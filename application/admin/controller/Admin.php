<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\service\Users\Jwt;
use think\Controller;
use think\facade\Request;
use think\facade\Session;

class Admin extends Controller
{

    protected function initialize()
    {
        parent::initialize();
        header("Access-Control-Allow-Origin:*");
        $host_name = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "*";
        $headers = [
            "Access-Control-Allow-Origin" => $host_name,
            "Access-Control-Allow-Credentials" => 'true',
            "Access-Control-Allow-Headers" => "X-Token,x-uid,x-token-check,x-requested-with,content-type,Host"
        ];
        /*获取响应头参数*/
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Jwt::verifyToken($token);
        if(!$uid || !$token ){
            json(['code'=>100,'message'=> '请先登陆','token'=>$uid],200,$headers)->send();
            exit;
//            return json()->data(['code'=>100,'message'=> '请先登陆']);
        }
        $login_mem_id = $uid['login_mem_id'];
        Session::set('USER_KEY_ID',$uid['login_mem_id']);
        Session::set('AUTH_TYPE',$uid['auth_type']);

        if(!$login_mem_id){
//            return json()->data(['code'=>100,'message'=> '请先登陆']);
            json(['code'=>100,'message'=> '请先登陆','login_mem_id'=>$login_mem_id],200,$headers)->send();
            exit;
        }
    }

    public function render($template, $data = [])
    {
        $service = new \app\admin\service\rbac\Users\Service();
        $data['manage'] = $service->getManageInfo();
        $data['menus'] = $this->baseParams();   
        return $this->fetch($template, $data);
    }

    private function baseParams()
    {
        $service = new \app\admin\service\rbac\Power\Service();
        $menus = $service->getMenus();
        return $menus;
    }
}