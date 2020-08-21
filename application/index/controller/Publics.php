<?php
namespace app\index\controller;

use app\common\entity\Orders;
use app\common\entity\Product;
use app\common\entity\ProductIncomeRecord;
use app\common\entity\User;
use app\common\entity\UserInviteCode;
use app\common\entity\UserMagicLog;
use app\common\entity\UserStatistics;
use app\common\entity\UserStatisticsLog;
use app\common\service\Product\Compute;
use app\common\service\Users\Jwt;
use app\common\service\Users\Service;
use app\index\model\SendCode;
use app\index\model\SiteAuth;
use app\index\validate\RegisterForm;
use think\Controller;
use think\Db;
use app\common\service\Users\Identity;
use \app\common\entity\Config;


use think\Request;

use \think\Facade\Cache as cache_1;

class Publics extends Controller
{

    public function initialize()
    {
        $switch = SiteAuth::checkSite();
        if ($switch !== true) {
            if (request()->isAjax()) {
                return json(['code' => 1, 'message' => $switch]);
            } else {
                (new SiteAuth())->alert($switch);
            }
        }
        parent::initialize();
    }

    public function index()
    {

        cache_1::store('File')->clear();
        return $this->fetch('login');
    }

    //登录处理
    public function login(Request $request)
    {
        $result = $this->validate($request->post(), 'app\index\validate\LoginForm');

        if ($result !== true) {
            return json(['code' => 1, 'message' => $result]);
        }
        $code_num_str ="{$request->post('account')}_code_num";

        if($request->post('is_check') != 1){

            $form = new RegisterForm();
            if (!$form->checkChange($request->post('code'), $request->post('account'))) {
                $code_num = cache_1::store('File')->get($code_num_str);
                $tourl = '';
                $msg = '验证码输入错误';
                if(!$code_num){
                    cache_1::store('File')->set($code_num_str,3);
                    $show_code_num = 3;
                    $msg = "您还剩{$show_code_num}次机会，请正确输入";
                }elseif ($code_num<=3 && $code_num>1){
                    cache_1::store('File')->dec($code_num_str);
                    $show_code_num = $code_num - 1;
                    $msg = "您还剩{$show_code_num}次机会，请正确输入";
                }else{
                    $tourl = url('index');
                }
                return json(['code' => 1, 'message' => $msg,'toUrl' => $tourl]);
            }
        }
        cache_1::store('File')->rm($code_num_str);
        $model  = new \app\index\model\User();
        $res = $model->doLogin($request->post('account'), $request->post('password'),$request->post('is_check'));

        if ($res['ret'] != 0) {
            return json(['code' => $res['ret'], 'message' => $res['msg'],'data'=>$res['data']]);
        }


        return json(['code' => 0, 'message' => '登录成功', 'toUrl' => url('/index/member/index')]);
    }

    public function register(Request $request)
    {
        $code = $request->get('code', '');
        /*if (!$code) {
        $this->error('平台暂未开启自己注册');
        }*/
        //判断code是否正确
        if ($code) {
            $model  = new UserInviteCode();
            $result = $model->getUserIdByCode($code);
            if (!$result) {
                (new SiteAuth())->alert('邀请码不正确');
            }
        }
        $rand_str = getRandStr();
        $token = Jwt::genToken($rand_str);
        cache_1::set($rand_str,$rand_str,2*60*60);
        return $this->fetch('register', [
            'code' => $code,'token'=>$token
        ]);
    }
public function register2(Request $request)
    {
        $code = $request->get('code', '');
        /*if (!$code) {
        $this->error('平台暂未开启自己注册');
        }*/
        //判断code是否正确
        if ($code) {
            $model  = new UserInviteCode();
            $result = $model->getUserIdByCode($code);
            if (!$result) {
                (new SiteAuth())->alert('邀请码不正确');
            }
        }
        $rand_str = getRandStr();
        $token = Jwt::genToken($rand_str);
        cache_1::set($rand_str,$rand_str,2*60*60);
        return $this->fetch('register2', [
            'code' => $code,'token'=>$token
        ]);
    }
    public function doRegister(Request $request)
    {
        $model = new \app\index\model\User();
        if (!$model->checkRegisterOpen()) {
            return json(['code' => 1, 'message' => '平台已关闭注册了']);
        }
        if (!$model->checkIp()) {
            return json(['code' => 1, 'message' => '兄弟，好像注册太多了哦']);
        }

        $validate = $this->validate($request->post(), '\app\index\validate\RegisterForm');
        if ($validate !== true) {
            return json(['code' => 1, 'message' => $validate]);
        }

        $form = new RegisterForm();
        if (!$form->checkCode($request->post('code'), $request->post('mobile'))) {
            return json(['code' => 1, 'message' => '验证码输入错误']);
        }
        //注册处理
        $result = $model->doRegister($request->post());
        if ($result) {
            $register_success_jump_url = Config::getValue('register_success_jump_url');
            $register_success_jump_url = $register_success_jump_url?$register_success_jump_url: url('index');
            return json(['code' => 0, 'message' => '注册成功', 'toUrl' => $register_success_jump_url]);
        }
        return json(['code' => 1, 'message' => '注册失败']);
    }

