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
                $startStamp = strtotime($startTime);
                $endStamp = strtotime($endTime);
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
                $startStamp = strtotime($startTime);
                $endStamp = strtotime($endTime);
            }

            //设备列表
            $boxIds = empty($formData['boxIds[]']) ? array() : $formData['boxIds[]'];

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
}