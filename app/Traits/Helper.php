<?php

namespace App\Traits;

use Storage;
use Carbon\Carbon;
use function is_array;
use Mockery\Exception;
use function is_object;
use function json_decode;
use function json_encode;
use App\Jobs\SendNotifyMessage;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: HunG
 * Date: 2018-07-06
 * Time: 8:40 AM
 */

class Helper extends Collection
{


    /**
     * @param $status
     * @param $data
     * @param $code
     * @param string $message
     * @return \Illuminate\Http\JsonResponse|string
     */

    /**
     * @param string $filename  
     *    
     * @return void
     *  */
    public static function correctImageOrientation($filename)
    {
        if (function_exists('exif_read_data')) {
            $exif = exif_imagetype($filename) == 2 ? exif_read_data($filename) : null;
            if ($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if ($orientation != 1) {
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }
                    // then rewrite the rotated image back to the disk as $filename 
                    imagejpeg($img, $filename, 95);
                } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists      
    }

    public static function whereForeach($query, $attribute, $method, $data, $where = 'and')
    {
        foreach ($data as $key => $value) {
            $_value = $method == 'LIKE' ? "%" . $value . "%" : $value;
            $query = $where == 'and' ? $query->where($attribute, $method, $_value) : $query->orWhere($attribute, $method, $_value);
        }
        return $query;
    }

    public static function saveData($query, $data)
    {
        foreach ($data as $key => $value) {
            $query->$key = $value;
        }
        return $query;
    }

    public static function vali_require($request, $fillable)
    {
        foreach ($fillable as $key => $value) {
            if (
                !isset($request->$value) ||
                $request->$value == null ||
                $request->$value == ''
            )
                return Helper::sendResponse(false, 'Validator error', 400, "the " . $value . " field is required");
        }
    }

    public static function json_object_request($request)
    {
        return json_decode(json_encode($request->json()->all()));
    }

    public static function sendResponse($status, $data, $code, $message = 'None message')
    {
        if ($status === true)
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data
            ], $code);
        elseif ($status === false) return response()->json([
            'status' => $status,
            'error' => $data,
            'message' => $message
        ], $code);
        else return Exception::class;
    }

    public static function upload_file_default($dir, $file, $fileName, $file_change = null)
    {
        $disk = Storage::disk('public');
        if ($file_change) {
            if ($disk->exists($file_change)) {
                $disk->delete($file_change);
            }
        }
        $fileContents = $file;
        $fileName = $fileName . "." . $file->getClientOriginalExtension();
        $filePath = $dir . $fileName;
        $disk->put($filePath, file_get_contents($fileContents));

        $url = $disk->url($filePath);
        return $url;
    }
    /**
     * @param $status
     * @param string $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse|string
     */
    public static function sendResponseWithoutData($status, $message = 'None message', $code)
    {
        if ($status === true)
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $code);
        elseif ($status === false) return response()->json([
            'status' => $status,
            'error' => $message
        ], $code);
        else return Exception::class;
    }

    public static function sendSuccess($data)
    {
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }
    /**
     * @param bool $status
     * @param $message
     * @param $data
     * should be the paginator instance
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendResponsePaginating($status = true, $message, $data, $code)
    {
        $data = json_decode(json_encode($data));

        $payload = $data->data;
        $temp = [];
        if (is_object($payload)) {
            foreach ($payload as $p) {
                $temp[] = $p;
            }
            $payload = $temp;
        }

        $pagination = array([
            "current_page" =>  $data->current_page,
            "from_record" =>  $data->from,
            "to_record" =>  $data->to,
            "total_record" =>  $data->total,
            "record_per_page" =>  (int) $data->per_page,
            "total_page" =>  $data->last_page,
            //            "first_page_url" =>  $data->first_page_url,
            //            "prev_page_url" =>  $data->prev_page_url,
            //            "next_page_url" =>  $data->next_page_url,
        ])[0];
        return response()->json(
            [
                'status' => $status,
                'message' => $message,
                'pagination' => $pagination,
                'data' => $payload,
            ],
            $code
        );
    }
    /**
     * @param Carbon $string
     * @return int
     */
    public static function carbon_to_int(Carbon $string)
    {
        return $string->timestamp;
    }

    /**
     * @param $string
     * @return int
     */
    public  static  function  str_to_int($string)
    {
        return time($string);
    }

    public static function request_only($request, $fillable)
    {
        $array = [];
        foreach ($fillable as $key => $value) {
            $array[$value] = $request[$value];
        }
        return $array;
    }

    public static  function array_to_object($data)
    {
        return json_decode(json_encode($data));
    }

    public static  function write_log_error($exception, $device, $url)
    {
        $message = "\n ------------" . date('H:i:s') . "-" . $device . "-------------\n";
        $message .= "url: " . $url;
        $message .= "\nMessage: " . $exception->getMessage();
        $message .= "\nFile: " . $exception->getFile();
        $message .= "\nLine: " . $exception->getLine();
        $message .= "\n ------------end------------\n";
        Storage::prepend('logErrors/' . date('Y-m-d') . '.log', $message);
    }
    public static function delTree($dir)
    {
        if (file_exists($dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
            }
            return rmdir($dir);
        }
        return false;
    }

    public function notifi($data, $content, $list_user = null)
    {
        $filter = [];
        $filter['included_segments'] = [];
        $filter['filters'] = [];
        foreach ($list_user as $key => $value) {
            if (!$key) {
                $filter['filters'][] = ['key' => 'userId', 'value' => $value, 'relation' => '='];
            } else {
                $filter['filters'][] = ['key' => 'userId', 'value' => $value, 'relation' => '=', 'operator' => 'OR'];
            }
        }
        $more = [
            'data' => $data
        ];
        SendNotifyMessage::dispatch($filter, $content, $more);
    }
}