    //发送注册验证码
    public function send(Request $request)
    {
        if ($request->isPost()) {
            $mobile = $request->post('mobile');
            //检验手机号码
            if (!preg_match('/^1[123456789][0-9]{9}$/', $mobile)) {
                return json(['code' => 1, 'message' => '手机号码格式不正确']);
            }
            //判断手机号码是否已被注册
            if (User::checkMobile($mobile)) {
                return json(['code' => 1, 'message' => '此账号已被注册，请重新填写']);
            }
            $token = $request->get('token');
            $rand_str = Jwt::verifyToken($token);
            $cache_str = cache_1::get($rand_str);

             //if(!Jwt::verifyToken($token) || !$cache_str){
             //    return json(['code' => 1, 'message' => '请重新进入页面请求短信']);
             //}
            $send_content = getSMSTemplate('3751460');

            $model = new SendCode($mobile, 'register',$send_content);
            $r = $model->send();
            if ($r['ret'] == 0) {
                cache_1::rm($rand_str);
                return json(['code' => 0, 'message' => '你的验证码发送成功']);
                //return json(['code' => 0, 'message' => '你的验证码为' . $model->code]);
            }
            return json(['code' => 1, 'message' => $r['msg']]);
        }
    }

    //找回密码
    public function change()
    {
        $rand_str = getRandStr();
        $token = Jwt::genToken($rand_str);
        cache_1::set($rand_str,$rand_str,60*60*2);
        return $this->fetch('change',['token'=>$token]);
    }

    //发送找回密码验证码
    public function sendChange(Request $request)
    {

        if ($request->isPost()) {
            $mobile = $request->post('mobile');
            //检验手机号码
            if (!preg_match('/^1[123456789][0-9]{9}$/', $mobile)) {
                return json(['code' => 1, 'message' => '手机号码格式不正确']);
            }
            //判断手机号码是否注册
            if (!User::checkMobile($mobile)) {
                return json(['code' => 1, 'message' => '此账号不存在，请重新填写']);
            }
            $token = $request->get('token');
            $rand_str = Jwt::verifyToken($token);
            $cache_str = cache_1::get($rand_str);

            // if(!Jwt::verifyToken($token) || !$cache_str){
            //     return json(['code' => 1, 'message' => '请重新进入页面请求短信']);
            // }

            $send_content = getSMSTemplate('3751460');

			
            $model = new SendCode($mobile, 'change-password',$send_content);
            $r = $model->send();
            if ($r['ret'] == 0) {
                cache_1::rm($rand_str);
                return json(['code' => 0, 'message' => '你的验证码发送成功']);
                //return json(['code' => 0, 'message' => '你的验证码为' . $model->code]);
            }

            return json(['code' => 1, 'message' => $r['msg']]);
        }
    }

