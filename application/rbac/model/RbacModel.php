<?php
namespace app\rbac\model;
use think\Model;
use think\Db;
class RbacModel extends Model
{
    //从请求中的到token
    function getTokenFromHttp(){
        $headers = getallheaders();
        return isset($headers['Token']) ? $headers['Token'] : '';
    }
    //token检查
    public function checkToken($channel){
        $token = $this->getTokenFromHttp();
        $token = trim($token,'"');
        if(empty($token)){
            app_send('',401,'您的登录信息为空。');
            die();
        }
        //检查token信息，并更新
        $tokenInfo = $this->getTokenInfo($token,$channel);
        if(empty($tokenInfo)){
            app_send('',401,'您的登录信息失效。');
            exit();
        }
        if($tokenInfo['add_time'] < time() - 7200 && $channel != 'app'){
            app_send('',401,'您超过两小时未动作，请重新登录。');
            exit();
        }
        //如果超过5分钟，更新token时间
        if($tokenInfo['add_time'] < time() - 300){
            Db::name('rbac_token')->where('id',$tokenInfo['token_id'])->update(array('add_time'=>time()));
        }
        //返回用户及token信息
        return $tokenInfo;
    }
    //查询token和用户信息
    public function getTokenInfo($token,$channel){
        $sql = Db::name('rbac_token')->alias('T')
            ->join('rbac_user U','T.user_id = U.id','left')
            ->join('rbac_user_role R','R.user_id =  U.id','left')
            ->field('U.id,R.role_id,U.user_name,U.tel,U.gender,T.token,T.id AS token_id,T.add_time')
            ->where('T.channel',$channel)
            ->where('T.token',$token)
            ->find();
        return $sql;
    }
    //用户保存
    public function user_save($params)
    {
        $id = $params['id'];
        $formData['user_name'] = input('post.user_name');
        $password = $params['user_password'];
        $formData['real_name'] = $params['real_name'];
        $formData['tel'] = $params['tel'];
        $formData['email'] = $params['email'];
        $formData['gender'] = $params['gender'];
        $role_ids = $params['role_id'];
        if(!empty($password)){
            $formData['user_password'] = md5($password);
        }else{
            $formData['user_password'] = md5(123456);
        }
        if(empty($id)){
            $res = Db::name('rbac_user')->insertGetId($formData);
            if($res > 0){
                $result = Db::name('rbac_user_role')->insert(array('user_id'=>$res,'role_id'=>$role_ids));
            }
        }else{
            if(empty($password)) unset($formData['user_password']);
            $res = Db::name('rbac_user')->where('id',$id)->update($formData);
            if($res > 0) {
                $result = Db::name('rbac_user_role')->where('user_id', $id)->update(array('user_id' => $id, 'role_id' => $role_ids));
            }
        }
        return $result;
    }
    public function user_delete($id)
    {
        $channel = 'web';
        $user_info = $this->checkToken($channel);
        $role_id = $user_info['ROLE_ID'];
        if($id>0){
            $res = Db::name('rbac_user')->where('id',$id)->delete();
            $result = Db::name('rbac_user_role')->where('user_id',$id)->delete();
        }else{
            $result = -1;
        }
        return $result;
    }
    public function user_details($params)
    {
        $info = Db::name('rbac_user')
            ->alias('U')
            ->join('rbac_user_role R','U.id=R.user_id','left')
            ->where('U.id',$params)
            ->find();
        //$info['role_id'] = explode(",",$info['role_id']);
        return $info;
    }
    public function role_save($params)
    {
        $data = array(
            'role_name' => $params['role_name'],
            'describe'  => $params['describe']
        );
        $role_id = $data['role_id'];
        if(empty($role_id)){
            $role_exit = Db::name('rbac_role')->where('role_name',$data['role_name'])->select();
            if(!$role_exit){
                $result = Db::name('rbac_role')->insertGetId($data);
                if($result){
                    $res = Db::name('rbac_role_right')->insert(array(
                            'role_id'  => $result,
                            'right_id' => $params['right_id']
                        )
                    );
                }
            }else{
                app_send('','400','该角色名称已存在');
            }
        }else{
            $result = Db::name('rbac_role')->where('id',$role_id)->update($data);
            if($result){
                $res = Db::name('rbac_role_right')->where('role_id',$role_id)->update(array(
                        'role_id'  => $role_id,
                        'right_id' => $params['right_id']
                    )
                );
            }
        }
        return $res;
    }
    public function role_delete($id)
    {
        if (!empty($id)) {
            $result = Db::name('rbac_role_right')->where('role_id', $id)->delete();
            if ($result > 0) {
                $res = Db::name('rbac_role')->where('id', $id)->delete();
            } else {
                app_send('', '400', '角色用户表删除失败');
            }
        } else {
            app_send('', '400', '请选择您要删除的角色');
        }
        return $res;
    }
    public function role_details($role_id)
    {
        $role_info = Db::name('rbac_role')->where('id',$role_id)->find();
        return $role_info;
    }
}