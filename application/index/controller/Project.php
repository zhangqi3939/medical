<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\UserModel;
use app\rbac\model\RbacModel;
use app\index\model\ProjectModel;
use think\Request;

class Project
{
    //项目保存
    public function project_save(Request $request)
    {

        $data = $_POST;
        $Project = new ProjectModel();
        $result = $Project->project_save($data);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','项目保存失败');
        }
    }
    //项目列表
    public function project_list()
    {
        $info = Db::name('project')->select();
        app_send($info);
    }
    //项目详情
    public function project_details()
    {
        $id = input('post.id');
        $Project = new ProjectModel();
        $info = $Project->project_details($id);
        app_send($info);
    }
    //项目删除
    public function project_delete()
    {
        $id = input('post.id');
        $Project = new ProjectModel();
        $result = $Project->project_delete($id);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','耗材删除失败');
        }
    }
    //项目->选择设备
    public function choose_equipment()
    {
        $data = input('post.');
        $Project = new ProjectModel();
        $result =  $Project->choose_equipment($data);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','项目设备保存失败');
        }
    }
    //项目->添加管理人员
    public function choose_manager()
    {
        $data = input('post.');
        $Project = new ProjectModel();
        $result =  $Project->choose_manager($data);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','项目管理者保存失败');
        }
    }
}