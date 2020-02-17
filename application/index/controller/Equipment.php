<?php
namespace app\Index\controller;
use think\Controller;
use think\Db;
use app\index\model\UserModel;
use app\rbac\model\RbacModel;
use app\index\model\EquipmentModel;
class Equipment
{
    public function equipmemnt_save()
    {
        $data = input('post.');
        $Equipment = new EquipmentModel();
        $result = $Equipment->Equipmemnt_save($data);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','设备保存失败');
        }
    }
    //设备列表
    public function equipment_list()
    {
        $info = Db::name('equipment')->select();
        app_send($info);
    }
    //设备详情
    public function equipment_details()
    {
        $id = input('post.id');
        $Equipment = new EquipmentModel();
        $info = $Equipment->equipment_details($id);
        app_send($info);
    }
    //设备删除
    public function equipment_delete()
    {
        $id = input('post.id');
        $Equipment = new EquipmentModel();
        $result = $Equipment->equipment_delete($id);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','设备删除失败');
        }
    }
}