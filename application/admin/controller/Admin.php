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
//        if (php_sapi_name() != 'cli') {
//            //判断用户是否登录
//            $service = new \app\admin\service\rbac\Users\Service();
//            if (!$service->getManageId()) {
//                $this->redirect('login/index');
//            }
//
//            //判断权限
//            $service = new \app\admin\service\rbac\Users\Service();
//            if (!$service->checkAuth()) {
//                if (Request::isAjax()) {
//                    throw new AdminException('没有权限操作，请联系管理员');
//                } else {
//                    $this->error('没有权限操作，请联系管理员');
//                }
//            }
//        }
        /*获取响应头参数*/
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Jwt::verifyToken($token);

        if(!$uid ||$token ){
            return json()->data(['code'=>100,'message'=> '请先登陆']);
        }
        $service = new \app\admin\service\rbac\Users\Service();
        $manage_info = $service->getManageInfo();
        Session::set('USER_KEY_ID',$uid['login_mem_id']);
        Session::set('AUTH_TYPE',$uid['auth_type']);

        if(!$manage_info){
            return json()->data(['code'=>100,'message'=> '请先登陆']);
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