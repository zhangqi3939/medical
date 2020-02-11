<?php
/**
 * 公共函数
 * Created by PhpStorm.
 * User: Phoebus
 * Date: 2020/2/11
 * Time: 19:32
 */

function curl_post($url,$pdata,$json=FALSE){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($json){

    }
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $pdata);
    $output = curl_exec($ch);
    //打印获得的数据
    curl_close($ch);
    return $output;
}

function curl_get($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);// 要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_HEADER,0);//不要http header 加快效率
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_TIMEOUT,20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function send_json($data){
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function app_send($data='',$code='200',$reason=''){
    send_json(array(
        'code'=>$code,
        'reason'=>$reason,
        'result'=>$data
    ));
}

function app_send_400($reason=''){
    app_send('',400,$reason);
    exit();
}
//获取IP
function getIP(){
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }elseif(!empty($_SERVER["REMOTE_ADDR"])){
        $cip = $_SERVER["REMOTE_ADDR"];
    }else{
        $cip = "无法获取！";
    }
    return $cip;
}
