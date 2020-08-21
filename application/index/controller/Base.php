<?php
namespace app\index\controller;

use app\common\entity\User;
use app\common\service\Users\Identity;
use app\index\model\Market;
use app\index\model\SiteAuth;
use think\Controller;
use think\facade\Request;

class Base extends Controller
{
    public $userId;
    public $userInfo;

    public function initialize()
    {

        parent::initialize();
        $this->checkSite();
        $this->checkLogin();

        $this->assign('is_finish_trade',$this->is_finish_trade());

        /*ip不一致重新登录*/
        $session_id = session_id();
        $mem_info =  User::where(array('id'=>$this->userId))->field('is_certification,status,session_id')->find();

        if(($mem_info['session_id'] != $session_id )||($mem_info['status'] != 1)){
            $service = new Identity();
            $service->logout();
            $this->redirect('publics/index');
        }

        /*是否实名*/
        $request = request();
        $REQUEST_URI = $request->server()['REQUEST_URI'];
        $request_url_arr = explode('.',$REQUEST_URI);
        if($mem_info['is_certification'] != 1 && $request_url_arr[0] != '/index/member/index'){
            $this->redirect('publics/cert');
        }

    }
    //判断是否登录
    public function checkLogin()
    {
        $identity = new Identity();
        $userId = $identity->getUserId();

        if (!$userId) {
            $this->redirect('publics/index');
        }
        $this->userId = $userId;
        $this->userInfo = $identity->getUserInfo();
    }

    //检查站点
    public function checkSite()
    {
        $switch = SiteAuth::checkSite();
        if ($switch !== true) {
            if (request()->isAjax()) {
                return json(['code' => 1, 'message' => $switch]);
            } else {
                (new SiteAuth())->alert($switch);
                exit;
            }
        }
    }
    //否还有交易没完成
    public function is_finish_trade(){
        $is_finish_trade = 0;
        if(Market::checkOrder($this->userId)){
            $is_finish_trade= 1;
        }

        return $is_finish_trade;
    }
}