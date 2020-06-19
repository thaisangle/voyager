<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Storage;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */

    public function index(Request $request)
    {
     
        // dd($request->name);
        $col_show = (new User())->col_show;
        //  dd($col_show);

        $fillable = array();
        foreach ($col_show as $key => $value) {
            $fillable[] = $key;
        }
        
        
        $sort = $request->sort;
        if($sort == null) $sort = "id-desc";
        
        $sort = explode("-", $sort);
        if($sort !== null && count($sort) !== 2 || !in_array($sort[1],['asc','desc']) || !in_array($sort[0], $fillable)){
            return abort('503','error $_GET for user');
        }

        $user = User::select('*');
        
    
        foreach ($col_show as $key => $value) {
            $col_show[$key]['data'] = $request->$key;
       
            if(isset($value['search']) && $request->$key){
                if($value['search'] == 'select')
                    $user = $user->where($key,$request->$key);
                    
                else
                    $user = $user->where($key,'like','%'.$request->$key.'%');
            }

        }
        $user = $user->where('ban',0)->paginate(2);
        
        // dd($col_show);
        return view('cms.pages.user.index', [
            'users'=> $user,
            'col_show'=>$col_show
        ]);
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('cms.pages.user.edit', [
            'user'=>$user,
        ]);
    }

    public function update(Request $request, $id)
    {
        //
        $user = User::findOrFail($id);
        $user->name = $request->name;        
        $user->email = $request->email;        
        $user->birth_day = strtotime($request->birth_day);
        $user->save();
        return redirect()->back()->with('success', "Update sucess!");       
    }

    // ban user
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $user->ban = 1 - $user->ban;
        
        $user->save();
        return redirect(route('user.index'))->with('success', "User with id=".$id."  baned!");;
    }

}