    /**
     * 找回密码 修改密码
     */
    public function changeSave(Request $request)
    {
        $mobile = $request->post("mobile");
        //检验手机号码
        if (!preg_match('/^1[345789][0-9]{9}$/', $mobile)) {
            return json(['code' => 1, 'message' => '手机号码格式不正确']);
        }
        $user = User::where("mobile", $mobile)->find();
        //判断手机号码是否注册
        if (!User::checkMobile($mobile)) {
            return json(['code' => 1, 'message' => '此账号不存在，请重新填写']);
        }

        $new_pwd     = $request->post("new_pwd"); //新密码
        $confirm_pwd = $request->post("confirm_pwd"); //确认密码

        $service = new Service();
        if ($new_pwd != $confirm_pwd) {
            return json(['code' => 1, 'message' => '两次密码输入不一致']);
        }
        if ($service->getPassword($new_pwd) == $user->password) {
            return json(['code' => 1, 'message' => '密码没变']);
        }

//        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,10}$/', $new_pwd)) {
//            return json(['code' => 1, 'message' => '密码必须为8位数以上且包含数字字母']);
//        }
        if (strlen($new_pwd) < 8) {
            return json(['code' => 1, 'message' => '密码长度至少8位']);
        }
        if (!$request->post("code")) {
            return json(['code' => 1, 'message' => '验证码不能为空']);
        }

        $form = new RegisterForm();
        if (!$form->checkChange($request->post('code'), $request->post('mobile'))) {
            return json(['code' => 1, 'message' => '验证码输入错误']);
        }

        $res = User::where("mobile", $mobile)->update(["password" => $service->getPassword($new_pwd)]);
        if ($res) {
            return json(['code' => 0, 'message' => '密码修改成功', 'toUrl' => url('index')]);
        } else {
            return json(['code' => 1, 'message' => '密码修改失败']);
        }
    }

    /**
     * @return mixed
     * 实名 3要素
     */
    public function cert(){
        $identity = new Identity();
        $userId = $identity->getUserId();
        if (!$userId) {
            $this->redirect('publics/index');
        }
        $mobile = $identity->getUserMobile();
        return $this->fetch('cert2',['mobile'=>$mobile]);
    }
    /**
     * @return mixed
     * 实名 4要素
     */
    public function cert2(){
        $identity = new Identity();
        $userId = $identity->getUserId();
        if (!$userId) {
            $this->redirect('publics/index');
        }
        $mobile = $identity->getUserMobile();
        return $this->fetch('cert2',['mobile'=>$mobile]);
    }

    /**
     * 实名接口
     */
    public function cert_api(Request $request){
        $identity = new Identity();
        $userId = $identity->getUserId();
        if (!$userId) {
            return json(['code' => 1, 'message' => "请先登录",'toUrl'=>url('publics/index')]);
        }
        $validate = $this->validate($request->post(), '\app\index\validate\CertForm');
        if ($validate !== true) {
            return json(['code' => 1, 'message' => $validate]);
        }
        $type = $request->post('type');
        $card_id = $request->post('card_id');
//        if($type == 2){
//
//        }
        $card =  $request->post('card');
        if(!$card){
            return json(['code' => 1, 'message' => "请输入银行卡号"]);
        }
        if(User::where(array('card_id'=>$card_id))->find()){
            return json(['code' => 1, 'message' => "该身份证号已存在"]);
        }
        $cert_num = \app\common\entity\Config::getValue('cert_num');
        $cert_w[] = ['user_id','=',$userId];
        $cert_w[] = array('add_time','between',array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time()))));
        $cert_w1 = array_merge($cert_w,array(['types','=',1]));

        $cert_record1 = Db::table('user_cert_record')->where($cert_w1)->select();
        $cert_record1 = count($cert_record1);

        $cert_w2 = array_merge($cert_w,array(['types','=',2]));
        $cert_record2 = Db::table('user_cert_record')->where($cert_w2)->select();

        $cert_record2 = count($cert_record2);
        $update_data = array(
            'real_name' =>$request->post('real_name'),
            'card_id' =>$request->post('card_id'),
        );
