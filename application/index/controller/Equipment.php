<?php
namespace app\Index\controller;
use think\Controller;
use think\Db;
use app\index\model\UserModel;
use app\rbac\model\RbacModel;
use app\index\model\EquipmentModel;
use app\index\model\DataModel;
class Equipment
{
    //设备保存
    public function equipment_save()
    {
        $data = input('post.');
        $Equipment = new EquipmentModel();
        $result = $Equipment->equipment_save($data);
        $logArr = ['itemID'=>json_encode(array('box_id'=>$data['box_id'])),'from'=>json_encode(array('name'=>$data['name'],'box_id'=>$data['box_id'],'project_id'=>$data['project_id'],'remarks'=>$data['remarks'])),'to'=>json_encode(array('name'=>$data['name'],'box_id'=>$data['box_id'],'project_id'=>$data['project_id'],'remarks'=>$data['remarks']))];
        if($result > 0){
            app_send('',$logArr);
        }else{
            app_send('','400','设备保存失败');
        }
    }
    //设备列表
    public function equipment_list()
    {
        $info = Db::name('equipment')
            ->alias('e')
            ->join('project p','e.project_id = p.id','left')
            ->field('e.id,e.type,e.name as equipment_name,e.box_id,e.project_id,p.name as project_name,e.install_time,e.install_user,e.remarks')
            ->select();
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
        $data = Db::name('equipment')->where('id',$id)->find();
        $Equipment = new EquipmentModel();
        $result = $Equipment->equipment_delete($id);
        $logArr = ['itemID'=>json_encode(array('box_id'=>$data['box_id'])),'from'=>'','to'=>json_encode(array('result'=>'设备已删除'))];
        if($result > 0){
            app_send('',$logArr);
        }else{
            app_send('','400','设备删除失败');
        }
    }
    //设备数据
        //设备最新数据
        function data_latest(){
            $params = new \STDClass;
            $dm = new DataModel();
            $res = $dm->dataLatest();
            app_send($res);
        }
        //设备历史数据
        function data_list(){
            //参数
            $formData = input('post.');
            if(empty($formData['boxId'])){
                app_send_400('box error!');
                die();
            }
            if(empty($formData['startTime']) || empty($formData['endTime'])){
                $endStamp = time();
                $startStamp = $endStamp - 7*24*3600;
            }else{
                $startStamp = strtotime($formData['startTime']);
                $endStamp = strtotime($formData['endTime']);
            }
            $dm = new DataModel();

            $params = new \STDClass;
            $params->boxId = $formData['boxId'];
            $params->startStamp = $startStamp;
            $params->endStamp = $endStamp;

            $res = $dm->dataList($params);

            app_send($res);
        }
    //设备统计
        //设备总览
        function overview(){
            //
            $em = new EquipmentModel();

            $res = $em->overview();

            app_send($res);
        }
        //设备运行时长
        function work_time(){
            $formData = input('post.');
            //起止时间
            if(empty($formData['startTime']) || empty($formData['endTime'])){
                $endStamp = time();
                $startStamp = $endStamp - 7*24*3600;
            }else{
                $startStamp = strtotime($formData['startTime']);
                $endStamp = strtotime($formData['endTime']);
            }
            //设备列表
            $boxIds = empty($formData['boxIds']) ? array() : $formData['boxIds'];
            $params = new \STDClass;
            $params->boxIds = $boxIds;
            $params->startStamp = $startStamp;
            $params->endStamp = $endStamp;
            //统计类型
            $params->category = ['status_cold','status_p4_jiaoban','status_p4_beng1','status_p4_beng2','status_p6_beng'];
            $dm = new DataModel();
            $res = [];
            $res['timeStrip'] = 30;
            $res['status_cold'] = $dm->workTime($params,'status_cold');
            $res['status_p4_jiaoban'] = $dm->workTime($params,'status_p4_jiaoban');
            $res['status_p4_beng1'] = $dm->workTime($params,'status_p4_beng1');
            $res['status_p4_beng2'] = $dm->workTime($params,'status_p4_beng2');
            $res['status_p6_beng'] = $dm->workTime($params,'status_p6_beng');
            app_send($res);
        }
        //报警信息
        public function alarm_info()
        {
            $alarm = Db::name('data_latest')
                ->alias('d')
                ->field('d.box_id,d.alarm_cold,e.name')
                ->join('equipment e','e.box_id = d.box_id','left')
                ->where('d.alarm_cold',1)
                ->select();
            app_send($alarm);
        }
        //配置超级密码
        public function set_super_secret()
        {
            $data = input('post.');
            if(empty($data['box_id'])){
                app_send_400('请选择您要配置超级密码的设备');
            }
            $old_super_secret = Db::name('param')->where('box_id',$data['box_id'])->field('super_secret')->find();
            empty($old_super_secret) && $old_super_secret = [];
            empty($data['super_secret']) && $data['super_secret'] = '';
            empty($data['super_secret_total_cnts']) && $data['super_secret_total_cnts'] = '';
            $Equipment = new EquipmentModel();
            $result = $Equipment->set_super_secret($data);
            $logArr = ['itemID'=>json_encode(array('box_id'=>$data['box_id'])),'from'=>json_encode($old_super_secret),'to'=>json_encode(array('super_secret'=>$data['super_secret']))];
            if($result == true){
                app_send('',$logArr);
            }else{
                app_send_400('超级密码配置失败');
            }
        }
        //超级密码配置信息详情
        public function param_info()
        {
            $box_id = input('post.box_id');
            if(empty($box_id)){
                app_send_400('请选择您要配置超级密码的设备');
            }
            $param = Db::name('param')->where('box_id',$box_id)->field('box_id,super_secret,super_secret_use_cnts,super_secret_total_cnts')->find();
            if(!empty($param)){
                app_send($param);
            }
        }
}