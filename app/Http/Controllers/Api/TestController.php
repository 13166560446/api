<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ApiloginModel;
use Illuminate\Support\Str;
class TestController extends Controller
{
    public function test(){
        $user_info=[
            'name'=>'王大锤',
            'tel'=>'7451',
            'email'=>'2256006378'
        ];
        echo json_encode($user_info);
    }
    public function reg(request $request){
        $pass1=$request->input('pass1');
        $pass2=$request->input('pass2');
        if($pass1!=$pass2){
            die('输入的两次密码不一致');
        }
        $password=password_hash($pass1,PASSWORD_BCRYPT);
        $data=[
            'email' =>$request->input('email'),
            'name'  =>$request->input('name'),
            'password'=> $password,
            'last_login'=>time(),
            'last_ip'=>$_SERVER['REMOTE_ADDR'],
        ];
        $res=ApiloginModel::insertGetId($data);
        echo $res;
//     echo   json_encode($data);

    }
    public function login(request $request){
        $name=$request->input('name');
        $password=$request->input('password');
        $a=ApiloginModel::where(['name'=>$name])->first();
        if($a){
            if(password_verify($password,$a->password)){
                echo '登陆成功';
                //生成token
                $token=Str::random(32);
                $response=[
                    'errno'=>0,
                    'msg'=>'ok',
                    'data'=>[
                        'token'=>$token
                    ]
                ];
//                return $response;
            }else{
                $response=[
                    'errno'=>40002,
                    'msg'=>'密码不正确'
                ];
            }

        }else{
            $response=[
                'errno'=>40003,
                'msg'=>'用户不存在'
            ];
        }
        return $response;

    }



    //获取用户列表
    public function userlist(){
        $data=ApiloginModel::all();
        echo '<pre>';print_r($data);echo '</pre>';die;
//        echo '<pre>';print_r($_SERVER);echo '</pre>';die;
//        $user_token=$_SERVER['HTTP_TOKEN'];
//        echo 'user_token: '.$user_token;echo '</br>';
        $current_url=$_SERVER['REQUEST_URI'];
        echo "当前URL: ".$current_url;echo '</hr>';



        $redis_key='str:count:u'.':url:'.md5($current_url);
        echo 'redis key: '.$redis_key;echo '</br>';

        $count=Redis::get($redis_key);  //获取接口的访问次数
        echo "接口的访问次数: ".$count;echo '</br>';
        if($count>=5){
            echo '请不要频繁访问此接口,访问次数已上限,请稍后再试';
            Redis::expire($redis_key,3600);
            die;
        }
        $count=Redis::incr($redis_key);
        echo 'count: '.$count;

    }




    //测试接口


    public function md5test(){
        //发送的数据
        $data='yyp';  //要发送的数据
        $key='1905';
        //计算签名
        $signature=md5($data.$key);
        echo "待发送的数据：". $data;echo '</br>';
        echo "发送端的签名：".$signature;echo '</br>';

        //发送数据
        $url='http://passport.1905.com/index/check?data='.$data.'&signature='.$signature;
        //echo $url;echo '<hr>';
        $response=file_get_contents($url);
        echo $response;
    }

    public function check2()
    {
        $key = "1905";          

        //待签名的数据
        $order_info = [
            "name"          => 'yyp',
            "order_amount"  => mt_rand(111,999),
            "add_time"      => time(),
        ];
        $data_json = json_encode($order_info);
        //计算签名
        $sign = md5($data_json.$key);
        // post 表单（form-data）发送数据
        $client = new Client();
        $url = 'http://passport.1905.com/index/check2';
        $response = $client->request("POST",$url,[
            "form_params"   => [
                "data"  => $data_json,
                "sign"  => $sign
            ]
        ]);

        //接收服务器端响应的数据
        $response_data = $response->getBody();
        echo $response_data;

        
    }
}
