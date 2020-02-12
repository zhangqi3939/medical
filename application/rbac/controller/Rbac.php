<?php
namespace app\rbac\controller;
use think\Controller;
use think\Db;
use think\Debug;
use app\rbac\model\RbacModel;
class Rbac extends Controller
{
    //用户保存
    public function user_save()
    {
        $data = input('post.');
        $rbac = new RbacModel();
        $result = $rbac->user_save($data);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','用户保存失败');
        }
    }
    //用户列表
    public function user_list()
    {
        $user = Db::name('rbac_user')->select();
        app_send($user);
    }
    //用户删除
    public function user_delete()
    {
        $id = input('post.id');
        $rbac = new RbacModel();
        $result = $rbac->user_delete($id);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','用户选择错误。');
        }

    }
}
