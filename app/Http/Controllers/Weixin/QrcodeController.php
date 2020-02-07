<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Model\WxUsermodel;

class QrcodeController extends Controller
{
    public function qrcode(){
        $scene_id=$_GET['qrcode'];
        $access_token=WxUsermodel::getAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        $client=new Client();
        //{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
        $data=[
            'expire_seconds'=>'604800',
            'action_name'=>'QR_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_id'=>$scene_id
                ]
            ]
        ];
        $response=$client->request('POST',$url,[
            'body'=>json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        $data=$response->getBody();
//        echo $data;die;
        $data_json=json_decode($data,true);
        $ticket=$data_json['ticket'];
        $url2='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
        return redirect($url2);


    }
}
