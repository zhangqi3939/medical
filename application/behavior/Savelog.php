<?php
namespace app\behavior;
use think\Controller;
use think\Exception;
use think\Db;
use app\rbac\model\RbacModel;
use app\index\model\UserModel;
use app\index\model\EquipmentModel;
class Savelog extends Controller{

    // 定义需要排除的权限路由
    protected $exclude = [
        //'index/index/user_login',//登录
        //'index/index/user_login_out',//退出
        'index/index/user_password_change',//修改密码
        'index/equipment/equipment_save',//设备保存
        'index/equipment/equipment_delete',//设备删除
        'index/equipment/set_super_secret',//设置超级密码
        'index/consumable/consumable_save',//耗材保存
        'index/consumable/consumable_delete',//耗材删除
        'index/consumable/consumable_mark',//耗材标记
        'index/project/project_save',//项目保存
        'index/project/project_delete',//项目删除
        'rbac/rbac/user_save',//用户保存
        'rbac/rbac/user_delete',//用户删除
        'rbac/rbac/role_save',//角色保存
        'rbac/rbac/role_delete'//角色删除
    ];
    public function run(&$params){
        $url = $this->getActionUrl();
        if(in_array($url,$this->exclude)){
            $data = request()->logArr;
            $headers = getallheaders();
            if(!empty($headers['token'])){
                $token = $headers['token'];
                $user_info = Db::name('rbac_token')->where('token',$token)->find();
                if(empty($user_info)){
                    app_send_400('您的登录信息为空，请重新登录');
                }
            }
            $d = array(
                'user_id'=>$user_info['user_id'],
                'item_id'=>$data['itemID'],
                'original_data'=>$data['from'],
                'new_data'=>$data['to'],
                'user_ip'=>getIP(),
                'model'=>request()->module(),
                'controller'=>request()->controller(),
                'url'=>$url,
                'insert_time'=>time()
            );
            Db::name('log')->insert($d);
        }else{
            return true;
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