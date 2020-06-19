<?php
/**
 * Created by PhpStorm.
 * User: HunG
 * Date: 2018-07-31
 * Time: 9:47 AM
 */

namespace App\Traits;


use function config;
use function dd;
use function env;

class Notify
{

   private static $app_id;
   private static $rest_api_key ;
   private static $user_auth_key;
   private static $api_access_key;

    /**
     * @return mixed
     */
    public static function getAppId()
    {
        return self::$app_id;
    }

    /**
     * @return mixed
     */
    public static function getRestApiKey()
    {
        return self::$rest_api_key;
    }

    /**
     * @return mixed
     */
    public static function getUserAuthKey()
    {
        return self::$user_auth_key;
    }

    /**
     * @return api_access_key
     */
    public static function getApiAccessKey()
    {
        return self::$api_access_key;
    }

    /**
     * @param mixed $app_id
     */
    public static function setAppId($app_id)
    {
        self::$app_id = $app_id;
    }

    /**
     * @param mixed $rest_api_key
     */
    public static function setRestApiKey($rest_api_key)
    {
        self::$rest_api_key = $rest_api_key;
    }

    /**
     * @param mixed $user_auth_key
     */
    public static function setUserAuthKey($user_auth_key)
    {
        self::$user_auth_key = $user_auth_key;
    }

    /**
     * @param mixed $api_access_key
     */
    public static function setApiAccsessKey($api_access_key)
    {
        self::$api_access_key = $api_access_key;
    }

    public function __construct()
    {
        self::$app_id = env('NOTI_APP_ID','24db9a60-9532-415d-ad50-7951788f1018');
        self::$rest_api_key = env('NOTI_REST_API_KEY','YmNkMjBiZTYtNjBmYi00ODhjLWEyMmEtYjliYWEzMGY0ZmFj');
        
        self::$user_auth_key = env('NOTI_USER_AUTH_KEY','MDA4ZDJjMmItZGQ2Yy00YjI2LTgyNDktYTFhYTRkMjhjODg3');
        self::$api_access_key = env('NOTI_API_ACCESS_KEY','AAAANPWk4pU:APA91bHcuO6kBGi8xmhE95w3Ed5J5-ncXKy1mi4auXkC0t_YCGTiGhAQMVY8WuRJXg_Pc6ua1u8E3Aj0MNF59Y-Sgbuf0kNN_XW8SvQMHo8vGvWTOk0e2fJhRsLJuF9F2T_J9JP2dEb6GgHetF7riYe1yTFfXZS2Aw');
    }

    public function sendNotifyToUser( $filter, $content, $more = null){
        $arr = [];
        $arr['filters'] = [];
        foreach ($filter['filters'] as $key => $value) {
            if(isset($value['operator'] ))
                $arr['filters'][] = ["operator"=> "OR"];

            $arr['filters'][] = ["field" => "tag", "key" => $value['key'], "relation" => $value['relation'], "value" => $value['value']];
        }
        $arr['included_segments'] = $filter['included_segments'];
        $filter =  $arr;

        $response = $this->sendMessage($filter, $content, $more);
       return $response;
    }
    public function sendMessage( $filter, $content, $more = null) {
        $content_arr      = array(
            "en" => $content
        );
        $fields = array(
            'app_id' => self::getAppId(),
            'filters' => $filter['filters'],
            'included_segments' =>$filter['included_segments'],
            'content_available' => true,
            'mutable_content' => true,
            'contents' => $content_arr
        );
        if($more){
            foreach($more as $key =>$value){
                $fields[$key] = $value;
            }
        }
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic '.self::getRestApiKey(),
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function pushInfo( $user_id, $identifier,$device_type) {
        $fields = array(
            'app_id' => self::getAppId(),
            'identifier' => $identifier, 
            'language' => "en", 
            'game_version' => "1.0",
            'device_type' => $device_type, 
            'tags' => array("userId"=>$user_id) 
        );
        $fields = json_encode($fields); 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players"); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_HEADER, FALSE); 
        curl_setopt($ch, CURLOPT_POST, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 

        $response = curl_exec($ch); 
        curl_close($ch); 

        return $response;

    }


}