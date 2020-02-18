<?php
use think\Controller;
use think\Db;
use app\index\model\UserModel;
use app\rbac\model\RbacModel;
use app\index\model\ConsumableModel;
class Consumable
{
    //耗材保存
    public function consumable_save()
    {
        $data = input('post.');
        $Consumable = new ConsumableModel();
        $result = $Consumable->consumable_save($data);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','保存失败');
        }
    }
    //耗材列表
    public function consumable_list()
    {
        $info = Db::name('consumable')->select();
        app_send($info);
    }
    //耗材详情
    public function consumable_details()
    {
        $id = input('post.id');
        $Consumable = new ConsumableModel();
        $info = $Consumable->consumable_details($id);
        app_send($info);
    }
    //耗材删除
    public function consumable_delete()
    {
        $id = input('post.id');
        $Consumable = new ConsumableModel();
        $result = $Consumable->consumable_delete($id);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','耗材删除失败');
        }
    }
}