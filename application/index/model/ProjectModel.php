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
}