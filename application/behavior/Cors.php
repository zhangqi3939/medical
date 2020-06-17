<?php
namespace app\behavior;
use think\Controller;
use think\Response;
class Cors extends Controller
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
        if (request()->isOptions()) {
            exit();
        }
    }
}