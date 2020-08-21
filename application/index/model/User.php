<?php
namespace app\index\model;

use app\common\entity\Config;
use app\common\entity\Log;
use app\common\entity\Orders;
use app\common\entity\UserInviteCode;
use app\common\entity\UserProduct;
use app\common\entity\UserStatistics;
use app\common\service\Users\Cache;
use app\common\service\Users\Identity;
use app\common\service\Users\Service;
use think\Db;
use think\facade\Request;

class User
{

    public function checkRegisterOpen()
    {
        $registerOpen = Config::getValue('register_open');
        if ($registerOpen) {
            return true;
        }
        return false;
    }

    public function checkIp()
    {
        $ipTotal = Config::getValue('register_ip');
        $ip = Request::ip();
        $total = \app\common\entity\User::where('register_ip', $ip)->count();
        if ($ipTotal > $total) {
            return true;
        }
        return false;
    }

    public function doRegister($data)
    {
        $entity = new \app\common\entity\User();
        $service = new Service();
        $parentId = (new UserInviteCode())->getUserIdByCode($data['invite_code']);
        $entity->mobile = $data['mobile'];
        $entity->nick_name = $data['nick_name'];
        $entity->password = $service->getPassword($data['password']);
        $entity->trad_password = $service->getPassword($data['safe_password']);
        $entity->register_time = time();
        $entity->register_ip = Request::ip();
        $entity->status = \app\common\entity\User::STATUS_DEFAULT;
        $entity->is_certification = \app\common\entity\User::AUTH_ERROR;
        $entity->pid = $parentId;
        $entity->session_id =  session_id();

        if ($entity->save()) {
            $inviteCode = new UserInviteCode();
            $inviteCode->saveCode($entity->id);
//            $this->sendRegisterReward($entity);
            //返利
            $this->addInviteRecord($entity);


            //清除全部会员的缓存
            $cache = new Cache();
            $cache->delCache();

            return true;
        }
        return false;
    }

    /*邀请信息*/
    public function addInviteRecord($user){
        $user_m = new \app\common\entity\User();
        $invite_m = new \app\common\entity\Invite();
        $parent_info_1 = $user_m->getParentInfo($user->pid);
        if($parent_info_1){
            $data1 = array(
                'user_id'=>$parent_info_1['id'],
                'sub_user_id'=>$user->id,
                'type'=>1,
                'level'=>1,
                'is_cert'=>1,
            );
            $invite_m->saveRecord($data1);
            $parent_info_2 = $user_m->getParentInfo($parent_info_1['pid']);
            //统计
            UserStatistics::setFieldInc($parent_info_1['id'],'one_sub_nums',1);

            if($parent_info_2){
                $data2 = array(
                    'user_id'=>$parent_info_2['id'],
                    'sub_user_id'=>$user->id,
                    'type'=>1,
                    'level'=>2,
                    'is_cert'=>1,
                );
                $invite_m->saveRecord($data2);
                UserStatistics::setFieldInc($parent_info_2['id'],'two_sub_nums',1);
            }
        }
    }
    //注册赠送
    public function sendRegisterReward($user)
    {
        $registerReward = Config::getValue('register_send_produc');
        if (!$registerReward) {
            return true;
        }
        $number = Config::getValue('register_send_product_num');
        if ($number < 1) {
            return true;
        }

        //送矿机
        $model = new UserProduct();
        for ($i = 0; $i < $number; $i++) {
            $result = $model->addInfo($user->id, 1, UserProduct::TYPE_REGISTER);

            if (!$result) {
                Log::addLog(Log::TYPE_PRODUCT, '注册送矿机失败', [
                    'user_id' => $user->id,
                    'mobile' => $user->mobile
                ]);
            }
        }
    }

