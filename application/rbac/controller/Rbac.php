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
        $user = Db::name('rbac_user')
            ->alias('u')
            ->join('rbac_user_role r','u.id = r.user_id','left')
            ->field('u.id,u.user_name,u.real_name,u.tel,u.email,u.pass_word,u.gender,r.role_id')
            ->select();
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
    //用户详情
    public function user_details()
    {
        $user_id = input('post.id');
        $rbac = new RbacModel();
        $info = $rbac->user_details($user_id);
        app_send($info);
    }
    //角色保存
    public  function role_save()
    {
        $data = input('post.');
        $rbac = new RbacModel();
        $result = $rbac->role_save($data);
        app_send($result);
    }
    //角色列表
    public function role_list()
    {
        $role = Db::name('rbac_role')
            ->alias('r')
            ->join('rbac_role_right l','r.id = l.role_id','left')
            ->field('r.id,r.role_name,r.remarks,l.right_id')
            ->select();
        app_send($role);
    }
    //角色删除
    public function role_delete()
    {
        $id = input('post.role_id');
        $rbac = new RbacModel();
        $result = $rbac->role_delete($id);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','角色选择错误。');
        }
    }
    //角色详情
    public function role_details()
    {
        $role_id = input('post.role_id');
        $rbac = new RbacModel();
        $info = $rbac->role_details($role_id);
        app_send($info);
    }
    //权限列表
    public function right_list()
    {
        $details = Db::name('rbac_right')->select();
        app_send($details);
    }
}
