<?php

namespace App\Admin\Controllers;

use App\Model\GoodsModel;
use App\Model\WxUsermodel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;


class WxmsgController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $access_token;
    public function __construct(){
        //获取access_token
        $this->access_token=$this->getAccessToken();
    }
    public function getAccessToken(){
        $key=   'wx_access_token';

        $access_token=Redis::get($key);
        if($access_token){
            return $access_token;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
        $data_json = file_get_contents($url);
        $arr = json_decode($data_json,true);
        Redis::set($key,$arr['access_token']);
        Redis::expire($key,3600);
        return $arr['access_token'];
    }


    protected $title = '群发';

    public function sendmsg(){
        $openid_arr=WxUsermodel::select('openid')->get()->toArray();
        $openid=array_column($openid_arr,'openid');
//        print_r($openid);
        $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->access_token;
        $msg=date('Y-m-d H:i:s').'哈哈哈哈哈';
        $data=[
            'touser'=>$openid,
            'msgtype'=>'text',
            'text'=>['content'=>$msg]
        ];
        $client=new Client();
        $response=$client->request('POST',$url,[
            'body'=>json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $response->getBody();
    }



}
