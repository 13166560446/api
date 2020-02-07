<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class JifenController extends Controller
{
    public function index(){
        $code=$_GET['code'];
        $data=$this->getAccessToken($code);
        $user_info=$this->getUserInfo($data['access_token'],$data['openid']);
        dd($user_info);
    }


    protected function getAccessToken($code){
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET').'&code='.$code.'&grant_type=authorization_code';
        $json_data=file_get_contents($url);
        return json_decode($json_data,true);
    }

    //获取用户基本信息
    protected function getUserInfo($access_token,$openid){
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $json_data=file_get_contents($url);
        $data=json_decode($json_data,true);
        if(isset($data['errcode'])){
            // TODO 错误处理
            die('出错了 40001'); //40001标识获取用户信息失败

        }
        return $data; //返回用户信息
    }

}
