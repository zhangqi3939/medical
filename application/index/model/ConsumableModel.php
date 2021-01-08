<?php
namespace app\index\model;
use think\Model;
use think\Db;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use app\rbac\model\RbacModel;
class ConsumableModel extends Model
{
    //耗材保存
        public function  consumable_save($params)
    {
        $rbacModel = new RbacModel();
        $user_info = $rbacModel->checkToken('web');
        $id = empty($params['id']) ? "" : $params['id'];
        $data['category'] = empty($params['category']) ? "" : $params['category'];
        $data['flag_use'] = empty($params['flag_use']) ? "" : $params['flag_use'];
        $data['name'] = empty($params['name']) ? "" : $params['name'];
        $data['rfid'] = empty($params['rfid']) ? "" : $params['rfid'];
        $data['batch'] = empty($params['batch']) ? "" : $params['batch'];
        $data['customer'] = empty($params['customer']) ? "" : $params['customer'];
        $data['sale_time'] = empty($params['sale_time']) ? "" : $params['sale_time'];
        $data['remark'] = empty($params['remark']) ? "" : $params['remark'];
        if(empty($id)){
            $data['add_by'] = empty($user_info['id']) ? "" : $user_info['id'];
            $data['add_by_name'] = empty($user_info['user_name']) ? "" : $user_info['user_name'];
            $exit = Db::name('consumable')->where('rfid',$params['rfid'])->find();
            if(empty($exit)){
                $result = Db::name('consumable')->insert($data);
            }else{
                app_send('', '400', '设备已存在,请仔细核对设备名称');
            }
        }else{
            $result = Db::name('consumable')->where('id',$id)->update($data);
        }
        if($result === 1 || $result === 0){
            return true;
        }
    }
    //耗材详情
    public function consumable_details($id)
    {
        $details = Db::name('consumable')->where('id',$id)->find();
        return $details;
    }
    //耗材删除
    public function consumable_delete($id)
    {
        $result = Db::name('consumable')->where('id','in',$id['id'])->delete();
        return $result;
    }
    //读取xls数据
    public function actionRead($filename,$encode='utf-8') {
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename,$encode);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        //return $highestRow;
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = [];
        for($row = 1; $row <= $highestRow; $row++) {
            $rowData = array();
            for ($col = 0; $col <= $highestColumnIndex; $col++) {
                $rowData[]=(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            $excelData[] = $rowData;
        }
        return $excelData;
    }
    //耗材标记保存
    public function consumable_mark_save($params)
    {
        $id = $params['id'];
        $data['status'] = empty($params['status']) ? "" : $params['status'];
        $data['remark'] = empty($params['remark']) ? "" : $params['remark'];
        $result = Db::name('consumable')->where('id', $id)->update($data);
        if ($result === 1 || $result === 0) {
            return true;
        }
    }
}