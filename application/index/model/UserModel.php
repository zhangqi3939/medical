<?php

namespace app\index\model;
use think\Model;
use think\Db;
class UserModel extends Model
{
    //查询登录用户的信息
    public function userList($where)
    {
        $result = Db::name('rbac_user')->where($where)->find();
        return $result;
    }
    //生成新的token
    public function newToken($userID,$channel){
        //组织数据
        $d = array(
            'channel'=>$channel,
            'user_id'=>$userID,
            'add_time'=>time(),
            'user_ip'=>getIP(),
            'token'=>''
        );
        if($channel == 'web'){
            //生成新的token
            $d['token'] = md5($d['user_id'].$d['user_ip'].$d['channel']);
        }else if($channel == 'app'){
            //生成新的token
            $d['token'] = md5($d['user_id'].$d['user_ip'].$d['channel']);
        }

        //查询有没有记录
        $row = Db::name('rbac_token')->field('id')->where(array('user_id'=>$userID,'channel'=>$channel))->find();
        if(!empty($row)){
            //更新
            Db::name('rbac_token')->where(array('id'=>$row['id']))->update(array('channel'=>$channel,'user_id'=>$userID,'add_time'=>time(),'user_ip'=>$d['user_ip'],'token'=>$d['token']));
        }else{
            //新记录
            Db::name("rbac_token")->insert(array('channel'=>$channel,'user_id'=>$userID,'add_time'=>time(),'user_ip'=>$d['user_ip'],'token'=>$d['token']));
        }
        return $d['token'];
    }
    //用户登录日志
    public function saveLoginLog($uid,$remarks='用户登录'){
        $d = array(
            'user_id'=>$uid,
            'remarks'=>$remarks,
            'user_ip'=>getIP()
        );
        $sql = Db::name('log')->insert(array('user_id'=>$d['user_id'],'remarks'=>$d['remarks'],'user_ip'=>$d['user_ip'],'insert_time'=>time()));
    }
    //删除token，退出登录
    public function deleteToken($channel){
        $token = $this->getTokenFromHttp();
        $t_token = time().'';
        $result = DB::name('token')->where('channel',$channel)->update(array('token'=>$t_token));
        return $result;
    }
    //从请求中的到token
    function getTokenFromHttp(){
        $headers = getallheaders();
        return isset($headers['token']) ? $headers['token'] : '';
    }
}
