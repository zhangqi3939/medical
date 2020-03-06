<?php
namespace app\behavior;
use think\Controller;
use think\Exception;
use think\Db;
use app\rbac\model\RbacModel;
class Behavior extends Controller{
    // 定义需要排除的权限路由
    protected $exclude = [

    ];
    /**
     * 权限验证
     */
    public function run(&$params){
        // 行为逻辑
        try {
            // 获取当前访问路由;
            $url  = $this->getActionUrl();
            $Rbac =  new RbacModel();
            $token_exit = $Rbac->getTokenFromHttp();
            $token_exit = trim($token_exit,'"');
            $uid = Db::name('rbac_token')->where('token',$token_exit)->find();
            $user_info = Db::name('rbac_user')->where('id',$uid['user_id'])->find();
            if($url != 'index/index/user_login') {
                if (empty($user_info['id'])) {
                    app_send('', 401, '您的登录信息失效。');
                }
            }
            $role_id = Db::name('rbac_user_role')->field('role_id')->where('user_id',$uid['user_id'])->find();
            $role_id = explode(",",$role_id['role_id']);
            $right_id = Db::name('rbac_role_right')->field('right_id')->where('id','in',$role_id)->select();
            $data = array_column($right_id,"right_id");
            $mod = array();
            foreach($data as $key=>$value){
                if(strpos($value,',') !== false){
                    $array = explode(',',$value);
                    $array = array_filter($array);
                    $mod = array_merge($array,$mod);
                }
            }
            $mod = array_unique($mod);
            $data  = array(
                "right_id"=>$mod
            );
            // 用户所拥有的权限路由
            $auth = Db::name('rbac_right')->field('url')->where('id','in',$data['right_id'])->select();
            $auth = array_column($auth, 'url');
            if(!in_array($url, $auth)) {
                app_send('','400','您没有操作权限，请联系管理员');
            }
        }catch (Exception $ex) {
            //print_r($ex);
        }
    }
    /**
     * 获取当前访问路由
     * @param $Request
     * @return string
     */
    private function getActionUrl()
    {
        $module     = request()->module();
        $controller = request()->controller();
        $action     = request()->action();
        $url        = $module.'/'.$controller.'/'.$action;
        return strtolower($url);
    }
}