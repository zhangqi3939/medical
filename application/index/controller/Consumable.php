<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use PHPExcel;
use app\index\model\ConsumableModel;
use app\rbac\model\RbacModel;
class Consumable extends Controller
{
    //耗材保存
    public function consumable_save()
    {
        $data = $_POST;
        $Consumable = new ConsumableModel();
        $result = $Consumable->consumable_save($data);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','保存失败');
        }
    }
    //耗材列表
    public function consumable_list()
    {
        $info = Db::name('consumable')
            ->alias('c')
            ->join('equipment e','e.box_id = c.box_id','left')
            ->join('project p','p.id = e.project_id','left')
            ->field('c.id,c.name,c.category,c.rfid,c.flag_use,c.box_id,c.status,c.sale_time,c.batch,c.remark,c.customer,c.add_by,c.add_by_name,e.name as equipment_name,p.name as project_name,p.province,p.city,p.address,p.lng,p.lat,p.charge_person,e.install_time')
            ->order('id asc')
            ->select();
        app_send($info);
    }
    //耗材详情
    public function consumable_details()
    {
        $id = input('post.id');
        $Consumable = new ConsumableModel();
        $info = $Consumable->consumable_details($id);
        app_send($info);
    }
    //耗材删除
    public function consumable_delete()
    {
        $id = $_POST;
        $Consumable = new ConsumableModel();
        $result = $Consumable->consumable_delete($id);
        if($result > 0){
            app_send();
        }else{
            app_send('','400','耗材删除失败');
        }
    }
    //耗材导入
    public function consumable_import()
    {
        $rbacModel = new RbacModel();
        $user_info = $rbacModel->checkToken('web');
        if(request()->isPost()){
           Loader::import('PHPExcel.PHPExcel');
           Loader::import('PHPExcel.PHPExcel.PHPExcel_IOFactory');
           Loader::import('PHPExcel.PHPExcel.PHPExcel_Cell');
           $objPHPExcel = new \PHPExcel();
           $file = request()->file('excel');
           if(empty($file)){
                app_send_400('请选择耗材文件');
                exit();
           }
           $res = 0;
           if($file){
               $file_types = explode(".", $_FILES ['excel'] ['name']); // ["name"] => string(25) "excel文件名.xls"
               $file_type = $file_types [count($file_types) - 1];//xls后缀
               $file_name = $file_types [count($file_types) - 2];//xls去后缀的文件名
               /*判别是不是.xls文件，判别是不是excel文件*/
               if (strtolower($file_type) != "xls" && strtolower($file_type) != "xlsx") {
                   echo '不是Excel文件，重新上传';
                   die;
               }
               $info = $file->move(ROOT_PATH . 'public' . DS . 'excel');//上传位置
               $path = ROOT_PATH . 'public' . DS . 'excel' . DS;
               $file_path = $path . $info->getSaveName();//上传后的EXCEL路径
               $consumable = new ConsumableModel();
               $re = $consumable->actionRead($file_path, 'utf-8');
               array_splice($re, 1, 0);
               unset($re[0]);
               $keys = array('name','category','rfid','batch','customer','sale_time','remark','add_by','add_by_name');
               $result = [];
               foreach ($re as $i => $vals) {
                    if(count(array_filter($vals)) > 0){
                        $value = [];
                        $value[0] = empty($vals[0]) ?  "" : $vals[0];
                        $value[1] = empty($vals[1]) ? 1 : $vals[1];
                        $value[2] = empty($vals[2]) ?  "" : $vals[2];
                        $value[3] =  empty($vals[3]) ? "" : $vals[3];
                        $value[4] = empty($vals[4]) ?  "" : $vals[4];
                        $value[5] = empty($vals[5]) ?  time() : strtotime($vals[5]);
                        $value[6] = empty($vals[6]) ?  "" : $vals[6];
                        $value[7] = $user_info['id'];
                        $value[8] = $user_info['user_name'];
                        $result[] = array_combine($keys, $value);
                    }
               }
               //遍历数组写入数据库
               $data = [];
               foreach ($result as $m=>$n) {
                   $consumable_exit = Db::name('consumable')->where('rfid',$n['rfid'])->find();
                   if(empty($consumable_exit)){
                        $data[] = $n;
                   }
               }
               if(!empty($data)){
                   $res = Db::name('consumable')->insertAll($data);
               }else{
                   app_send('','400','请仔细核对您的耗材..');
                   die;
               }
           }
           if($res > 0){
                app_send();
           }else{
               app_send('','400','耗材保存失败');
           }
       }else{
           app_send('','400','请仔细核对您的信息');
       }
    }
    //耗材统计
    public function consumable_statistics()
    {
        //项目  使用  剩下的   总共的
        $result = Db::name('consumable')
            ->alias('c')
            ->join('equipment e','e.box_id = c.box_id','left')
            ->join('project p','p.id = e.project_id','left')
            //->where('p.id != ""')
            ->field('c.id as consumable_id,e.box_id,e.install_time,c.flag_use,c.name as consumable_name,c.rfid,p.id as project_id,p.name as project_name,c.box_id,e.install_time')
            ->select();
        $total = count(Db::name('consumable')->select());
        $is_use = count(Db::name('consumable')->where('flag_use',1)->select());
        $not_use = $total - $is_use;
        $info['details'] = $result;
        $info['total'] = $total;
        $info['is_use'] = $is_use;
        $info['not_use'] = $not_use;
        app_send($info);
    }
    //耗材状态标记
    public function consumable_mark()
    {
        $data = input('post.');
        $consumable = new ConsumableModel();
        $result = $consumable->consumable_mark_save($data);
        if($result > 0){
            app_send();
        }else{
            app_send_400('耗材标记失败，请联系管理员');
        }
    }
}