    public function get_td_data($list,$time_field,$field,$decimal=2){
        $arr = array();
        $sum_num_arr = array();
        foreach ($list as $item){
            $k = date("Y-m-d",$item[$time_field]);
            if($item[$field] != 0){
                $item['show_'.$field] = set_number($item[$field],$decimal);
                $arr[$k][] = $item;
            }
        }

        if(!empty($arr)){
            foreach ($arr as $key=>$value){
                $num_arr = self::sum_array($value,$time_field,'show_'.$field,$decimal);
                $sum_num_arr = array_merge($sum_num_arr,$num_arr);
            }
        }

        return array(
            'sum_num_arr' => $sum_num_arr,
            'num_arr' => $arr
        );
    }

   public function sum_array($data,$time_field,$filed,$decimal){
        $sum_arr = array();
        foreach ($data as $key=>$value){
            $kk = date("Y-m-d",$value[$time_field]);
            if($value[$filed] != 0){
                $sum_arr[$kk][] = $value[$filed];
            }
        }

        $sum_num_arr = array();
        foreach ($sum_arr as $k=>$v){
            $array_sum = set_number(array_sum($v),$decimal);
            $sum_num_arr[$k] = $array_sum>0?'+'.$array_sum:$array_sum;
        }

        return $sum_num_arr;

    }

    /**
     * 得到用户的详细信息
     */
    public function getInfo($id)
    {
        return \app\common\entity\User::where('id', $id)->find();
    }

    /**
     * 银行卡号 微信号 支付宝账号 唯一
     */
    public function checkMsg($type, $account, $id = '')
    {
        return \app\common\entity\User::where("$type", $account)->where('id', '<>', $id)->find();
    }

    public function doLogin($account, $password,$is_check)
    {
        $user = \app\common\entity\User::where('mobile', $account)->find();
        if (!$user) {
            return array(
                'msg'=>'账号或者密码错误',
                'ret'=>1
            );
        }
        $model = new \app\common\service\Users\Service();
        if (!$model->checkPassword($password, $user)) {
            return array(
                'msg'=>'账号或者密码错误',
                'ret'=>1,

            );
        }

        if ($user->status == \app\common\entity\User::STATUS_FORBIDDED) {
            return array(
                'msg'=>'您的账号已被禁止登录',
                'ret'=>1
            );
        }
//        if($is_check == 1){
//            /*查询最近5次登录ip*/
//            $login_monitor = Db::table('login_monitor')
//                ->where('user_id','=',$user->id)
//                ->limit(1)
//                ->order('add_time desc')
//                ->column('ip_str');
//
//            if(count($login_monitor) == 0){
//                $send_content = getSMSTemplate('3751460');
//                $send_model = new SendCode($account, 'change-password',$send_content);
//                $send_r = $send_model->send();
//
//                return array(
//                    'msg'=>$send_r['ret'] == 0 ?'验证手机验证码':"验证手机验证码失败",
//                    'ret'=>$send_r['ret'] == 0 ?2:3,
//                    'data'=> yc_phone($account)
//                );
//            }else{
//                $is_unique = 1;
//                foreach ($login_monitor as $l_v){
//                    if($l_v != Request::ip()){
//                        $is_unique = 0;
//                    }
//                }
//                if($is_unique == 0){
//                    $send_content = getSMSTemplate('3751460');
//                    $send_model = new SendCode($account, 'change-password',$send_content);
//                    $send_r = $send_model->send();
//                    return array(
//                        'msg'=>$send_r['ret'] == 0 ?'验证手机验证码':"验证手机验证码失败",
//                        'ret'=>$send_r['ret'] == 0 ?2:3,
//                        'data'=> yc_phone($account)
//                    );
//                }
//            }
//        }
        //保存session
        $identity = new Identity();
        $identity->saveSession($user);
        \app\common\entity\User::where('mobile', $account)->update(array(
            'login_ip'   => Request::ip(),
            'session_id' => session_id(),
            'login_time' => time()
        ));
        //登录monitor
        Db::table('login_monitor')->insert(array(
            'user_id' => $user->id,
            'ip_str'=>Request::ip(),
            'add_time'=>time()
        ));
        return array(
            'ret'=>0,
            'msg'=>'成功'
        );
    }


}