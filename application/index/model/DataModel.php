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
				->order('insert_time desc')
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
   /* function workTime($params,$category = 'status_cold'){
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
                ->select();
        $result = Db::name('data')->where($where)->select();
        foreach($result as $value) {
            $box_id[] = $value['box_id'];
        }
        $box_id = array_unique($box_id);
        $reserve4 = [];
        foreach($box_id as $value){
            $w = [];
            $w['insert_time'] = ['between',"{$params->startStamp},{$params->endStamp}"];
            $l = Db::name('data')->where('box_id',$value)->field('reserve4')->order('insert_time asc')->limit(1)->where($w)->find();
            $t = Db::name('data')->where('box_id',$value)->field('reserve4')->order('insert_time desc')->limit(1)->where($w)->find();
            $reserve4["$value"] = $t['reserve4'] - $l['reserve4'];
        }
        $res['reserve4'] = $reserve4;
        return $res;
    }*/

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
    public function workTime($params){
       /* if(empty($params['startStamp']) || empty($params['endStamp'])){
            $params['endStamp'] = time();
            $params['startStamp'] = $params['endStamp'] - 7 * 24 * 3600;
        }

        $where['insert_time'] = ['between',"{$params['startStamp']},{$params['endStamp']}"];*/
        $where = [];
        if(!empty($params['boxIds'])){
            $where['box_id'] = is_array($params['boxIds']) ? ['in',implode(',', $params['boxIds'])] : ['in', $params['boxIds']];
        }
        $result = Db::name('param')->where($where)->select();
        $array = [];
        foreach($result as $value){
            $box_id = $value['box_id'];
            $res =  Db::name('param')->field('config_t6,config_t5,config_t4,config_t3,reserve4')->where('box_id',$value['box_id'])->limit(1)->find();
            //一级泵
            $beng1 = $res['config_t5']*$res['reserve4'];
            //二级泵
            $beng2 = $res['config_t4']*$res['reserve4'];
            //搅拌泵
          /*  if($res['config_t6'] != 0){
                $jiao = (($res['config_t5'] / $res['config_t6'])*$res['config_t6'] + $res['config_t3'])*$res['reserve4'];*/
          	/*	var_dump($res['config_t3']);3
          		var_dump($res['config_t6']);1
          		var_dump($res['reserve4']);die;10
			$jiao = 3+30*1/1+60*/
                $jiao = number_format(($res['config_t3'] + 30 * ($res['config_t6'] / ($res['config_t6'] + 60)))*$res['reserve4'],1);
            /*}else{
                $jiao = 0;
            }*/
            $array["$box_id"] = [
                'beng1' => $beng1,
                'beng2' => $beng2,
                'jiao'  => $jiao,
                'ci' => $res['reserve4']
            ];
        }
        return $array;
    }
}