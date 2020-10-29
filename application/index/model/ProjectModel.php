<?php

namespace app\index\model;
use think\Model;
use think\Db;
class ProjectModel extends Model
{
    //项目保存
    public function project_save($params)
    {
        $id = empty($params['id']) ? "" : $params['id'];
        $data['name'] = empty($params['name']) ? "" : $params['name'];
        $data['remarks'] = empty($params['remarks']) ? "" : $params['remarks'];
        $data['province'] = empty($params['province']) ? "" : $params['province'];
        $data['city'] = empty($params['city']) ? "" : $params['city'];
        $data['address'] = empty($params['address']) ? "" : $params['address'];
        $data['lng'] = empty($params['lng']) ? "" : $params['lng'];
        $data['lat'] = empty($params['lat']) ? "" : $params['lat'];
        $data['charge_person'] = empty($params['charge_person']) ? "" : $params['charge_person'];
        $data['gender'] = empty($params['gender']) ? "" : $params['gender'];
        $data['tel'] = empty($params['tel']) ? "" : $params['tel'];
        $data['email'] = empty($params['email']) ? "" : $params['email'];
        if(empty($id)){
            $exit = Db::name('project')->where('name',$params['name'])->find();
            if(empty($exit)){
                $data['create_time'] = time();
                $result = Db::name('project')->insert($data);
            }else{
                app_send('', '400', '设备已存在,请仔细核对设备名称');
            }
        }else{
            $result = Db::name('project')->where('id',$id)->update($data);
        }
        if($result == 1 || $result == 0){
            return true;
        }
    }
    //项目详情
    public function project_details($id)
    {
        $details = Db::name('project')->where('id',$id)->find();
        return $details;
    }
    //项目删除
    public function project_delete($id)
    {
        $result = Db::name('project')->where('id',$id)->delete();
        return $result;
    }
    //项目->选择设备
    public function choose_equipment($params)
    {
        $id = $params['id'];
        $equipment_id = $params['equipment_id'];
        $equipment_id = explode( ',',$equipment_id);
        foreach($equipment_id as $row) {
            $result = Db::name('equipment')->where('id',$row)->update(array('pid'=>$id));
        }
        return $result;
    }
    //项目->选择管理者
    public function choose_manager($params)
    {
        $id = $params['id'];
        $uid = $params['uid'];
        $uid = explode( ',',$uid);
        foreach($uid as $row) {
            $result = Db::name('rbac_user')->where('id',$row)->update(array('pid'=>$id));
        }
        return $result;
    }
}