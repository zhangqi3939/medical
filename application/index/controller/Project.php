<?php
use think\Controller;
use think\Db;
use app\index\model\UserModel;
use app\rbac\model\RbacModel;
use app\index\model\ProjectModel;
class Project
{
    //项目保存
    public function project_save()
    {
        $data = input('post.');
        $Project = new ProjectModel();
        $result = $Project->project_save();
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
    //
}