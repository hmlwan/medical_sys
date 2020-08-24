<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Login extends Controller
{
    public function index()
    {
        if($_SERVER['SERVER_PORT'] != '8080') {
            //die;
        }
        return $this->fetch('index');
    }
     public function test(Request $request){
      return json()->data(['code' => 0, 'message' => '222']);
     }
    /**
     * 登录处理
     */
    public function login(Request $request)
    {


        $service = new \app\admin\service\rbac\Users\Service();
        $result = $this->validate($request->post(), 'app\admin\validate\LoginForm');

        if (true !== $result) {
            return json()->data(['code' => 1, 'message' => $result]);
        }

        if ($service->doLogin($request->post('username'), $request->post('password'))) {
            $power = new \app\admin\service\rbac\Power\Service();
            $power->delCache();
            return json()->data(['toUrl' => url('index/index')]);
        }
    }
}