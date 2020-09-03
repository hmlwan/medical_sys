<?php
namespace app\admin\controller;

use app\common\entity\ManageUser;
use app\common\service\Users\Jwt;
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
      return json()->data(['code' => 0, 'message' => $_SERVER]);
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
        $accout = $request->post('username');
        $r = $service->doLogin($accout, $request->post('password'));
        if ($r['code'] != 0) {
            return json()->data(['code' => 1,'message'=>$r['msg']]);
        }
        $save_data['login_ip'] = \think\facade\Request::ip();
        $save_data['login_time']= time();
        $userInfo = ManageUser::where('manage_name', $accout)
            ->field('id as login_mem_id,manage_name,real_name,auth_type,status')
            ->find();
        $token = Jwt::genToken($userInfo);
        $data = array(
            'x_token' => $token,
            'userInfo' => $userInfo
        );
        return json()->data(['code' => 0,'data'=>$data]);
    }
}