<?php
namespace app\index\controller;

use app\admin\controller\Config;
use app\common\entity\AdvConfig;
use app\common\entity\ProductIncomeRecord;
use app\common\entity\Sl;
use app\common\entity\UserArticleRecord;
use app\common\entity\Product;
use app\common\entity\UserInviteCode;
use app\common\entity\UserStatistics;
use app\common\service\Market\Auth;
use app\common\service\Product\Compute;
use app\common\service\Users\Identity;
use app\common\service\Users\Jwt;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use phpqrcode\QRcode;
use Grafika\Color;
use Grafika\Grafika;
use think\facade\Env;
use think\facade\Session;
use think\facade\Url;
use think\Request;
use app\common\service\Users\Service;
use app\common\entity\User;
use app\common\entity\UserProduct;
use app\common\entity\Config as conf_m;
use app\common\entity\UserMagicLog;
use app\index\validate\RegisterForm;
use \think\Facade\Cache as cache_1;

use think\Db;

class Member extends Base
{
    public function index()
    {
//        dd(sendNewSMS("15179811531",getSMSTemplate(3751462)));
        //获取缓存用户详细信息
        $userInfo = User::where('id', $this->userId)->find();
        $userInfo['show_mobile'] = yc_phone($userInfo['mobile']);
        $userInfo['show_magic'] = get_number($userInfo['magic'],3);
        $userInviteCode_m  = new UserInviteCode();
        $invite_code = $userInviteCode_m->getCodeByUserId($userInfo['id']);
        $userInfo['invite_code'] = $invite_code;

        //获取用户冻结资金 和交易总数
        $freeze = $userInfo->getFreeze();

        /*未读留言*/
        $is_read_list = \app\common\entity\Message::where(array('user_id'=>$this->userId,'is_read'=>0))->select();

        /*未读公告*/
        $post_w = array(
            'user_id'=>$this->userId,
            'cate_id'=>1,
            'is_read'=>0,
        );
        $no_read_post = UserArticleRecord::is_exist($post_w);
        $post_w['cate_id'] = 2;
        $no_read_post1 = UserArticleRecord::is_exist($post_w);

        //上级微信
        $parent_info = array();

        if($userInfo['pid']){
            $parent_info = User::getParentInfo($userInfo['pid']);
        }
           //退租
//        $where['user_id'] = $this->userId;
//        $where['is_receive'] = 0;
//        $list = Product::getProductRecord($where);
//
//        if($list){
//            foreach ($list as $k=>$value){
//                $period = $value['period'] * 24 * 3600;
//                $left_day = ($period + $value['add_time']) - strtotime(date("Y-m-d", time())) ;
//                if ($left_day > 0) {
//                } else {//可退租
//                    Compute::do_rehire($value);
//                }
//            }
//        }
        $market_money_num = conf_m::getValue('market_money_rate') * $userInfo['magic'];
        $market_money_num = set_number($market_money_num,2);
        //随机广告位
        $adv_info = AdvConfig::getOneById(array(),'rand()');
        if($adv_info){
            $adv_info = $adv_info->toArray();
        }
        //待领取矿石
        $ad_w[] = ['user_id','=',$this->userId];
//        $ad_w[] = array('add_time','between',array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time()))));
        $ad_w[] = array('add_time','gt',time()-60*60);
        $is_jump_ad = 1;
        $jump_ad = Db::table('user_jump_ad')->where($ad_w)->find();

        if(!$jump_ad && $userInfo->is_certification == '1'){
            $user_info = User::getUserInfo($this->userId);
            $energy = $user_info['energy'];
            $product_candy_second = conf_m::getValue('product_candy_second');
            $product_max_hours = conf_m::getValue('product_max_hours');
            $product_receive_min_hours = conf_m::getValue('product_receive_min_hours');
            $product_receive_decimal_num = conf_m::getValue('product_receive_decimal_num');
            $product_receive_switch = conf_m::getValue('product_receive_switch');

            $pro_income_record_m = new ProductIncomeRecord();

            /*我瓜分的收益*/
            $last_record_where['user_id'] = $this->userId;
            $last_record = $pro_income_record_m->getLastRecord($last_record_where);

            /*间隔时间*/
            $interval_hours = $product_receive_min_hours;
            $accumulate_hours = $product_max_hours;
            $interval_second = $interval_hours * 3600;
            $accumulate_second = $accumulate_hours *3600;

            /*已过有效秒数*/
            $during_second = 1;

            $xc_second = time() - $last_record['add_time'];
            if($last_record){
                if($xc_second >= $accumulate_second){
                    $during_second = $accumulate_second;
                }else{
                    $during_second = $xc_second;
                }
            }else{
                $x = time()-$user_info['register_time'];
                $during_second = $x;

                if($x>=$accumulate_second){
                    $during_second = $accumulate_second;
                }else{
                }
            }
            if($product_receive_switch == 0){
                $during_second = 0;
            }
            $reward_num = $energy * $product_candy_second;
            $condy_sum_num = $during_second * $reward_num;
            $condy_sum_num = set_number($condy_sum_num,5);
            if($condy_sum_num > 0){
                $is_jump_ad = 0;
                Db::table('user_jump_ad')->insert(array(
                    'user_id'=>$this->userId,
                    'add_time'=>time()
                ));
            }
        }

        return $this->fetch('memberinfo', [
            'list' => $userInfo,
            'freeze' => $freeze,
            'market_money_num' =>$market_money_num,
            'is_read_list' =>$is_read_list,
            'parent_info' => $parent_info,
            'no_read_post'=>$no_read_post,
            'no_read_post1'=>$no_read_post1,
            'adv_info'=>$adv_info,
            'is_jump_ad'=>$is_jump_ad,
            'condy_sum_num'=>$condy_sum_num?$condy_sum_num:'',
        ]);
    }

