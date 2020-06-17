<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\UserModel;
use app\rbac\model\RbacModel;
class Index
{
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
        empty($channel) && $channel = 'web';
        $where = array( 'user_name'=>$userName, 'status'=>0);
        $User = new UserModel();
        $userExit = $User->userList($where);
        if(!empty($userExit) && count($userExit)>0){
            if($userExit['pass_word']  == $userPassword){
                $token = $User->newToken($userExit["id"],$channel);
                if(!empty($token)){
                    $userdata = array(
                        'user_id' => $userExit["id"],
                        'user_name' =>$userExit["user_name"],
                        'tel'=> $userExit['tel'],
                        'token'=> $token
                    );
                    Db::name('log')->insert(array('user_id'=>$userExit["id"],'item_id'=>json_encode(array('user_id'=>$userExit["id"])),'user_ip'=>getIP(),'remarks'=>'用户登录','model'=>request()->module(),'controller'=>request()->controller(),'url'=>getActionUrl(),'insert_time'=>time()));
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
        $token = $User->getTokenFromHttp();
        $userExit = Db::name('rbac_token')->where('token',$token)->find();
        $result = $User->deleteToken($channel);
        if($result>0){
            Db::name('log')->insert(array('user_id'=>$userExit["user_id"],'item_id'=>json_encode(array('user_id'=>$userExit["id"])),'user_ip'=>getIP(),'remarks'=>'用户退出','model'=>request()->module(),'controller'=>request()->controller(),'url'=>getActionUrl(),'insert_time'=>time()));
            app_send();
        }else{
            app_send('','400','退出失败，请联系管理员');
        }
    }
    //获取用户信息
    public function user_info()
    {
        $Rbac =  new RbacModel();
        $channel = 'web';
        $user_info = $Rbac->checkToken($channel);
        app_send($user_info);
    }
    //修改密码
    public function user_password_change()
    {
        $data = input('post.');
        $User =  new UserModel();
        $token = $User->getTokenFromHttp();
        $userExit = Db::name('rbac_token')->where('token',$token)->find();
        $result = $User->user_password_change($data);
        $logArr = ['itemID'=>json_encode(array('user_id'=>$userExit['user_id'])),'from'=>json_encode(array('pass_word'=>$data['old_password'])),'to'=>json_encode(array('pass_word'=>$data['new_password']))];
        if($result == true){
            app_send('',$logArr);
        }else{
            app_send('','400','源密码错误');
        }
    }
}
