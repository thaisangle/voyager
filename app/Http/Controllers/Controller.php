<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotifyMessage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function notifi($data,$content, $list_user = null){
        $filter = [];
        $filter['included_segments'] = [];
        $filter['filters'] = [];
        foreach($list_user as $key => $value){
            if(!$key){
                $filter['filters'][] = ['key'=>'userId','value'=>$value,'relation'=>'='];
            }else{
                $filter['filters'][] = ['key'=>'userId','value'=>$value,'relation'=>'=','operator'=>'OR'];
            }
        }
        $more = [ 
            'data' => $data
        ];
        SendNotifyMessage::dispatch($filter,$content,$more);
    }
}
