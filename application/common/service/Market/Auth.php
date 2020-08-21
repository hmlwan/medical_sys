<?php
namespace app\common\service\Market;

use app\common\entity\Config;
use app\common\entity\Invite;
use app\common\entity\User;
use app\common\entity\UserMagicLog;
use app\common\service\Users\Identity;
use app\index\model\SiteAuth;
use think\exception\HttpException;
use think\facade\Cache;
use think\Request;

class Auth
{
    const CACHE_NAME = 'flow_box_market_%s';
    const CACHE_TTS = 1800;

    private $memberId = 0;

    private $userInfo = null;

    public function __construct()
    {
        $model = new Identity();
        $this->memberId = $model->getUserId();

        $userInfo = User::where('id', $this->memberId)->find();
        if (!$userInfo) {
            throw new HttpException('对象不存在');
        }

        $this->userInfo = $userInfo;
    }

    /**
     * @return bool
     */
    public function checkAuth()
    {
        $auth = Cache::get(self::getCacheKey());
        if ($auth) {
            return true;
        }
        return false;
    }

    /**
     * @param $password
     * @return bool
     */
    public function check($password)
    {
        $service = new \app\common\service\Users\Service();
        $result = $service->checkSafePassword($password, $this->userInfo);
        if ($result) {
            Cache::set($this->getCacheKey(), 1, self::CACHE_TTS);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    private function getCacheKey()
    {
        return sprintf(self::CACHE_NAME, $this->memberId);
    }

    public function identity()
    {
        //判断市场交易时间
        $siteAuth = new SiteAuth();
        if (($auth = $siteAuth->checkSite()) !== true) {
            if (Request()->isAjax()) {
                response(['code' => 1, 'message' => $auth], 200, [], 'json')->send();
                exit;
            } else {
                $siteAuth->alert($auth);
            }
        } elseif (($marketAuth = SiteAuth::checkMarket()) !== true) {
            if (Request()->isAjax()) {
                response(['code' => 1, 'message' => $marketAuth], 200, [], 'json')->send();
                exit;
            } else {
                $siteAuth->alert($marketAuth);
            }

        } elseif ($this->userInfo->is_certification == User::AUTH_ERROR) {
            //判断是否实名认证
            if (Request()->isAjax()) {
                response(['code' => 1, 'message' => '请先去进行实名认证'], 200, [], 'json')->send();
                exit;
            } else {
                $siteAuth->alert('请先去进行实名认证');
            }
        } else {

            if (!$this->checkAuth()) {
                if (Request()->isAjax()) {
                    response(['code' => 1, 'message' => '请先登录交易市场','toUrl' => url('member/login')], 200, [], 'json')->send();
                    exit;
                } else {
                    $siteAuth->alert('请先登录交易市场', url('member/login'));
                }
            }
        }
    }


    /*下线实名*/
    public function addTradeInviteRecord($user)
    {
        $user_m = new \app\common\entity\User();
        $invite_m = new \app\common\entity\Invite();
        $parent_info_1 = $user_m->getParentInfo($user['pid']);

        if($parent_info_1 && $parent_info_1['is_certification'] == 1){
            $first_trade_rebate_one = Config::getValue('first_trade_rebate_one');
            $num1 = $first_trade_rebate_one;
            $data1 = array(
                'user_id'=> $parent_info_1['id'],
                'sub_user_id'=> $user['id'],
                'content' => '一级下线首次交易返佣'.$num1.'云链',
                'num' => $num1,
                'type'=> 5,
                'level'=> 1,
                'is_cert'=> 2,
            );
            $invite_m->saveRecord($data1);
            //收益记录
            $magic_data1 = array(
                'magic' => $num1,
                'remark' => '一级下线首次交易返佣'.$num1.'云链',
                'type'=> 19,
            );
            $magic_log1 = UserMagicLog::changeUserMagic($parent_info_1['id'],$magic_data1,1);


            $parent_info_2 = $user_m->getParentInfo($parent_info_1['pid']);
            if($parent_info_2 && $parent_info_1['is_certification'] == 1){
                $first_trade_rebate_two = Config::getValue('first_trade_rebate_two');
                $num2 = $first_trade_rebate_two;

                $data2 = array(
                    'user_id' => $parent_info_2['id'],
                    'sub_user_id' => $user['id'],
                    'content' => '二级下线首次交易返佣'.$num2.'云链',
                    'num' => $num2,
                    'type' =>5,
                    'level' =>2,
                    'is_cert' =>2,
                );
                $invite_m->saveRecord($data2);
                //收益记录
                $magic_data2 = array(
                    'magic' => $num2,
                    'remark' => '二级下线首次交易返佣'.$num2.'云链',
                    'type' => 19,
                );
                $magic_log2 = UserMagicLog::changeUserMagic($parent_info_2['id'],$magic_data2,1);
            }
        }

    }

}