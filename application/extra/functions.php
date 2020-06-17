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
}

/*function app_send($data='',$code='200',$reason=''){
    send_json(array(
        'code'=>$code,
        'reason'=>$reason,
        'result'=>$data
    ));
}*/
function app_send($data='',$code='200',$reason='',$logArr=[]){
    header('Content-Type: application/json');
    //默认返回
    $d=['code'=>$code,'reason'=>$reason,'result'=>$data];
    //传入参数
    $params = func_get_args();
    $paramsNum = count($params);
    //两个参数  $data  $logArr或者四个参数都传,保存log入住到request
    if($paramsNum == 2 || $paramsNum == 4){
        \think\Request::instance()->logArr = $params[$paramsNum-1];
        $d=['code'=>200,'reason'=>$reason,'result'=>$data];
    }
    //return json($d)->send();
    send_json($d);
}
/*function app_send_400($reason=''){
    app_send('',400,$reason);
    exit();
}*/
//返回400错误
function app_send_400($reason='',$logArr=[]){
    if(empty($logArr)){
        return app_send('',400,$reason);
    }else{
        return app_send('',400,$reason,$logArr);
    }
}
//返回401错误
function app_send_401($reason=''){
    return app_send('',400,$reason);
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
function encode($c, $prefix="&#") {
    $len = strlen($c);
    $a = 0;
    $scill = "";
    while ($a < $len) {
        $ud = 0;
        if (ord($c{$a}) >= 0 && ord($c{$a}) <= 127) {
            $ud = ord($c{$a});
            $a += 1;
        } else if (ord($c{$a}) >= 192 && ord($c{$a}) <= 223) {
            $ud = (ord($c{$a}) - 192) * 64 + (ord($c{$a + 1}) - 128);
            $a += 2;
        } else if (ord($c{$a}) >= 224 && ord($c{$a}) <= 239) {
            $ud = (ord($c{$a}) - 224) * 4096 + (ord($c{$a + 1}) - 128) * 64 + (ord($c{$a + 2}) - 128);
            $a += 3;
        } else if (ord($c{$a}) >= 240 && ord($c{$a}) <= 247) {
            $ud = (ord($c{$a}) - 240) * 262144 + (ord($c{$a + 1}) - 128) * 4096 + (ord($c{$a + 2}) - 128) * 64 + (ord($c{$a + 3}) - 128);
            $a += 4;
        } else if (ord($c{$a}) >= 248 && ord($c{$a}) <= 251) {
            $ud = (ord($c{$a}) - 248) * 16777216 + (ord($c{$a + 1}) - 128) * 262144 + (ord($c{$a + 2}) - 128) * 4096 + (ord($c{$a + 3}) - 128) * 64 + (ord($c{$a + 4}) - 128);
            $a += 5;
        } else if (ord($c{$a}) >= 252 && ord($c{$a}) <= 253) {
            $ud = (ord($c{$a}) - 252) * 1073741824 + (ord($c{$a + 1}) - 128) * 16777216 + (ord($c{$a + 2}) - 128) * 262144 + (ord($c{$a + 3}) - 128) * 4096 + (ord($c{$a + 4}) - 128) * 64 + (ord($c{$a + 5}) - 128);
            $a += 6;
        } else if (ord($c{$a}) >= 254 && ord($c{$a}) <= 255) { //error
            $ud = false;
        }
        $scill .= $prefix.$ud.";";
    }
    return $scill;
}
//将php整数封装成字符串(二进制,4 Byte)
function long2mem($v){
    return  chr($v & 255). chr(($v >> 8 ) & 255). chr(($v >> 16 ) & 255). chr(($v >> 24) & 255);
}
function short2mem($v){
    return  chr($v & 255). chr(($v >> 8 ) & 255);
}
function getActionUrl()
{
    $module     = request()->module();
    $controller = request()->controller();
    $action     = request()->action();
    $url        = $module.'/'.$controller.'/'.$action;
    return strtolower($url);
}
