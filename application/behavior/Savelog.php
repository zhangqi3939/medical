<?php
namespace app\behavior;
use think\Controller;
use think\Exception;
use think\Db;
use app\rbac\model\RbacModel;
use app\index\model\UserModel;
class Savelog extends Controller{

    public function run(&$params){
        $module     = request()->module();
        $controller = request()->controller();
        $url  = $this->getActionUrl();

        $Rbac =  new RbacModel();
        $User =  new UserModel();
        if($url != 'index/index/user_login'){
            $channel = 'web';
            $user_exit = $Rbac->checkToken($channel);
        }
        $token_exit = $Rbac->getTokenFromHttp();
        $token_exit = trim($token_exit,'"');
        $user_info = Db::name('rbac_token')->where('token',$token_exit)->find();
        if($url == 'index/index/user_login'){
            $remark = "用户登录";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == 'index/index/user_login_out'){
            $remark = "用户退出";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "index/equipment/equipment_save"){
            $remark = "设备保存";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "index/equipment/equipment_delete"){
            $remark = "设备删除";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "index/consumable/consumable_save"){
            $remark = "耗材保存";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "index/consumable/consumable_delete"){
            $remark = "耗材删除";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "index/consumable/consumable_mark"){
            $remark = "耗材标记";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "index/project/project_save"){
            $remark = "设备保存";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "index/project/project_delete"){
            $remark = "设备删除";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "rbac/rbac/user_save"){
            $remark = "用户保存";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "rbac/rbac/user_delete"){
            $remark = "用户删除";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "/rbac/rbac/role_save"){
            $remark = "角色保存";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }else if($url == "/rbac/rbac/role_delete"){
            $remark = "角色删除";
            $User->saveLog($user_info['user_id'],$url,$remark);
        }
    }
    private function getActionUrl()
    {
        $module     = request()->module();
        $controller = request()->controller();
        $action     = request()->action();
        $url        = $module.'/'.$controller.'/'.$action;
        return strtolower($url);
    }

}