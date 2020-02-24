<?php

namespace app\index\model;
use think\Model;
use think\Db;
class EquipmentModel extends Model
{
    //设备保存
    public function equipment_save($params)
    {
        $id = empty($params['id'])  ? "" : $params['id'];
        $data['project_id'] = empty($params['project_id']) ? "" : $params['project_id'];
        $data['box_id'] = empty($params['box_id']) ? "" : $params['box_id'];
        $data['install_user'] = empty($params['install_user']) ? "" : $params['install_user'];
        $data['type'] = empty($params['type']) ? "" : $params['type'];
        //$data['equipment_name'] = empty($params['equipment_name']) ? "" :$params['equipment_name'];
        $data['install_time'] = time();
        if(empty($id)){
            $exit = Db::name('equipment')->where('box_id',$params['box_id'])->find();
            if(empty($exit)){
                $result = Db::name('equipment')->insert($data);
            }else{
                app_send('', '400', '设备已存在,请仔细核对设备名称');
            }
        }else{
            $result = Db::name('equipment')->where('id',$id)->update($data);
        }
        return $result;
    }
    //设备详情
    public function equipment_details($id)
    {
        $details = Db::name('equipment')->where('id',$id)->find();
        return $details;
    }
    //设备删除
    public function equipment_delete($id)
    {
        $result = Db::name('equipment')->where('id',$id)->delete();
        return $result;
    }
}