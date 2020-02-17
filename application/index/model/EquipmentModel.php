<?php

namespace app\index\model;
use think\Model;
use think\Db;
class EquipmentModel extends Model
{
    //设备保存
    public function equipment_save($params)
    {
        $id = $params['id'];
        $data['pid'] = $params['pid'];
        $data['equipment_name'] = $params['equipment_name'];
        $data['number'] = $params['number'];
        $data['type'] = $params['type'];
        if(empty($id)){
            $exit = Db::name('equipment')->where('name',$params['equipment_name'])->find();
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