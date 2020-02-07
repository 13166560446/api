<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class filtrate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(isset($_SERVER['HTTP_TOKEN'])){
            echo 1;die;
            $redis_key='str:count:u'.$_SERVER['HTTP_TOKEN'].':url:'.$_SERVER['REQUEST_URI'];
            $count=Redis::get($redis_key);
            if($count>=5){
                Redis::expire($redis_key,60);
                $response=[
                    'errno'=>40004,
                    'msg'=>'接口请求已达上限请稍后再试'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
        }else{
            $response=[
                'errno'=>40005,
                'msg'=>'没有授权'
            ];
            json_encode($response,JSON_UNESCAPED_UNICODE);
        }
        return $next($request);
    }
}
