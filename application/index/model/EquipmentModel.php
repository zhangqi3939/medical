<?php

namespace app\index\model;
use app\rbac\model\RbacModel;
use think\Model;
use think\Db;
class EquipmentModel extends Model
{
    //设备保存
    public function equipment_save($params)
    {
        $rbacModel = new RbacModel();
        $user_info = $rbacModel->checkToken('web');
        $id = empty($params['id'])  ? "" : $params['id'];
        $data['name'] = empty($params['name'])  ? "" : $params['name'];
        $data['project_id'] = empty($params['project_id']) ? "" : $params['project_id'];
        $data['box_id'] = empty($params['box_id']) ? "" : $params['box_id'];
        $data['install_user'] = empty($params['install_user']) ? "" : $params['install_user'];
        $data['type'] = empty($params['type']) ? "" : $params['type'];
        $data['remarks'] = empty($params['remarks']) ? "" : $params['remarks'];
        $data['iccid'] = empty($params['iccid']) ? "" : $params['iccid'];
        $data['sim'] = empty($params['sim']) ? "" : $params['sim'];
        //$data['equipment_name'] = empty($params['equipment_name']) ? "" :$params['equipment_name'];
        $data['install_time'] = time();
        if(empty($id)){
            $data['add_by'] = empty($user_info['id']) ? "" : $user_info['id'];
            $data['add_by_name'] = empty($user_info['user_name']) ? "" : $user_info['user_name'];
            $exit_box_id = Db::name('equipment')->where('box_id',$data['box_id'])->find();
            $exit_box_name = Db::name('equipment')->where('name',$data['name'])->find();
            if(empty($exit_box_id) && empty($exit_box_name)){
                $result = Db::name('equipment')->insert($data);
                $box_id = $params['box_id'];
                $time = time();
                if($result >0 ){
                    $param = Db::name('param')->insert(array('box_id'=>$params['box_id'],'insert_time'=>$time));
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
        $select = "e.*,p.name as project_name,p.province,p.city,p.address,p.charge_person,p.tel,m.config_t1 as param_config_t1,m.config_t2 as param_config_t2,m.config_t3 as param_config_t3,m.config_t4 as param_config_t4,m.config_t5 as param_config_t5,m.config_t6 as param_config_t6,m.config_t7 as param_config_t7,m.ST1 as param_ST1,m.ST2 as param_ST2,m.SV as param_SV,m.reserve1 as param_reserve1,m.reserve2 as param_reserve2,m.reserve3 as param_reserve3,m.reserve4 as param_reserve4,m.reserve5 as param_reserve5,m.reserve6 as param_reserve6,m.reserve7 as param_reserve7,m.reserve8 as param_reserve8,m.reserve9 as param_reserve9,m.reserve10 as param_reserve10,m.reserve11 as param_reserve11";
        $details = Db::name('equipment')
                    ->alias('e')
                    ->join('project p','e.project_id = p.id','left')
                    ->join('param m','m.box_id = e.box_id','left')
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
    //超级密码保存
    public function set_super_secret($params)
    {
        $result = Db::name('param')->where('box_id',$params['box_id'])->update(array('super_secret'=>$params['super_secret'],'super_secret_total_cnts'=>$params['super_secret_total_cnts']));
        if($result == 1 || $result == 0 ){
            return true;
        }
    }
}