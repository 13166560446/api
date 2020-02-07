<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use App\Model\WxUsermodel;

class TestController extends Controller
{
    public function hello(){
       phpinfo();
    }

    public function adduser(){

        $pass ='123';
        $email='asdas';
        //使用秘钥函数
        $password = password_hash($pass,PASSWORD_BCRYPT);

        $data=[
            'user_name'=>'yyp',
            'password'=>$password,
            'email'=>$email
        ];
       $res=UserModel::insertGetId($data);
        var_dump($res);
    }

    public function ord(){
        $char=$_GET['char'];
        $length=strlen($char);
        $pass='';
        for($i=0;$i<$length;$i++){
            $pass.=chr(ord($char[$i])+3);
        }
        echo $pass;
    }
    public function chr(){
        $test=$_GET['test'];
        $length=strlen($test);
        $pass2='';
        for($i=0;$i<$length;$i++){
            $pass2.=chr(ord($test[$i])-3);
        }
        echo $pass2;
    }
}
