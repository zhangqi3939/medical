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
        $data['name'] = empty($params['name'])  ? "" : $params['name'];
        $data['project_id'] = empty($params['project_id']) ? "" : $params['project_id'];
        $data['box_id'] = empty($params['box_id']) ? "" : $params['box_id'];
        $data['install_user'] = empty($params['install_user']) ? "" : $params['install_user'];
        $data['type'] = empty($params['type']) ? "" : $params['type'];
        $data['remarks'] = empty($params['remarks']) ? "" : $params['remarks'];
        //$data['equipment_name'] = empty($params['equipment_name']) ? "" :$params['equipment_name'];
        $data['install_time'] = time();
        if(empty($id)){
            $exit_box_id = Db::name('equipment')->where('box_id',$data['box_id'])->find();
            $exit_box_name = Db::name('equipment')->where('name',$data['name'])->find();
            if(empty($exit_box_id) && empty($exit_box_name)){
                $result = Db::name('equipment')->insert($data);
                $box_id = $params['box_id'];
                $time = time();
                if($result >0 ){
                    $sql = "INSERT INTO md_data_latest (box_id,insert_time) VALUES ('".$box_id."' , $time )";
                    $res = Db::execute($sql);
                }
            }else{
                app_send('', '400', '设备已存在,请仔细核对设备名称');
            }
        }else{
            $result = Db::name('equipment')->where('id',$id)->update($data);
        }
        if($result === 1 || $result === 0){
            return true;
        }
    }
    //设备详情
    public function equipment_details($id)
    {
        $select = "e.*,p.name as project_name,p.province,p.city,p.address,p.charge_person,p.tel";
        $details = Db::name('equipment')
                    ->alias('e')
                    ->join('project p','e.project_id = p.id','left')
                    ->field($select)
                    ->where('e.id',$id)->find();
        return $details;
    }
    //设备删除
    public function equipment_delete($id)
    {
        $info = Db::name('equipment')->where('id',$id)->find();
        Db::name('data_latest')->where('box_id',$info['box_id'])->delete();
        $result = Db::name('equipment')->where('id',$id)->delete();
        return $result;
    }
    //设备统计
    function overview(){
        $res = [];
        //总数，实施数量
            $select = "count(if(project_id>0,true,null)) as installed_num,count(1) as total_num";
            $numRes = Db::name('equipment')->field($select)->find();
            $res['install']=$numRes;
        //按地市统计
            $select = "count(1) as total_num,province,city";
            $numRes = Db::name('equipment')
                    ->alias('e')
                    ->join('project p','e.project_id = p.id','left')
                    ->field($select)
                    ->group('p.province,p.city')
                    //->fetchSql(true)
                    ->select();
            $res['city']=$numRes;
        return $res;
    }
}