<?php
namespace app\index\model;
use think\Model;
use think\Db;
class ProjectModel extends Model
{
    //项目保存
    public function project_save($params)
    {
        $id = $params['id'];
        $data['project_name'] = $params['project_name'];
        $data['describe'] = $params['describe'];
        if(empty($id)){
            $exit = Db::name('project')->where('project_name',$params['project_name'])->find();
            if(empty($exit)){
                $result = Db::name('project')->insert($data);
            }else{
                app_send('', '400', '设备已存在,请仔细核对设备名称');
            }
        }else{
            $result = Db::name('project ')->where('id',$id)->update($data);
        }
        return $result;
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