//        if($type == 1){
//            if($cert_record1>= $cert_num && $cert_record2<$cert_num){
//                return json(['code' => 1, 'message' => "该验证次数已使用完，请更换验证方式"]);
//            }elseif($cert_record1>= $cert_num && $cert_record2>=$cert_num){
//                  $identity->logout();
//
//                return json(['code' => 1, 'message' => "验证次数已使用完,请明天再来",'toUrl'=>url('publics/index')]);
//            }
//            $r = cert_api_1($request->post());
//
//        }else{
            $update_data['card'] = $request->post('card');
//            if($cert_record2 >= $cert_num && $cert_record1<$cert_num){
            if($cert_record2 >= $cert_num){
//                return json(['code' => 1, 'message' => "该验证次数已使用完，请更换验证方式"]);
                return json(['code' => 1, 'message' => "验证次数已使用完,请明天再来",'toUrl'=>url('publics/index')]);
            }elseif($cert_record2 >= $cert_num && $cert_record1 >= $cert_num){
            	$identity->logout();
                return json(['code' => 1, 'message' => "验证次数已使用完,请明天再来",'toUrl'=>url('publics/index')]);
            }
            $r = cert_api_2($request->post());
//        }
        //实名记录
        Db::table('user_cert_record')->insert(array(
            'user_id'=>$userId,
            'types'=>$type,
            'add_time'=>time(),
        ));
        $u_w[] = ['id','=',$userId];
        if($r == false){
            $fail_update_data['is_certification'] = 2;
            $update_r = User::update($fail_update_data,$u_w);
            return json(['code' => 1, 'message' => "实名失败"]);
        }
        $update_data['is_certification'] = 1;
        $update_r = User::update($update_data,$u_w);
        if(!$update_r){
            return json(['code' => 1, 'message' => "实名失败"]);
        }
        //实名奖励
        $register_send_product_switch = \app\common\entity\Config::getValue('register_send_product_switch');
        $register_send_product = \app\common\entity\Config::getValue('register_send_product');

        if($register_send_product_switch == 1){
            $w[] = ['id','=',$register_send_product];
            $prod_info = \app\common\entity\Product::getOneProduct($w)->toArray();

            if($prod_info){
                $prod_data = array(
                    'user_id' => $userId,
                    'product_id' => $register_send_product,
                    'period' => $prod_info['period'],
                    'is_receive' => 0,
                    'candy_num' =>  $prod_info['candy_num'],
                    'return_candy_num' =>  $prod_info['return_candy_num'],
                    'energy_num' =>  $prod_info['energy_num'],
                    'types' =>  3,
                );

                \app\common\entity\Product::addProductRecord($prod_data);
                //增加算力
                User::setIncEnergy($userId,$prod_info['energy_num']);
            }

        }
        //实名上级奖励
         $entity = User::getUserInfo($userId);
         \app\admin\controller\User::addCertInviteRecord($entity);
        //赠送流入金，消费金
        $register_send_buy_nums = Config::getValue('register_send_buy_nums');
        $register_send_consume_nums = Config::getValue('register_send_consume_nums');
        UserStatisticsLog::changeUserMagic($userId,'buy_nums',array(
            'magic' => $register_send_buy_nums,
            'type' => 1,
            'remark' => '实名赠送'.$register_send_buy_nums.'流入金',
        ));
        UserStatisticsLog::changeUserMagic($userId,'buy_machine_num',array(
            'magic' => $register_send_consume_nums,
            'type' => 2,
            'remark' => '实名赠送'.$register_send_consume_nums.'消费金',
        ));
        return json(['code' => 0, 'message' => "实名成功",'toUrl'=>url('/index/member/index')]);
    }


    /**
     * @param $data
     * 订单超时处理
     */
    public function op_times_out_orders(){
        $order_m = new Orders();
        $user_m = new User();
        $uml_m = new UserMagicLog();

        //买家打款时间(小时)
        $trade_buyer_pay_hours = Config::getValue('trade_buyer_pay_hours');
        //卖家处理时间(小时)
        $trade_saler_deal_hours = Config::getValue('trade_saler_deal_hours');
        //投诉卖家处理时间（小时）
        $trade_complain_saler_deal_hours = Config::getValue('trade_complain_saler_deal_hours');
        //交易订单失效时间（小时）
        $trade_order_invalid_hours = Config::getValue('trade_order_invalid_hours');

        $o_w[] = ['status','in','1,2,3'];
        $o_w[] = ['types','=',1];
        $order_list = $order_m->where($o_w)->select()->toArray();
        if($order_list){
            foreach ($order_list as $order){
                $buy_mobile = $user_m->getUserPhone($order['user_id']);
                if($order['status'] == 1){ //等待匹配
                    //挂单失效
                    if(time()>($order['create_time'] + $trade_order_invalid_hours * 3600)){
//                        $order_m->where(array('id'=>$order['id']))->delete();
                        $order_m->saveOrder(array('id'=>$order['id']),array('status'=>5,'expired_time'=>time()));
                    }
                }elseif ($order['status'] == 2){ //等待付款
                    if(time()>$order['match_time']+$trade_buyer_pay_hours * 3600){ //超时
                        /*卖家退云链*/
                        $re_magic = $order['number'] + $order['charge_number'];
                        $r = $uml_m->changeUserMagic($order['target_user_id'], [
                            'magic' => $re_magic,
                            'remark' => '市场卖出退回'.$re_magic.'云链',
                            'type' => 18
                        ], 1);
                        if($r){
                            $order_m->saveOrder(array('id'=>$order['id']),array('status'=>5,'expired_time'=>time()));
                            $order_m->saveOrder(array('user_id'=>$order['user_id'],'status'=>1),array('status'=>5,'expired_time'=>time()));//下架所有挂单
                            /*买家禁止交易*/
                            $user_m::where(array('id'=>$order['user_id']))->update(array('order_status'=>-1));
                            //发短信
//                             $content = getSMSTemplate('3751466');
//                             sendNewSMS($buy_mobile,$content,0);
                        }
                    }
                }elseif($order['status'] == 3){ //等待确认收款
                    if(time()>$order['pay_time']+$trade_saler_deal_hours * 3600){ //超时未处理

                        $r = $uml_m->changeUserMagic($order['user_id'], [
                            'magic' => $order['number'],
                            'remark' => '市场买入'.$order['number'].'云链',
                            'type' => 2
                        ], 1);
                        if($r){
                            $order_m->saveOrder(array('id'=>$order['id']),array('status'=>4,'finish_time'=>time()));
                            //发短信
//                            $content = getSMSTemplate('3751462');
//                            sendNewSMS($buy_mobile,$content,0);
                        }
                    }
                }elseif($order['status'] == 6){ //订单投诉
//                    if(time()>$order['pay_time']+$trade_saler_deal_hours * 3600){//投诉超时未处理
//
//                    }

                }
            }
        }
       //退租
        $re_where['is_receive'] = 0;
        $list = Product::getProductRecord($re_where);
        if($list){
            foreach ($list as $k=>$value){
                $period = $value['period'] * 24 * 3600;
                $left_day = ($period + $value['add_time']) - time() ;
                if ($left_day > 0) {
                } else {//可退租
                    Compute::do_rehire($value);

                }
            }
        }

    }
    //恢复数据
    public function restore_magic(){

        //糖果清零
        Db::table('user')->where('1=1')->update(array(
            'magic'=>0
        ));
        /*查询直推人数*/
        $one_data = Db::table('invite_record')->where(array(
            'types'=>1,
            'is_cert'=>2,
            'level'=>1
        ))->field('user_id')->select();

        if($one_data){
            foreach ($one_data as $one_item){
                $one_w = array('id'=>$one_item['user_id']);
                $one_info = Db::table('user')->where($one_w)->field('is_certification')->find();
                if($one_info['is_certification'] == 1){
                    Db::table('user')->where($one_w)->setInc('magic',10);
                }
            }
        }
        echo "直推成功，接下来间推...<br>";
        /*查询间推人数*/
        $two_data = Db::table('invite_record')->where(array(
            'types'=>1,
            'is_cert'=>2,
            'level'=>2
        ))->field('user_id')->select();
        if($two_data){
            foreach ($two_data as $two_item){
                $two_w = array('id'=>$two_item['user_id']);
                $two_info = Db::table('user')->where($two_w)->field('is_certification')->find();
                if($two_info['is_certification'] == 1){
                    Db::table('user')->where($two_w)->setInc('magic',5);
                }
            }
        }
        echo "间推成功，接下来赠送租工厂...<br>";
        /*租工厂赠送*/
        $user_list = Db::table('user')->where(array('is_certification'=>1))->field('id')->select();
        if($user_list){
            $w[] = ['id','=',1];
            $prod_info = \app\common\entity\Product::getOneProduct($w)->toArray();
            if(!$prod_info){
                echo '租工厂赠送失败';
                exit;
            }
            $prod_data = array(
                'product_id' => 1,
                'period' => $prod_info['period'],
                'is_receive' => 0,
                'candy_num' =>  $prod_info['candy_num'],
                'return_candy_num' =>  $prod_info['return_candy_num'],
                'energy_num' =>  $prod_info['energy_num'],
                'types' =>  1,
            );
            foreach ($user_list as $user){

                $prod_data['user_id'] =$user['id'];
                \app\common\entity\Product::addProductRecord($prod_data);
                //增加算力
                User::setIncEnergy($user['id'],$prod_info['energy_num']);
            }
            echo '租工厂赠送成功,完成';
            exit;
        }

    }

    /**
     * 恢复下级
     */
