<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Color;
use App\Product;
use App\SwapProduct;
use App\ColorProduct;
use App\MatchProduct;
use App\Traits\Helper;
use App\UserBuyProduct;
use App\UserLikeProduct;
use App\GalleryImageProduct;
use Illuminate\Http\Request;
use App\Jobs\SendNotifyMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Size;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function my_product(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $my_product = $user->product;
        foreach ($my_product as $key => $value) {
            $my_product[$key]->category;
            $my_product[$key]->append(['match']);
            $my_product[$key]->append(['check_seen']);
            $my_product[$key]->append(['user_buy']);
        }
        $my_product = $my_product->toArray();
        $old_product = Product::with('category')->where('old_user_id', $user->id)->get()->toArray();
        foreach ($old_product as $key => $value) {
            array_push($my_product, $value);
        }
        return Helper::sendSuccess($my_product);
    }

    public function index(Request $request)
    {
        //
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        $Product = $user->product_filter->orderBy('id', 'desc')->get();

        $list_product = [];

        foreach ($Product as $key => $value) {
            if ($key <= 49) {
                if ($user->check_premium()) {
                    $value['is_like'] = UserLikeProduct::where('user_id', $value->user_id)
                        ->where('of_user_id', $user->id)
                        ->exists() ? 1 : 0;
                } else {
                    $value['is_like'] = 0;
                }
                $list_product[] = $value;
            } else {
                break;
            }
        }
        return Helper::sendSuccess($list_product);
    }

    public function index_no_user(Request $request)
    {
        $list_product = Product::select('*');
        if ($request->category) {
            $category = json_decode($request->category, true);
            if ($category !== null && count($category)) {
                $category_id = [];
                foreach ($category as $key => $value) {
                    $category_id[] = $value['id'];
                }

                $list_product = $list_product->whereIn('category_id', $category_id);
            }
        }
        if ($request->size_id) {
            // check size 
            $size = Size::find($request->size_id);
            $size_feild = Size::where('ted_size', $size->ted_size)->get();
            $size_feild = $size_feild->map->id->all();
            // end check size
            $list_product = $list_product->whereIn('size_id', $size_feild);
        }
        if ($request->brand) {
            $brand = json_decode($request->brand, true);
            if ($brand !== null && count($brand)) {
                $brand = [];
                foreach ($brand as $key => $value) {
                    $brand[] = $value['name'];
                }
                $list_product = $list_product->whereIn('brand', $brand);
            }
        }
        $list_product = $list_product->where('active_status', 1)->orderBy('id', 'desc')->take(6)->get();
        return Helper::sendSuccess($list_product);
    }

    public function store(Request $request)
    {
        //
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        // $user = User::find(1);
        // $request = Helper::json_object_request($request);

        $validator = Validator::make($request->all(), [
            'image_main' => 'required|image'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $Product = new Product();
        $Product->user_id = $user->id;
        $Product->save();

        $disk = Storage::disk('public');

        $dir = $Product->image . $Product->id;
        $file = $request->file('image_main');
        $fileName = rand(1, 1000) . "-" . time() . '.' . $file->getClientOriginalExtension();
        $imagepath = $dir . "/" . $fileName;

        $disk->put($imagepath, file_get_contents($file));
        // Helper::correctImageOrientation(public_path(Storage::url($imagepath)));

        $thumbnailpath = $dir . "/thumbnail/" . $fileName;
        //Resize image here
        // Helper::correctImageOrientation($thumbnailpath);
        $resize = Image::make($file)->resize(300, 300)->encode($file->getClientOriginalExtension());
        $disk->put($thumbnailpath, $resize, 'public');

        $data = ['product_id' => $Product->id, 'path' => $fileName, 'status' => 1];
        $gallery = new GalleryImageProduct();
        $gallery = Helper::saveData($gallery, $data);
        $gallery->save();
        return Helper::sendSuccess(['id' => $Product->id, 'path_main_image' => $Product->path_main_image, 'image_main' => $Product->galleryImage()->where('status', 1)->first(), 'exif' => '']);
    }

    public function show(Request $request, $id)
    {
        //
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $lat = $user->setting->lat;
        $lng = $user->setting->lng;
        $Product = Product::select('*')->with('category');
        $haversine = "(6371 * acos(cos(radians(" . $lat . ")) 
                    * cos(radians(`lat`)) 
                    * cos(radians(`lng`) 
                    - radians(" . $lng . ")) 
                    + sin(radians(" . $lat . ")) 
                    * sin(radians(`lat`))))";
        $Product = $Product->selectRaw("{$haversine} AS distance")->find($id);

        if (!$Product) return Helper::sendResponse(false, 'Not found', 404, 'Product not found');
        $UserLikeProduct = UserLikeProduct::where('user_id', $user->id)->where('product_id', $Product->id)->whereIn('status', [1, 2])->exists();
        if ($user->check_premium()) {
            $Product['is_like'] = UserLikeProduct::where('user_id', $Product->user_id)->where('of_user_id', $user->id)->exists() ? 1 : 0;
        } else {
            $Product['is_like'] = 0;
        }
        $Product['like'] = $UserLikeProduct;
        return Helper::sendSuccess($Product);
    }

    public function cut_text_search($text_search)
    {
        $text_search = explode(' ', $text_search);
        $list_text = ['hastag' => [], 'text' => []];
        foreach ($text_search as $key => $value) {
            $tmp = trim($value);
            if (strlen($tmp)) {
                if ($tmp[0] == '#') $list_text['hastag'][] = substr($tmp, 1);
                else $list_text['text'][] = $tmp;
            }
        }
        return $list_text;
    }

    public function search(Request $request)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
            'distance' => 'required'
        ]);

        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $text_search = $request->text;
        $list_text = $this->cut_text_search($text_search);

        if ($list_text['text'] !== null && count($list_text['text'])) {
            $search_by_color = ColorProduct::join('colors', 'color_product.color_id', 'colors.id')->select('color_product.*', 'colors.name_of_color')->when($list_text['text'], function ($query, $list_text) {
                foreach ($list_text as $key => $value) {
                    $query = $query->orWhere('name_of_color', 'LIKE', "%" . $value . "%");
                }
                return $query;
            })->get()->toArray();
        } else {
            $search_by_color = [];
        }
        $list_product_id = [];
        foreach ($search_by_color as $key => $value) {
            # code...
            $list_product_id[] = $value['product_id'];
        }
        $param = [
            'text' => $list_text['text'],
            'hastag' => $list_text['hastag'],
            'list_product_id' => $list_product_id
        ];
        $product_in = Product::when($param, function ($query, $param) {
            $query = $query->whereIn('id', $param['list_product_id']);
            $query = Helper::whereForeach($query, 'brand', 'LIKE', $param['text'], 'or');
            $query = Helper::whereForeach($query, 'descriptions', 'LIKE', $param['hastag'], 'or');
            return $query;
        })->get();
        $list_product_id = [];
        foreach ($product_in as $key => $value) {
            $list_product_id[] = $value->id;
        }
        $Product = $user->not_see_product
            ->where('active_status', 1);
        if ($request->text)
            $Product = $Product->whereIn('id', $list_product_id);
        $haversine = "(6371 * acos(cos(radians(" . $request->lat . ")) 
                    * cos(radians(`lat`)) 
                    * cos(radians(`lng`) 
                    - radians(" . $request->lng . ")) 
                    + sin(radians(" . $request->lat . ")) 
                    * sin(radians(`lat`))))";
        $Product = $Product->select('*')->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$request->distance])->orderBy('id', 'desc')->get();
        return Helper::sendSuccess($Product);
    }

    public function update(Request $request, $id)
    {
        //
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        // $user = User::find(1);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'size_id' => 'required',
            'brand' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'required',
            'active_status' => 'required',
            'title' => 'required',
            'descriptions' => 'required',
            'urgent_swap_status' => 'required'
        ]);

        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $Product = Product::find($id);

        if (!$Product) return Helper::sendResponse(false, 'Not found', 404, 'Product not found');

        $data = $request->all();
        unset($data['color']);
        unset($data['urgent_swap_status']);
        unset($data['urgent_swap_start']);
        unset($data['urgent_swap_end']);
        $data['sell_now_status'] = 0;
        $data['swap_status'] = 0;
        $Product = Helper::saveData($Product, $data);
        if ($request->urgent_swap_status) {
            if ($user->check_premium()) {
                $Product->urgent_swap_status = $request->urgent_swap_status;
                $Product->urgent_swap_start = $request->urgent_swap_start;
                $Product->urgent_swap_end = $request->urgent_swap_end;
            } else {
                return Helper::sendResponse(false, 'Permision error', 502, 'You aren\'t premium');
            }
        }
        $color = json_decode($request->color, true);
        ColorProduct::where('product_id', $id)->delete();
        foreach ($color as $key => $value) {
            $check = ColorProduct::where('product_id', $id)->where('color_id', $value['id'])->get();
            if (!$check->count()) {
                $ColorProduct = new ColorProduct();
                $ColorProduct->product_id = $id;
                $ColorProduct->color_id = $value['id'];
                $ColorProduct->save();
            }
        }
        $Product->save();
        return Helper::sendResponse(true, 'success', 200, 'Update product success.');
    }

    public function upload_image(Request $request, $id)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        // $user = User::find(1);
        // $request = Helper::json_object_request($request);

        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        $Product = Product::find($id);
        if (!$Product) return Helper::sendResponse(false, 'Not found', 404, 'Product not found');
        $disk = Storage::disk('public');

        $dir = $Product->image . $Product->id;
        $file = $request->file('image');
        $fileName = rand(1, 1000) . "-" . time() . '.' . $file->getClientOriginalExtension();
        $imagepath = $dir . "/" . $fileName;

        $disk->put($imagepath, file_get_contents($file));
        // Helper::correctImageOrientation(public_path(Storage::url($imagepath)));

        $thumbnailpath = $dir . "/thumbnail/" . $fileName;
        //Resize image here
        // Helper::correctImageOrientation($thumbnailpath);
        $resize = Image::make($file)->resize(300, 300)->encode($file->getClientOriginalExtension());
        $disk->put($thumbnailpath, $resize, 'public');

        $gallery = new GalleryImageProduct();
        $gallery->product_id = $id;
        $gallery->path = $fileName;
        $gallery->status = 1;
        $gallery->save();
        return Helper::sendResponse(true, 'success', 200, 'Upload image success.');
    }

    public function update_image(Request $request, $id)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');

        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::sendResponse(false, 'Validator error', 400, $validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            //code...
            $gallery = GalleryImageProduct::find($id);
            $this->delete_image($request, $id);
            $Product = Product::find($gallery->product_id);
            if (!$Product) return Helper::sendResponse(false, 'Not found', 404, 'Product not found');
            $disk = Storage::disk('public');

            $dir = $Product->image . $Product->id;
            $file = $request->file('image');
            $fileName = rand(1, 1000) . "-" . time() . '.' . $file->getClientOriginalExtension();
            $imagepath = $dir . "/" . $fileName;

            $disk->put($imagepath, file_get_contents($file));
            // Helper::correctImageOrientation(public_path(Storage::url($imagepath)));

            $thumbnailpath = $dir . "/thumbnail/" . $fileName;
            //Resize image here
            // Helper::correctImageOrientation($thumbnailpath);
            $resize = Image::make($file)->resize(300, 300)->encode($file->getClientOriginalExtension());
            $disk->put($thumbnailpath, $resize, 'public');

            $gallery = new GalleryImageProduct();
            $gallery->product_id = $Product->id;
            $gallery->path = $fileName;
            $gallery->status = $request->status;
            $gallery->save();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return Helper::sendResponse(false, $th->getMessage(), 500, $th->getMessage());
        }

        return Helper::sendResponse(true, 'success', 200, 'Upload image success.');
    }

    public function delete_old_product(Request $request, $id)
    {
        $user = $request->user();
        $check = Product::where('old_user_id', $user->id)->where('id', $id)->update(['old_user_id' => null, 'swap_status' => 0, 'sell_now_status' => 0]);
        return Helper::sendResponse(true, 'success', 200, 'Delete product success.');
    }

    public function delete_image(Request $request, $id)
    {
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        $check_item = GalleryImageProduct::find($id);
        if (!$check_item) return Helper::sendResponse(false, 'Not found', 404, 'Image not found');
        $dir = (new Product)->image . $check_item->product_id;
        $disk = Storage::disk('public');
        if ($disk->exists($dir . "/" . $check_item->path)) {
            $disk->delete($dir . "/" . $check_item->path);
        }
        if ($disk->exists($dir . "/thumbnail/" . $check_item->path)) {
            $disk->delete($dir . "/thumbnail/" . $check_item->path);
        }
        $check_item->delete();

        return Helper::sendResponse(true, 'success', 200, 'Delete image success.');
    }

    public function destroy(Request $request, $id)
    {
        //
        $user = $request->user();
        if ($user === null) return Helper::sendResponse(false, 'Not found', 404, 'User not found');
        // $user = User::find(1);
        DB::beginTransaction();



        try {
            $Product = Product::find($id);
            if (!$Product) return Helper::sendResponse(false, 'Not found', 404, 'Product not found');
            MatchProduct::where('product_id', $id)->delete();
            MatchProduct::where('match_product_id', $id)->delete();
            UserBuyProduct::where('product_id', $id)->delete();
            UserLikeProduct::where('product_id', $id)->delete();
            ColorProduct::where('product_id', $id)->delete();
            $dir = dirname($_SERVER["SCRIPT_FILENAME"]) . $Product->image . $id;
            Helper::delTree($dir);
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::sendResponse(false, 'Delete folder', 400, 'Delete folder false.');
        }
        $Product->delete();
        DB::commit();
        return Helper::sendResponse(true, 'success', 200, 'Delete product success.');
    }
}
