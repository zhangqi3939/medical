<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\UserModel;
class Index
{
    public function index()
    {

    }
    /*
     * 用户登录
     * phoebus
     * 2020.02.11
     * */
    public function user_login()
    {
        $userName = input('post.userName');
        $userPassword = input('post.userPassword');
        $channel = input('post.channel');
        empty($channel) && $cchannel = 'web';
        $where = array( 'user_name'=>$userName, 'status'=>0);
        $User = new UserModel();
        $userExit = $User->userList($where);
        if(!empty($userExit) && count($userExit)>0){
            if($userExit['USER_PASSWORD']  == $userPassword){
                $token = $User->newToken($userExit["id"],$channel);
                if(empty($token)){
                    $userdata = array(
                        'user_id' => $userExit["id"],
                        'user_name' =>$userExit["user_name"],
                        'tel'=> $userExit['TEL'],
                        'token'=> $token
                    );
                    $User->saveLoginLog($userdata['user_id']);
                    app_send($userdata);
                }else{
                    app_send('','400','token error.');
                }
            }else{
                app_send('','400','password error1!');
                exit();
            }
        }else{
            app_send('','400','user error!');
            exit();
        }
    }
    //退出登录
    public function user_login_out()
    {
        $User =  new UserModel();
        $channel = 'web';
        $result = $User->deleteToken($channel);
        if($result>0){
            app_send();
        }else{
            app_send('','400','退出失败，请联系管理员');
        }
    }
}