//     public function restore_sub(){
//         UserStatistics::where("1=1")->delete();
//         /*查询直推人数*/
//         $one_data = Db::table('invite_record')->where(array(
//             'types'     =>  1,
// //            'is_cert'   =>  2,
//             'level'     =>  1
//         ))->field('user_id')->select();

//         if($one_data){
//             foreach ($one_data as $one_item){
//                 UserStatistics::setFieldInc($one_item['user_id'],'one_sub_nums',1);
//             }
//         }
//         echo "直推人数成功，接下来间推...<br>";
//         /*查询间推人数*/
//         $two_data = Db::table('invite_record')->where(array(
//             'types'     =>  1,
// //            'is_cert'   =>  2,
//             'level'     =>  2
//         ))->field('user_id')->select();
//         if($two_data){
//             foreach ($two_data as $two_item){

//                 UserStatistics::setFieldInc($two_item['user_id'],'two_sub_nums',1);

//             }
//         }
//         echo "间推人数成功，接下来直推赠送租工厂消耗糖果...<br>";
//         /*查询直推赠送租工厂消耗糖果*/
//         $one_consume_data = Db::table('invite_record')->where(array(
//             'types' =>  2,
//             'level' =>  1
//         ))->field('user_id,num')->select();

//         if($one_consume_data){
//             foreach ($one_consume_data as $one_consume_item){
//                 UserStatistics::setFieldInc($one_consume_item['user_id'],'contribution_nums',$one_consume_item['num']);
//             }
//         }
//         echo "直推赠送租工厂消耗糖果成功，接下来间推赠送租工厂消耗糖果...<br>";
//         /*查询直推赠送租工厂消耗糖果*/
//         $two_consume_data = Db::table('invite_record')->where(array(
//             'types' =>  2,
//             'level' =>  2
//         ))->field('user_id,num')->select();

//         if($two_consume_data){
//             foreach ($two_consume_data as $two_consume_item){
//                 UserStatistics::setFieldInc($two_consume_item['user_id'],'contribution_nums',$two_consume_item['num']);
//             }
//         }
//         echo '间推赠送租工厂消耗糖果成功,结束。。。。';
//         exit;
//     }
    public function reward_consumption(){
        //实名用户每人奖励4流入金+8消费金
//        $user_list = Db::table('user')->where('is_certification','=',1)->select();
//        foreach ($user_list as $user){
//            UserStatistics::setFieldInc($user['id'],'buy_nums',4);
//            UserStatistics::setFieldInc($user['id'],'buy_machine_num',8);
//        }
        //所有用户持有的矿机到期时间延长5天。
        Db::table('product_record')->where(array('is_receive'=>0))->setInc('period',5);
    }
    public function download(){
        return $this->fetch('download_view');
    }
}
