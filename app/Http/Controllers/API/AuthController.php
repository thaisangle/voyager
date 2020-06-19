<?php

namespace App\Http\Controllers\API;

use App\Card;
use App\User;
use Socialite;
use function bcrypt;
use function config;
use App\ChargeStripe;
use App\Traits\Helper;
use App\Traits\Notify;
use Mockery\Exception;
use Illuminate\Support\Str;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Jobs\SendNotifyMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotifyCreateDevice;
use App\UserBuyProduct;
use App\UserLikeProduct;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Stripe\Subscription;

class AuthController extends Controller
{
    // serve 
    use VerifiesEmails;
    private $serve = null;
    public function __construct()
    {
        $this->serve = env('APP_URL', 'http://localhost/dreesu/public');
    }
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            // Authentication passed...
            return Helper::sendResponse(false, 'Error.', 400, 'Username or password is incorrect.');
        }
        $user = Auth::user();

        if ($user) {
            if ($user->ban == 1) {
                return Helper::sendResponseWithoutData(false, 'Account be banned', 403);
            }
        }
        $input = $request->all();
        if (!$user->email_verified_at && !$user->social_id) {
            $mes = 'Unauthenticated account. Please check your email.';
            return Helper::sendResponse(false, 'Unauthorised.', 401, $mes);
        }
        $token_field = $user->createToken('Login Token')->accessToken;
        return Helper::sendResponse(true, $token_field, 200, 'success');
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return Helper::sendResponse(true, 'Resent successfully', 200, "successfully");
    }
    /**
     * Register apiad ads asd
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required',
            'birth_day' => 'integer|required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $input = $request->all();
        $password = $input['password'];
        $input['password'] = bcrypt($input['password']);
        // dd($input);
        // DB::beginTransaction();
        // // Tao user
        // try {
        $user = User::create($input);
        $user->verify_code = ($user->id + 20) . Str::random(6);
        $user->verify_expires = strtotime(Carbon::now()->addMinutes(10));
        $user->save();
        if (!$request->setting_json) {
            $json = [
                "category" => "[]",
                "size_id" => 23,
                "brand" => "[]",
                "color" => "[]",
                "lat" => "-33.872452",
                "lng" => "151.208721",
                "swap_today" => 0,
                "distance" => 100,
                "time_swap_today" => 1561295460
            ];
            $user->setting_json = json_encode($json);
            $user->save();
        }
        $user->sendApiEmailVerificationNotification();
        $mes = 'Unauthenticated account. Please check your email.';
        return Helper::sendResponse(true, $mes, 200, 'success');
        // } catch (Exception $exception) {
        //     DB::rollBack();
        // }

        //password grant access token
        // DB::commit();
        // $http = new \GuzzleHttp\Client;
        // try {
        //     $response = $http->post($this->serve . '/oauth/token', [
        //         'form_params' => [
        //             'grant_type' => 'password',
        //             'client_id' => $input['client_id'],
        //             'client_secret' => $input['client_secret'],
        //             'username' => $user->email,
        //             'password' => $password,
        //             'scope' => '',
        //         ],
        //     ]);
        //     DB::commit();
        //     // SendNotifyCreateDevice::dispatch($user->id,$request->identifier,$request->device_type);

        //     $token_field = json_decode((string)$response->getBody(), true);
        //     return Helper::sendResponse(true, $token_field, 200, 'success');
        // } catch (RequestException  $e) {
        //     DB::rollBack();
        //     return Helper::sendResponse(false, 'Grant token error', 200,"grant error");
        // }
        //

    }
    public function getAccessToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post('https://api.instagram.com/oauth/access_token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => env('INSTAGRAM_CLIENT_ID', 'ba22e1a7e34c4302bfb8f7badaa7a1a5'),
                    'client_secret' => env('INSTAGRAM_CLIENT_SECRET', '799a458e5b214a08a967ef7329473788'),
                    'redirect_uri' => env('INSTAGRAM_URI_REDIRECT', 'http://dreesu.stdiohue.com/api/getAccessToken'),
                    'code' => $request->code
                ],
            ]);
            $res = json_decode((string) $response->getBody());
            DB::beginTransaction();
            $input = $request->only(['social_token', 'email', 'birth_day', 'social_id', 'avatar', 'name', 'type_social']);
            $user = User::where('social_id', $res->user->id)->first();
            if ($user) {
                $user->update(['social_token' => $res->access_token, 'name' => $res->user->full_name, 'avatar' => $res->user->profile_picture]);
            } else {
                $user = new User();
                $user->social_token = $res->access_token;
                $user->social_id = $res->user->id;
                $user->avatar = $res->user->profile_picture;
                $user->name = $res->user->full_name;
                $user->type_social = 'instagram';
                $user->save();
            }
            $token_field = $user->createToken('Social Token')->accessToken;
            DB::commit();
            return Helper::sendResponse(true, $token_field, 200, 'success');
        } catch (RequestException  $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            return Helper::sendResponse(false, 'Grant token error', 401, $response);
        }
    }
    // public function login_instagram(Request $request){
    //     return redirect('https://api.instagram.com/oauth/authorize?client_id='.env('INSTAGRAM_CLIENT_ID','ba22e1a7e34c4302bfb8f7badaa7a1a5').'&redirect_uri='.env('INSTAGRAM_URI_REDIRECT','http://dreesu.stdiohue.com/api/getAccessToken').'&response_type=token');
    // } 

    public function register_via_social(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_token' => 'required',
            'email' => 'email',
            'birth_day' => 'integer',
            'social_id' => 'required',
            'type_social' => 'required',
        ]);

        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }

        DB::beginTransaction();
        $input = $request->only(['social_token', "setting_json", 'email', 'birth_day', 'social_id', 'avatar', 'name', 'type_social']);
        $user = User::where('social_id', $request->social_id)->first();
        if ($user) {
            $user->update($input);
        } else {
            $user = User::create($input);
            if (!$request->setting_json) {
                $json = [
                    "category" => "[]",
                    "size_id" => 23,
                    "brand" => "[]",
                    "color" => "[]",
                    "lat" => "-33.872452",
                    "lng" => "151.208721",
                    "swap_today" => 0,
                    "distance" => 100,
                    "time_swap_today" => 1561295460
                ];
                $user->setting_json = json_encode($json);
                $user->save();
            }
        }
        //  SendNotifyCreateDevice::dispatch($user->id,$request->identifier,$request->device_type);
        $token_field = $user->createToken('Social Token')->accessToken;
        DB::commit();
        return Helper::sendResponse(true, $token_field, 200, 'success');
    }


    public function getUserViaToken(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $user->star = round($user->star, 2) . '';
        return Helper::sendResponse(true, $user, 200, 'success');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function refresh_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_secret' => 'required',
            'client_id' => 'required',
            'refresh_token' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }

        //password grant access token
        $input = $request->all();

        $http = new \GuzzleHttp\Client;
        try {

            $response = $http->post($this->serve . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => '4',
                    'client_secret' => $input['client_secret'],
                    'refresh_token' => $input['refresh_token'],
                    'scope' => '',
                ],
            ]);
            $token_field = json_decode((string) $response->getBody(), true);
            //                $result = array_merge(User::find($user->id)->toArray(), $token_field);
            return Helper::sendResponse(true, $token_field, 200, 'success');
        } catch (RequestException  $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            return Helper::sendResponse(false, 'Grant token error', 401, $response->message);
        }
        //

    }

    public function setting_filter(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'size_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'distance' => 'required',
            'swap_today' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $data_request = $request->all();
        $category_list = json_decode($request->category, true);
        foreach ($category_list as $key => $value) {
            $category_list[$key]['id'] = (int) $value['id'];
        }
        $data_request['category'] = json_encode($category_list);

        $color_list = json_decode($request->color, true);
        foreach ($color_list as $key => $value) {
            $color_list[$key]['id'] = (int) $value['id'];
        }
        $data_request['color'] = json_encode($color_list);
        $data_request['time_swap_today'] = time();
        $data = json_encode($data_request);
        $user->setting_json = $data;

        $user->save();
        return Helper::sendResponse(true, 'Success.', 200, 'Success.');
    }

    public function delete_account(Request $request)
    {
        $user = $request->user();
        DB::beginTransaction();
        try {
            //code...
            UserBuyProduct::where('user_id', $user->id)->delete();
            UserBuyProduct::where('of_user_id', $user->id)->delete();
            UserLikeProduct::where('user_id', $user->id)->delete();
            UserLikeProduct::where('of_user_id', $user->id)->delete();
            $user->delete();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return Helper::sendResponse(false, $th->getMessage(), 500, $th->getMessage());
        }

        return Helper::sendResponse(true, 'Delete account success.', 200, 'success');
    }

    public function test_delete_skip(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        return Helper::sendResponse(true, 'Delete success.', 200, 'success');
    }

    public function add_card(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        $validator = Validator::make($request->all(), [
            'source' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $stripe = Stripe::make(config('services.stripe.secret'));
        if (!$user->stripe_id) {
            $customer = $stripe->customers()->create([
                'email' => $user->email,
            ]);
            $user->stripe_id = $customer['id'];
            $user->save();
        }
        $token_card = $stripe->cards()->create($user->stripe_id, $request->source);

        return Helper::sendResponse(true, 'Add card success.', 200, 'Success.');
    }

    public function list_card(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        $stripe = Stripe::make(config('services.stripe.secret'));
        $data = [];
        if ($user->stripe_id) {
            $data = $stripe->cards()->all($user->stripe_id);
        }
        return Helper::sendSuccess($data);
    }

    public function delete_card(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'card_stripe_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $stripe = Stripe::make(config('services.stripe.secret'));
        $data = $stripe->cards()->delete($user->stripe_id, $request->card_stripe_id);
        if ($data) {
            return Helper::sendResponse(true, 'Delete card success.', 200, 'Success.');
        }
        return Helper::sendResponse(true, 'Delete card fail.', 400, 'Fail.');
    }

    public function select_active_card(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found.', 404, 'User not found.');

        $validator = Validator::make($request->all(), [
            'card_stripe_id' => 'required',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $stripe = Stripe::make(config('services.stripe.secret'));

        $customer = $stripe->customers()->update($user->stripe_id, ['default_source' => $request->card_stripe_id]);
        return Helper::sendResponse(true, 'Select card success.', 200, 'Success.');
    }

    public function buy_premium(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found.', 404, 'User not found.');
        if (!$user->stripe_id) return Helper::sendResponse(false, 'You haven\'t got card stripe.', 404, 'You haven\'t got card stripe.');
        $stripe = Stripe::make(config('services.stripe.secret'));

        $list_subscription = $stripe->subscriptions()->all($user->stripe_id);
        if ($list_subscription['data'] !== null && !count($list_subscription['data'])) {
            $plan = $user->is_trial ? 'plan_FGoUkc75qDAJ45' : 'plan_FGoTRBbLYRUDGN';

            $subscription = $stripe->subscriptions()->create($user->stripe_id, [
                'plan' => $plan,
            ]);

            $data_user = ['premium_start_at' => time(), 'vip' => 'premium'];
            $user = Helper::saveData($user, $data_user);
            $user->save();
        }

        return Helper::sendResponse(true, 'Success.', 200, 'Success.');
    }

    public function cancel_premium(Request $request)
    {
        $user = $request->user();
        if (!$user) return Helper::sendResponse(false, 'Not found.', 404, 'User not found.');
        if (!$user->stripe_id) return Helper::sendResponse(false, 'You haven\'t got card stripe.', 404, 'You haven\'t got card stripe.');

        $stripe = Stripe::make(config('services.stripe.secret'));
        $subscription = $stripe->subscriptions()->all($user->stripe_id);
        if (count($subscription['data'])) {
            $stripe->subscriptions()->cancel($user->stripe_id, $subscription['data'][0]['id'], true);
            return Helper::sendResponse(true, 'Success.', 200, 'Success.');
        }
        return Helper::sendResponse(true, 'Cancel fail.', 200, 'Fail.');
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();
        if (isset($request->name))
            $user->name = $request->name;
        if (isset($request->phone))
            $user->phone = $request->phone;
        if ($user->type_social == 'default' && ($request->old_password || $request->new_password || $request->confirm_password)) {

            if (isset($request->old_password) && isset($request->confirm_password) && isset($request->new_password)) {
                if (Hash::check($request->old_password, Auth::user()->password)) {
                    if ($request->confirm_password == $request->new_password) {
                        $request->user()->fill([
                            'password' => Hash::make($request->new_password)
                        ])->save();
                    } else {
                        return Helper::sendResponse(false, 'Validator error', 400, 'Confirm password failt');
                    }
                } else {
                    return Helper::sendResponse(false, 'Validator error', 400, 'Old password failt');
                }
            } else {
                return Helper::sendResponse(false, 'Validator error', 400, 'old password and new password and confirm password required');
            }
        }
        // if(isset($request->avatar)){
        //    if($request->hasFile('avatar')){
        //         $dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/upload/user/';
        //         $file = $request->avatar;
        //         $fileName = $user->id."-".rand(1,1000)."-".time().'.'.$file->getClientOriginalExtension();
        //         $file->move($dir,$fileName);
        //         $fileName = $fileName;
        //         $user->avatar = env('APP_URL','gapmash.stdiohue.com').User::$path.$fileName;
        //    } else {
        //         return Helper::sendResponse(false, 'error', 400, 'upload avatar error!'); 
        //    }
        // }

        $user->save();
        return Helper::sendResponse(true, $user, 200, 'success');
    }

    public function sendNotify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'value' => 'required',
            'content' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $content = $request->content;
        $data = [
            'data' => $request->data
        ];
        $this->notifi($data, $content, [$request->value]);
        return Helper::sendResponse(true, 'Send success', 200, 'success');
    }
}