    /**
     * 设置页面
     */
    public function set()
    {
        //获取缓存用户详细信息
         $identity = new Identity();
         $identity->delCache($this->userId);
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);

        return $this->fetch('set', ["list" => $userInfo]);
    }

    /**
     * 关于
     */
    public function about()
    {
        return $this->fetch("about");
    }

    /**
     * 修改密码页面
     */
    public function password()
    {
        $identify = new Identity();
        $userInfo = $identify->getUserInfo();

        $rand_str = getRandStr();
        $token = Jwt::genToken($rand_str);
        cache_1::set($rand_str,$rand_str,10*60);
        return $this->fetch("password",array('list'=> $this->userInfo,'token'=>$token));
    }
    /**
     * 修改资金密码页面
     */
    public function safepassword_view()
    {
        $identify = new Identity();
        $userInfo = $identify->getUserInfo();

        return $this->fetch("safepassword",array('list'=> $userInfo));
    }
    /**
     * 修改资金密码页面by短信
     */
    public function safepasswordByCode()
    {
        $identify = new Identity();
        $userInfo = $identify->getUserInfo();
        $rand_str = getRandStr();
        $token = Jwt::genToken($rand_str);
        cache_1::set($rand_str,$rand_str,10*60);
        return $this->fetch("safepasswordByCode",array('list'=> $this->userInfo,'token'=>$token));
    }
    /**
     * 联盟
     */
    public function union()
    {
        $userInfo = User::where('id', $this->userId)->find();

        //获得直推会员
        $userList = $userInfo->getChilds($this->userId);
        return $this->fetch('union', [
                "list" => $userInfo,
                "userList" => $userList
            ]
        );
    }

    /**
     * 修改密码
     */
    public function updatePassword(Request $request)
    {
        $validate = $this->validate($request->post(), '\app\index\validate\PasswordForm');

        if ($validate !== true) {
            return json(['code' => 1, 'message' => $validate]);
        }

        $oldPassword = $request->post('old_pwd');
        $user = User::where('id', $this->userId)->find();
        $service = new \app\common\service\Users\Service();
        $result = $service->checkPassword($oldPassword, $user);

        if (!$result) {
            return json(['code' => 1, 'message' => '原密码输入错误']);
        }
        $new_pwd = $request->post('new_pwd');
//        $code = $request->post('code');
//        if(!$code){
//            return json(['code'=>1,'message'=>'验证码不能空']);
//        }
        if (strlen($new_pwd) < 8) {
            return json(['code' => 1, 'message' => '密码长度至少8位']);
        }
//        $form = new RegisterForm();
//        if (!$form->checkChange($request->post('code'), $request->post('mobile'))) {
//            return json(['code' => 1, 'message' => '验证码输入错误']);
//        }
        //修改
        $user->password = $service->getPassword($request->post('new_pwd'));

        if ($user->save() === false) {
            return json(['code' => 1, 'message' => '修改失败']);
        }
        Db::table('login_monitor')->where(array('user_id'=>$this->userId))->delete();

        return json(['code' => 0, 'message' => '修改成功','toUrl'=>url('member/logout')]);
    }
    /*
     *  公告
     */
    public function post(Request $request){

        $category = $request->get('category');
        $article = new \app\index\model\Article();
        $articleList = $article->getArticleList($category);

        $w = array(
            'user_id'=>$this->userId,
            'is_read'=>0,
            'cate_id'=>$category
        );
        UserArticleRecord::where($w)->update(array('is_read'=>1,'add_time'=>time()));
        return $this->fetch('post', ["list" => $articleList,'total'=>count($articleList),'category'=>$category]);

    }
    /**
     * 新手解答
     */
    public function articleList()
    {
        //获取缓存用户详细信息
        $article = new \app\index\model\Article();
        $articleList = $article->getArticleList(2);
        return $this->fetch('articleList', ["list" => $articleList]);
    }

    /**
     * 问题留言
     */
    public function submitMsg(Request $request)
    {
        //获取缓存用户详细信息
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);

        //查询是否今日已提交反馈

        $td_sub_msg_where[] = ['user_id', '=', $this->userId];
        $td_sub_msg_where[] = ['create_time', 'between', array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time())))];

