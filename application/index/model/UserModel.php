<?php

namespace app\index\model;
use think\Model;
use think\Db;
use app\rbac\model\RbacModel;
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
    //用户日志保存
    public function saveLog($uid,$url,$original_data="",$new_data="",$remarks){
        $d = array(
            'user_id'=>$uid,
            'remarks'=>$remarks,
            'original_data'=>$original_data,
            'new_data'=>$new_data,
            'user_ip'=>getIP(),
            'model'=>request()->module(),
            'controller'=>request()->controller(),
            'url'=>$url,
            'insert_time'=>time()
        );
        //$sql = Db::name('log')->fetchSql(true)->insert(array('user_id'=>$d['user_id'],'new_data'=>$d['new_data'],'orginal_data'=>$d['orginal_data'],'remarks'=>$d['remarks'],'user_ip'=>$d['user_ip'],'model'=>$d['model'],'controller'=>$d['controller'],'url'=>$d['url'],'insert_time'=>time()));
        $sql = Db::name('log')->insert($d);

    }
    //删除token，退出登录
    public function deleteToken($channel){
        $token = $this->getTokenFromHttp();
        $t_token = time().'';
        $result = DB::name('rbac_token')->where('channel',$channel)->update(array('token'=>$t_token));
        return $result;
    }
    //从请求中的到token
    function getTokenFromHttp(){
        $headers = getallheaders();
        return isset($headers['token']) ? $headers['token'] : '';
    }
    //修改密码
    public function user_password_change($params)
    {
        $rbac = new RbacModel();
        $old_password = $params['old_password'];
        $new_password = $params['new_password'];
        $channel = 'web';
        $user_info = $rbac->checkToken($channel);
        if(empty($user_info['id'])){
           app_send_400('用户登录信息失效,请重新登录');
        }
        $user = Db::name('rbac_user')->where('id',$user_info['id'])->find();
        if($old_password == $user['pass_word']){
            $result = Db::name('rbac_user')->where('id',$user_info['id'])->update(array('pass_word'=>$new_password));
            if($result > 0 ){
               return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
