<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Size;
use App\Product;
use App\Color;
use App\Brand;
use App\GalleryImageProduct;
use App\ColorProduct;
use App\Traits\Helper;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        // DB::table('colors')->insert(['id' => 1, 'name' => 'Ligth','parent_id' => null]);
        // DB::table('colors')->insert(['id' => 2, 'name' => 'Black','parent_id' => null]);
        // DB::table('colors')->insert(['name' => '#FFFFF','parent_id' => 1]);
        // DB::table('colors')->insert(['name' => '#FEFEFE','parent_id' => 1]);
        // DB::table('colors')->insert(['name' => '#FFFFFF','parent_id' => 2]);
        $dir = public_path()."/upload/product/";
        // $size = Size::where('parent','>',1)->get();
        $Brand = ['Zara','Forever 21','Calvin Klein','DKNY','Element','Ellery','2XU','Gap'];
        $user = User::get();
        foreach($user as $key => $value){
            for ($i=0; $i < 3; $i++) { 
                # code...
                $dataProduct = ['user_id'=>$value->id, 
                                'category_id'=>rand(1,4),
                                'pur_price'=>rand(12,18),
                                'title'=>'vay'.$key,
                                'brand'=>$Brand[rand(0,7)],
                                'active_status'=>1,
                                'swap_status'=> 0,
                                'sell_now_status'=>0,
                                'lat'=>'16.5658557',
                                'lng'=>'107.5211176',
                                'address'=>'H',
                ];
                $Product = new Product(); 
                $Product = Helper::saveData($Product,$dataProduct);
                $Product->save();
                mkdir($dir.$Product->id);
                $fileName = $Product->id."-".rand(1,1000)."-".time().'.jpg';
                $sourceFilePath=$dir."vay".rand(1,20).".jpg";
                $destinationPath=$dir.$Product->id."/".$fileName;
                $success = \File::copy($sourceFilePath,$destinationPath);
                $data = ['product_id'=>$Product->id,'path'=>$fileName,'status'=>1];
                $gallery = new GalleryImageProduct();
                $gallery = Helper::saveData($gallery,$data);
                $gallery->save();
                $ColorProduct = new ColorProduct();
                $ColorProduct->product_id = $Product->id;
                $ColorProduct->color_id = 13;
                $ColorProduct->save();
                $ColorProduct = new ColorProduct();
                $ColorProduct->product_id = $Product->id;
                $ColorProduct->color_id = 14;
                $ColorProduct->save();
                $ColorProduct = new ColorProduct();
                $ColorProduct->product_id = $Product->id;
                $ColorProduct->color_id = 15;
                $ColorProduct->save();
            }
        }
        

    }
}
