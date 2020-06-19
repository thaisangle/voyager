<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Storage;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */

    public function index(Request $request)
    {
        $user = User::where('ban',0)->get();
        
        $cnt_user = $user->count()?$user->count():1;
        $facebook = User::where('ban',0)->where('type_social','facebook')->count();
        $gmail = User::where('ban',0)->where('type_social','gmail')->count();
        $data_user = array();
        $data_user[] = ['label' => 'Facebook ('.$facebook.')','value' => ($facebook *100/ $cnt_user)];
        $data_user[] = ['label' => 'Gmail ('.$gmail.')','value' => ($gmail *100/ $cnt_user), 'count' => $gmail];
        $data_user[] = ['label' => 'Normal ('.($cnt_user - $gmail - $facebook).')','value' => (100 -  $data_user[0]['value'] - $data_user[1]['value'])];
        return view('cms.pages.home.index', [
            'user'=> $user,
            'data_user' => $data_user
        ]);
    }

    public function log_error(Request $request)
    {
        $dir = Storage::get('logErrors/'.date('Y-m-d').'.log');
        dd($dir); 
    }

}
