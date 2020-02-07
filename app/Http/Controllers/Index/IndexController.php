<?php

namespace App\Http\Controllers\Index;
use App\Model\WxUsermodel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class IndexController extends Controller
{
    public function index(){
        $code=$_GET['code'];
        $data=$this->getAccessToken($code);
        //判断用户是否已存在
        $openid=$data['openid'];
        $u=WxUsermodel::where(['openid'=>$openid])->first();
        if($u){
            $user_info=$u->toArray();
        }else{
            $user_info=$this->getUserInfo($data['access_token'],$data['openid']);
            //入库
            WxUsermodel::insertGetId($user_info);
        }
        //微信配置
        $nonceStr = Str::random(8);
        $wx_config=[
            'appId'=>env('WX_APPID'),
            'timestamp'=>time(),
            'nonceStr'=>$nonceStr,
        ];
        $ticket=WxUsermodel::getJsapiTicket();
        $url=$_SERVER['APP_URL'].$_SERVER['REQUEST_URI'];
        $jsapi_signature = WxUserModel::jsapiSign($ticket,$url,$wx_config);
        $wx_config['signature'] = $jsapi_signature;
        
        $data=[
            'u'=>$user_info,
            'wx_config' => $wx_config
        ];
        return view('index.index',$data);
    }

    //根据code获取access_token
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
