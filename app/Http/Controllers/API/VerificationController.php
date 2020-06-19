<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Traits\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    // use VerifiesEmails;
    /**
    * Show the email verification notice.
    *
    */
    public function show()
    {
    //
    }
    /**
    * Mark the authenticated user’s email address as verified.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function verify(Request $request,$verify_code) {
        $user = User::where('verify_code',$verify_code)->first();
        if(!$user){
            return Helper::sendResponse(false, 'Error verifi code fail.', 400, 'Error verifi code fail.');
        }
        $now = time();
        if((int)$user->verify_expires - $now <= 0){
            return Helper::sendResponse(false, 'Error verifi code expires.', 400, 'Error verifi code expires.');
        }
        
        $date = date("Y-m-d g:i:s");
        $user->email_verified_at = $date; // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
        $user->save();
        $token_field = $user->createToken('Login Token')->accessToken;
        return Helper::sendResponse(true, $token_field, 200, 'success');
    }
    /**
    * Resend the email verification notification.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function resend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $user = User::where('email',$request->email)->first();
        $user->verify_code = ($user->id+20).Str::random(6);
        
        $user->verify_expires = strtotime(Carbon::now()->addMinutes(10));
        $user->save();
        if ($user->hasVerifiedEmail()) {
            return Helper::sendResponse(false, 'User already have verified email!', 422, 'error');
            // return redirect($this->redirectPath());
        }
        $user->sendApiEmailVerificationNotification();
        return Helper::sendResponse(true, 'The notification has been resubmitted', 200, 'success');
        // return back()->with(‘resent’, true);
    }
}