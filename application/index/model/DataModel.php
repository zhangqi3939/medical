<?php
namespace app\index\model;
use think\Model;
use think\Db;
class DataModel extends Model
{
    //设备最新数据
    /*
        参数对象$params:
        {
            'startTime'=>'',//开始时间
            'endTime'=>'',//结束时间
            'equip'=>array()//设备id数组
        }
    */
    function dataLatest($params = false){
        $map=[];
        if(!empty($params)){//处理查询参数

        }
        empty($map) && $map=array('1'=>1);
        $select = 'e.id,e.name,p.name as project_name,p.province,p.city,p.address,p.lng,p.lat,p.charge_person,p.tel,d.box_id,d.insert_time,d.status,d.rfid,d.status_cold,d.alarm_cold,d.switch_o2,d.pt1,d.pt2,d.pv,d.status_p4_jiaoban,d.status_p4_beng1,d.status_p4_beng2,d.status_p6_beng,d.reserve1,d.reserve2,d.reserve3,d.reserve4,d.reserve5,d.reserve6';
        $res = Db::name('data_latest')
            ->alias('d')
            ->join('equipment e','d.box_id = e.box_id','left')
            ->join('project p','e.project_id = p.id','left')
            ->order('d.insert_time desc')
            ->field($select)->select();
        return $res;
    }
    
    /*//历史数据
        params:
        {startTime,endTime,box_id}
    */
    function dataList($params = false){
        if(empty($params->startStamp) || empty($params->endStamp)){
            $params->endStamp = time();
            $params->startStamp = $params->endStamp - 7 * 24 * 3600;
        }
        $select='id,box_id,insert_time,status,rfid,status_cold,alarm_cold,switch_o2,pt1,pt2,pv,status_p4_jiaoban,status_p4_beng1,status_p4_beng2,status_p6_beng,reserve1,reserve2,reserve3,reserve4,reserve5,reserve6';
        $res = Db::name('data')
                ->where('box_id',$params->boxId)
                ->where('insert_time','between',"{$params->startStamp},{$params->endStamp}")
                ->field($select)
                //->fetchSql(true)
                ->select();

        return $res;
    }

    /*//时长统计
        参数对象$params:
        {
            'startTime'=>'',//开始时间
            'endTime'=>'',//结束时间
            'boxIds'=>array()//设备id数组
        }
        统计类型$category，默认制冷机
        时间区间$timeStrip,数据上传间隔，默认30秒
    */
    function workTime($params,$category = 'status_cold'){
        if(empty($params->startStamp) || empty($params->endStamp)){
            $params->endStamp = time();
            $params->startStamp = $params->endStamp - 7 * 24 * 3600;
        }
        $where = [];
        $where['insert_time'] = ['between',"{$params->startStamp},{$params->endStamp}"];
        if(!empty($params->boxIds)){
            $where['box_id'] = is_array($params->boxIds) ? ['in',implode(',', $params->boxIds)] : ['in', $params->boxIds];
        }
        $select = "box_id,count(1) as total_num";
        $res = Db::name('data')
                ->field($select)
                ->where("{$category}",1)
                ->where($where)
                ->group('box_id')
                //->fetchSql(true)
                ->select();
        return $res;
    }
}