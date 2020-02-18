<?php
namespace app\index\model;
use think\Model;
use think\Db;
class ConsumableModel extends Model
{
    //耗材保存
    public function consumable_save($params)
    {
        $id = $params['id'];
        $data['consumable_name'] = $params['consumable_name'];
        $data['rfid'] = $params['rfid'];
        if(empty($id)){
            $exit = Db::name('consumable')->where('consumable_name',$params['consumable_name'])->find();
            if(empty($exit)){
                $result = Db::name('consumable')->insert($data);
            }else{
                app_send('', '400', '设备已存在,请仔细核对设备名称');
            }
        }else{
            $result = Db::name('consumable')->where('id',$id)->update($data);
        }
        return $result;
    }
    //耗材详情
    public function consumable_details($id)
    {
        $details = Db::name('consumable')->where('id',$id)->find();
        return $details;
    }
    //耗材删除
    public function consumable_delete($id)
    {
        $result = Db::name('consumable')->where('id',$id)->delete();
        return $result;
    }
}