//        $td_sub_msg = \app\common\entity\Message::where($td_sub_msg_where)->select();
        $td_sub_msg = \app\common\entity\Message::where($td_sub_msg_where)->find();

        if($td_sub_msg){
            return json(['code' => 1, 'message' => '今天已反馈']);
        }
        //内容
        $data['content'] = $request->post("content");
        $data['create_time'] = time();
        $data['user_id'] = $this->userId;


        $res = \app\common\entity\Message::insert($data);
        if ($res) {
            return json(['code' => 0, 'message' => '提交成功', 'toUrl' => url('member/message')]);
        } else {
            return json(['code' => 1, 'message' => '提交失败']);
        }
    }

    /**
     * 客服页面
     */
    public function message()
    {
        $entity = \app\common\entity\Message::field('m.*, u.nick_name, u.avatar')
            ->alias("m")
            ->leftJoin("user u", 'm.user_id = u.id')
            ->where('m.user_id', $this->userId)
            ->order('m.create_time', 'desc')
            ->select();

        \app\common\entity\Message::update(array('is_read'=>1),['user_id'=>$this->userId]);
        return $this->fetch("message", ['list' => $entity]);
    }

    /**
     * 实名认证
     */
    public function certification()
    {
        //获取缓存用户详细信息 
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);

        return $this->fetch("certification", ['list' => $userInfo]);
    }

    /**
     * 实名认证下一步
     */
    public function lastreal(Request $request)
    {
        $data['real_name'] = $request->get("real_name");
        $data['card_id'] = $request->get("card_id");

        if (!$data['real_name'] || !$data['card_id']) {
            $this->error("请输入姓名和身份证号！！");
        }

        //获取缓存用户详细信息 
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);

        return $this->fetch("lastreal", ['list' => $userInfo, "data" => $data]);
    }

    /**
     * 支付宝
     */
    public function zfb()
    {
        //获取缓存用户详细信息 
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);
        $rand_str = getRandStr();
        $token = Jwt::genToken($rand_str);
        cache_1::set($rand_str,$rand_str,10*60);
        return $this->fetch("zfb", ['list' => $userInfo,'token'=>$token]);
    }

    /**
     * 微信
     */
    public function wx()
    {
        //获取缓存用户详细信息 
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);

        return $this->fetch("wx", ['list' => $userInfo]);
    }

    /**
     * 添加银行卡
     */
    public function card()
    {
        //获取缓存用户详细信息
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);

        return $this->fetch("card", ['list' => $userInfo]);
    }

    /**
     * 修改个人信息
     */
    public function updateUser(Request $request)
    {
        //获取缓存用户详细信息
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($this->userId);

        $user = new Service();

        $data = array();

        $card = $request->post("card");//银行卡号
        if ($card) {
            if ($user->checkMsg("card", $card, $userInfo->user_id)) {
                return json(['code' => 1, 'message' => '该银行卡号已经被绑定了']);
            } else {
                $data['card'] = $card;
            }
        }
        $card_name = $request->post("card_name");//开户行
        if ($card_name) {
            $data['card_name'] = $card_name;
        }
        $field_type = $request->post("field_type");//支付宝
        if($field_type == 'zfb'){
            $zfb = $request->post("zfb");//支付宝
            $confirm_zfb = $request->post("confirm_zfb");//支付宝
            $trad_pwd = $request->post("trad_pwd");//资金密码验证

            if(!$zfb){
                return json(['code'=>1,'message'=>'支付宝账号不能空']);
            }
            if(!$trad_pwd){
                return json(['code'=>1,'message'=>'资金密码不能空']);
            }
            if($confirm_zfb != $zfb){
                return json(['code'=>1,'message'=>'两次支付宝账号不一样']);
            }

            if ($user->checkMsg("zfb", $zfb, $userInfo->user_id)) {
                return json(['code' => 1, 'message' => '该支付宝号已经被绑定了']);
            }
            $user_info = User::where('id', $this->userId)->find();

            $result = $user->checkSafePassword($trad_pwd, $user_info);

            if (!$result) {
                return json(['code' => 1, 'message' => '资金密码输入错误']);
            }
//            $code = $request->post('code');
//            if(!$code){
//                return json(['code'=>1,'message'=>'验证码不能空']);
//            }
//            $form = new RegisterForm();
//            if (!$form->checkChange($request->post('code'), $request->post('mobile'))) {
//                return json(['code' => 1, 'message' => '验证码输入错误']);
//            }
            $data['zfb'] = $zfb;
        }

        $zfb_image_url = $request->post("zfb_image_url");

        if ($zfb_image_url) {
            $data['zfb_image_url'] = $zfb_image_url;
        }
        $wx = $request->post("wx");//微信
        if ($wx) {
            if ($user->checkMsg("wx", $wx, $userInfo->user_id)) {
                return json(['code' => 1, 'message' => '该微信号已经被绑定了']);
            } else {
                $data['wx'] = $wx;
            }
        }
        $wx_image_url = $request->post("wx_image_url");
        if ($wx_image_url) {
            $data['wx_image_url'] = $wx_image_url;
        }
        $real_name = $request->post("real_name");//真实姓名
        if ($real_name) {
            $data['real_name'] = $real_name;
        }
        $card_id = $request->post("card_id");//身份证号
        if ($card_id) {
            $data['card_id'] = $card_id;
        }
        $card_left = $request->post("card_left");//身份证反面
        if ($card_left) {
            $data['card_left'] = $card_left;
        }
        $card_right = $request->post("card_right");//身份证反面
        if ($card_right) {
            $data['card_right'] = $card_right;
        }
        $avatar = $request->post("avatar");//头像
        if ($avatar) {
            $data['avatar'] = $avatar;
        }

        $res = \app\common\entity\User::where('id', $this->userId)->update($data);
        // dump(\app\common\entity\User::getLastsql());die;
        if ($res) {
            //更新缓存
            $identity->delCache($this->userId);
            return json(['code' => 0, 'message' => '修改成功', 'toUrl' => url('member/index')]);
        } else {
            return json(['code' => 1, 'message' => '修改失败']);
        }
    }

    public function assets(){
        $is_fill_r = UserStatistics::fillTradeReq($this->userId);

        $user_statistics = UserStatistics::getByUserId($this->userId);
        $is_fill = $is_fill_r['ret'];
        $ks = 0;
        if($is_fill == 1){
            $ks = $is_fill_r['ks'];
        }
        $userInfo = User::where('id', $this->userId)->find();
        $userInfo['show_magic'] = get_number($userInfo['magic'],3);
        $market_money_num = conf_m::getValue('market_money_rate') * $userInfo['magic'];
        $market_money_num = set_number($market_money_num,2);

        $data = array(
            'user_statistics' => $user_statistics,
            'user_info' => $userInfo,
            'ks' => $ks,
            'market_money_num' => $market_money_num,
        );
        return $this->fetch('assets',$data);
    }
    public function assets_exp(){
        return $this->fetch('assets_exp');
    }
    /**
     * 魔盒
     */
    public function magicbox()
    {
        $user_product = new UserProduct();
        $magicList = $user_product->getBox($this->userId);
        return $this->fetch("magicbox", ["magicList" => $magicList]);
    }

    /**
     * 清除缓存
     */
    public function delCache()
    {
        $identity = new Identity();
        $identity->delCache($this->userId);
    }

    /**
     * 登录到交易市场
     */
    public function login(Request $request)
    {
        if ($request->isPost()) {
            $password = $request->post('password');
            if (!$password) {
                return json(['code' => 1, 'message' => '请输入密码']);
            }
            $auth = new Auth();
            if (!$auth->check($password)) {
                return json(['code' => 1, 'message' => '密码错误']);
            }
            $url = Session::get('prev_url');
            Session::delete('prev_url');
            return json(['code' => 0, 'message' => '登录成功', 'toUrl' => $url]);
        }
        Session::set('prev_url', !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('market/index'));
        return $this->fetch('login');
    }

    /**
     * 账单
     */
    public function magicloglist(Request $request)
    {
        $type = $request->get("type", '');
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $model = new UserMagicLog();
        $list = $model->magiclogdata($type, $this->userId);

        $get_td_data = array();
        if($list){
            $get_td_data = \app\index\model\User::get_td_data($list,'create_time','magic',2);
        }

        $sum_num_arr = $get_td_data['sum_num_arr'];
        $p = ($page-1) * $limit;
        $sum_num_arr1 = array_slice($sum_num_arr,$p,$limit);

        if ($request->isAjax()) {
            $data['content'] = $this->fetch('magicloglist_ajax',array('list'=>$sum_num_arr1));
            $data['count'] = array(
                'totalRows' => count($sum_num_arr),
                'listRows' => 8
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('magicloglist',['list'=>$sum_num_arr1 ]);
        }
    }

    /*流水详情*/
    public function magiclogdetail(Request $request){
        $page = $request->get('p', 1);
        $times = $request->get('times');
        $limit = $request->get('limit', 8);
        $model = new UserMagicLog();
         $list = $model->magiclogdata('', $this->userId);
//        $list = $model->magiclogdata('', 32698);
        $num_arr = array();
        $get_td_data = array();
        if($list){
            $get_td_data = \app\index\model\User::get_td_data($list,'create_time','magic',2);
        }
        $num_arr = $get_td_data['num_arr'];
        $td_num_arr = $num_arr[$times];
        $p = ($page-1)*$limit;
        $td_num_arr1 = array_slice($td_num_arr,$p,$limit);

        if ($request->isAjax()) {

            $data['content'] = $this->fetch('magiclogdetail_ajax',array('list'=>$td_num_arr1));
            $data['count'] = array(
                'totalRows' => count($td_num_arr),
                'listRows' => 8
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('magiclogdetail',['list'=>$td_num_arr1,'times'=> $times]);
        }
    }
    /**
     * 退出登录
     */
    public function logout()
    {
        $service = new Identity();
        $service->logout();

        $this->redirect('publics/index');
    }

    /**
     * 推广
     */
    public function spread()
    {
        //获取当前用户的推广码
        //$path = url('qrcode');
        $code = UserInviteCode::where('user_id', $this->userId)->value('invite_code');

        $bg_img = Sl::where('types', 3)->select();

//
//        $fileName = Env::get('app_path') . '../public/code/qrcode_' . $code . '.png';
//
//        if (!file_exists($fileName)) {
//            $path = $this->qrcode($code);
//
//            ob_clean();
//            $editor = Grafika::createEditor();
//
//            $background = Env::get('app_path') . '../public/static/img/zhaomubg.png';
//
//            $editor->open($image1, $background);
//            $editor->text($image1, $code, 30, 520, 1590, new Color('#ffffff'), '', 0);
//            $editor->open($image2, $path);
//            $editor->blend($image1, $image2, 'normal', 0.9, 'center');
//            $editor->save($image1, Env::get('app_path') . '../public/code/qrcode_' . $code . '.png');
//        }

        return $this->fetch('spread', [
//            'path' => '/code/qrcode_' . $code . '.png'
            'invite_code' => $code,
            'bg_img' => $bg_img,
        ]);
    }

    protected function qrcode($code)
    {
        //$code = UserInviteCode::where('user_id', $this->userId)->value('invite_code');
        $path = Env::get('app_path') . '../public/code/' . $code . '.png';

        if (!file_exists($path)) {
            ob_clean();
            $url = url('publics/register', ['code' => $code], 'html', true);
            $qrCode = new \Endroid\QrCode\QrCode();

            $qrCode->setText($url);
            $qrCode->setSize(300);
            $qrCode->setWriterByName('png');
            $qrCode->setMargin(10);
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 100]);
            //$qrCode->setLabel('Scan the code', 16, __DIR__.'/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER);
            $qrCode->setLogoPath(Env::get('app_path') . '../public/static/img/logo5.png');
            $qrCode->setLogoWidth(200);
            $qrCode->setValidateResult(false);

            header('Content-Type: ' . $qrCode->getContentType());
            $content = $qrCode->writeString();

            $path = Env::get('app_path') . '../public/code/' . $code . '.png';

            file_put_contents($path, $content);
        }

        return $path;

    }

    public function qrcode_img(){

        $level = 'M';
        $size = 5;
        $code = UserInviteCode::where('user_id', $this->userId)->value('invite_code');
        $invite_url = "https://".$_SERVER['SERVER_NAME'].'/index/publics/register.html?code='.$code;

        QRcode::png($invite_url ,false, $level, $size, 2);
    }

    public function safepassword(Request $request)
    {
        if ($request->isPost()) {
            $validate = $this->validate($request->post(), '\app\index\validate\PasswordForm');

            if ($validate !== true) {
                return json(['code' => 1, 'message' => $validate]);
            }

            //判断原密码是否相等
            $oldPassword = $request->post('old_pwd');
            $user = User::where('id', $this->userId)->find();
            $service = new \app\common\service\Users\Service();
            $result = $service->checkSafePassword($oldPassword, $user);

            if (!$result) {
                return json(['code' => 1, 'message' => '原密码输入错误']);
            }
//            $code = $request->post('code');
//            if(!$code){
//                return json(['code'=>1,'message'=>'验证码不能空']);
//            }
//            $form = new RegisterForm();
//            if (!$form->checkChange($request->post('code'), $request->post('mobile'))) {
//                return json(['code' => 1, 'message' => '验证码输入错误']);
//            }
            //修改
            $user->trad_password = $service->getPassword($request->post('new_pwd'));

            if (!$user->save()) {
                return json(['code' => 1, 'message' => '修改失败']);
            }

            return json(['code' => 0, 'message' => '修改成功','toUrl'=>url('set')]);

        }
        return $this->fetch('safepassword');
    }
    public function safepasswordBy(Request $request)
    {
        if ($request->isPost()) {
            $validate = $this->validate($request->post(), '\app\index\validate\TradePasswordForm');

            if ($validate !== true) {
                return json(['code' => 1, 'message' => $validate]);
            }

            //判断原密码是否相等
//            $oldPassword = $request->post('old_pwd');
            $user = User::where('id', $this->userId)->find();
            $service = new \app\common\service\Users\Service();
//            $result = $service->checkSafePassword($oldPassword, $user);
//
//            if (!$result) {
//                return json(['code' => 1, 'message' => '原密码输入错误']);
//            }
            $code = $request->post('code');
            if(!$code){
                return json(['code'=>1,'message'=>'验证码不能空']);
            }
            $form = new RegisterForm();
            if (!$form->checkChange($request->post('code'), $request->post('mobile'))) {
                return json(['code' => 1, 'message' => '验证码输入错误']);
            }
            //修改
            $user->trad_password = $service->getPassword($request->post('new_pwd'));

            if (!$user->save()) {
                return json(['code' => 1, 'message' => '修改失败']);
            }
            $this->delCache();
            return json(['code' => 0, 'message' => '修改成功','toUrl'=>url('set')]);

        }
        return $this->fetch('safepassword');
    }
}