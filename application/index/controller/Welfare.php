<?php
/**
 * Created by PhpStorm.
 * User: hmlwan521
 * Date: 2020/4/28
 * Time: 下午11:01
 */

namespace app\index\controller;


use app\common\entity\AdvConfig;
use app\common\entity\Config;
use app\common\entity\FlashExchangeOrders;
use app\common\entity\Orders;
use app\common\entity\payRecord;
use app\common\entity\Sl;
use app\common\entity\task;
use app\common\entity\UserMagicLog;
use app\common\entity\Turntable;
use app\common\entity\User as User_m;
use app\common\entity\UserStatistics;
use app\common\entity\UserStatisticsLog;
use app\common\service\Market\Auth;
use app\index\model\User;
use think\Db;
use think\Loader;
use think\Request;
use phpqrcode\QRcode;
use \think\Facade\Cache as cache_1;


class Welfare extends Base
{
    /**
     * @return mixed
     * 应用
     */
    public function index()
    {

        $sl_m = new Sl();
        $sl_list = $sl_m->where('types', 1)->select();
        //随机广告位
        $adv_info = AdvConfig::getOneById(array(),'rand()');
        if($adv_info){
            $adv_info = $adv_info->toArray();
        }
        return $this->fetch('index', ['sl_list' => $sl_list,  'adv_info'=>$adv_info]);
    }

    /**
     * @return mixed
     * 每日任务
     */
    public function task_daily()
    {

        //已完成挂买单数量
        $td_trade_where[] = array('create_time', 'between', array(strtotime(date("Y-m-d 0:0:0", time())), strtotime(date("Y-m-d 23:59:59", time()))));
        $td_trade_where[] = ['user_id', '=', $this->userId];
        $td_trade_where[] = ['types', '=', Orders::TYPE_BUY];

        $trade_info = Orders::where($td_trade_where)->select();
        $trade_nums = count($trade_info);
        //直推好友完成实名数量
        $invite_m = new  \app\common\entity\Invite;
        $td_where = array('add_time', 'between', array(strtotime(date("Y-m-d 0:0:0", time())), strtotime(date("Y-m-d 23:59:59", time()))));

        $td_cert_where[] = ['user_id', '=', $this->userId];
        $td_cert_where[] = ['is_cert', '=', 2];
        $td_cert_where[] = ['types', '=', 1];
        $td_cert_where[] = ['level', '=', 1];
        $td_cert_where[] = $td_where;
        $td_cert_list = $invite_m->getCommissionRecord($td_cert_where);
        $td_cert_nums = count($td_cert_list);

        //每日任务
        $task_m = new task();
        $map[] = ['status','=',1];
        $map[] = ['id','neq',1];
        $task_list = $task_m->getAllTask($map);

        foreach ($task_list as &$task_value) {

            $task_id = $task_value['id'];

            $reward_remark = $task_value['reward_remark'];
            $reward_remark_arr = explode("@", $reward_remark);
            $reward_rate = $task_value['reward_rate'];
            $reward_rate_arr = explode('@', $reward_rate);
            $complete_num = $task_value['complete_num'];
            $complete_num_arr = explode('@', $complete_num);
            $finish_where = array();
            //已完成记录
            $finish_where[] = ['user_id', '=', $this->userId];
            $finish_where[] = ['task_id', '=', $task_id];
            $finish_where[] = $td_where;

            $finish_record = $task_m->getTaskRecord($finish_where);

            $finish_num = $finish_record ? count($finish_record) : 0;
            $is_receive = 0;
            $is_all_receive = 0; //已领取完
            $cur_reward_rate = '';
            $cur_complete_num = '';

            if ($finish_num == count($complete_num_arr)) {
                $is_all_receive = 1;
                $cur_reward_remark = $reward_remark_arr[$finish_num-1];
            } else {
                $cur_reward_rate = $reward_rate_arr[$finish_num];
                $cur_complete_num = $complete_num_arr[$finish_num];
                $cur_reward_remark = $reward_remark_arr[$finish_num];
                switch ($task_id) {
                    case '1':  //每日签到
                        $is_receive = 1;
                        break;
                    case '2':  //挂买单
                        if ($trade_nums >= $cur_complete_num) {
                            $is_receive = 1;
                        }
                        break;
                    case '3':  //推广有效好友
                        if ($td_cert_nums >= $cur_complete_num) {
                            $is_receive = 1;
                        }
                        break;
                }
            }
            $task_value['is_receive'] = $is_receive;
            $task_value['is_all_receive'] = $is_all_receive;
            $task_value['cur_reward_rate'] = $cur_reward_rate;
            $task_value['cur_complete_num'] = $cur_complete_num;
            $task_value['cur_reward_remark'] = $cur_reward_remark;

        }

        $data_view = array(
            'task_list' => $task_list,
            'trade_nums' => $trade_nums,
            'td_cert_nums' => $td_cert_nums
        );
        return $this->fetch('task_daily', $data_view);
    }

    /**
     * 领取任务
     */
    public function do_task(Request $request)
    {
        $task_m = new task();
        $task_infos = $task_m->getAllTask()->toArray();

        $task_info = $task_infos[0];
        if (empty($task_infos) || $task_info['status'] != 1) {
            return json(['code' => 1, 'message' => '签到已关闭']);
        }
        $td_where = array('add_time', 'between', array(strtotime(date("Y-m-d 0:0:0", time())), strtotime(date("Y-m-d 23:59:59", time()))));
        $task_id = $task_info['id'];
        //已完成记录
        $finish_where[] = ['user_id', '=', $this->userId];
        $finish_where[] = ['task_id', '=', $task_id];
        $finish_where[] = $td_where;
        $finish_record = $task_m->getTaskRecord($finish_where);
        if($finish_record){
            return json(['code' => 1, 'message' => '今日已签到']);
        }
        //每日签到邀请下线才能领取开关(且实名)
        $invite_sub_switch = $task_info['invite_sub_switch'];
        if($invite_sub_switch == 1){
            $invite_m = new  \app\common\entity\Invite;
            $td_invite_where[] = ['user_id','=',$this->userId];
            $td_invite_where[] = ['level','=',1];
            $td_invite_where[] = ['is_cert','=',2];
            $td_invite_where[] = ['types','=',1];
            $td_invite_where[] = array('add_time','between',array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time()))));
            $invite_total = $invite_m->where($td_invite_where)->count();
            if($invite_total <= 0){
                return json(['code' => 1, 'message' => "今日未完成直推邀请任务<br>不能领取签到奖励"]);
            }
        }

        $reward_remark = $task_info['reward_remark'];
        $total_reward_num = '0.0';
        if($reward_remark){
            $reward_remark = explode("@",$reward_remark);
            $total_reward_num = rand($reward_remark[0]*10,$reward_remark[1]*10);
            $total_reward_num = set_number($total_reward_num/10,1);
        }

        //明细记录
        $magiclog_m = new UserMagicLog();
        $record_data = array(
            'magic' => $total_reward_num,
            'type' => 9,
            'remark' => "每日签到奖励{$total_reward_num}云链"
        );
        $record_r = $magiclog_m->changeUserMagic($this->userId, $record_data, 1);
        if (!$record_r) {
            return json(['code' => 1, 'message' => '领取失败']);
        }
        //记录任务
        $task_record_data = array(
            'user_id' => $this->userId,
            'task_id' => $task_id,
            'reward_rate' => $task_info['reward_rate'],
            'complete_num' => $task_info['complete_num'],
            'reward_num' => $total_reward_num,
            'add_time' => time(),
            'total_reward_num' => $total_reward_num,
        );
        $task_m->saveTaskRecord($task_record_data);

        return json(['code' => 0, 'message' => '领取成功', 'toUrl' => 'task_daily','data'=>array('reward_num'=>$total_reward_num)]);
    }

    /**
     * @return mixed
     * 云链转盘
     */
    public function candy_turntable()
    {
        $turntable_luckdraw_num = Config::getValue('turntable_luckdraw_num');
        $turntable_luckdraw_energy = Config::getValue('turntable_luckdraw_energy');
        $turntable_luckdraw_consume_candy = Config::getValue('turntable_luckdraw_consume_candy');
        $turntable_luckdraw_switch = Config::getValue('turntable_luckdraw_switch');
        $turn_w['status'] = 1;
        $list = Turntable::getAll($turn_w, 12)->toArray();

        $list = _insert_array($list, 5, array('type' => 1));
        $list = _insert_array($list, 6, array('type' => 1));
        $list = _insert_array($list, 9, array('type' => 2));
        $list = _insert_array($list, 10, array('type' => 1));
        $list = _insert_array($list, 11, array('type' => 1));

        //云链数量
        $user_info = User_m::getUserInfo($this->userId);

        $candy_num = $user_info['magic'] > 0 ? set_number($user_info['magic'], 0) : 0;
        //今日已抽奖记录
        $td_where = array('add_time', 'between', array(strtotime(date("Y-m-d 0:0:0", time())), strtotime(date("Y-m-d 23:59:59", time()))));
        $td_record_w[] = ['user_id', '=', $this->userId];
        $td_record_w[] = $td_where;
        $turntable_record = Db::table('turntable_record')->where($td_record_w)->order('add_time desc')->select();

        $turntable_nums = count($turntable_record);

        $left_nums = $turntable_luckdraw_num - $turntable_nums;
        if ($left_nums <= 0) {
            $left_nums = 0;
        }
        $is_over_energy = 1;
        if( $user_info['energy'] < $turntable_luckdraw_energy){
            $is_over_energy = 0;
        }
        $do_times = cache_1::store('File')->get('do_times');
        $phone_rand_reward = Config::getValue('phone_rand_reward');
        $phone_rand_reward_str = 6;
        if($phone_rand_reward){
            $phone_rand_reward_arr = explode("@",$phone_rand_reward);
            $phone_rand_reward_str = $phone_rand_reward_arr[array_rand($phone_rand_reward_arr)];
        }
        $data_view = array(
            'list' => $list,
            'turntable_luckdraw_num' => $turntable_luckdraw_num,
            'turntable_luckdraw_energy' => $turntable_luckdraw_energy,
            'turntable_luckdraw_consume_candy' => $turntable_luckdraw_consume_candy,
            'candy_num' => $candy_num,
            'turntable_nums' => $turntable_nums,
            'left_nums' => $left_nums,
            'is_over_energy' => $is_over_energy,
            'turntable_luckdraw_switch' => $turntable_luckdraw_switch,
            'energy' => $user_info['energy'],
            'timestamp'=>time(),
            'do_times'=>$do_times?$do_times:0,
            'rand_phone'=>yc_phone(randPhone()),
            'phone_rand_reward'=> $phone_rand_reward_str
        );
        return $this->fetch('candy_turntable', $data_view);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * 抽奖
     */
    public function do_luckdraw(Request $request)
    {
        $is_luck = $request->post('is_luck');
        if ($is_luck == '1') {
            return json(['code' => 2]);
        }
        $turntable_luckdraw_num = Config::getValue('turntable_luckdraw_num');
        $turntable_luckdraw_energy = Config::getValue('turntable_luckdraw_energy');
        $turntable_luckdraw_consume_candy = Config::getValue('turntable_luckdraw_consume_candy');
        $turntable_luckdraw_switch = Config::getValue('turntable_luckdraw_switch');
        if($turntable_luckdraw_switch == 0){
            return json(['code' => 1, 'message' => '抽奖已关闭']);
        }
        //云链数量
        $user_info = User_m::getUserInfo($this->userId);
        $candy_num = $user_info['magic'] ? set_number($user_info['magic'], 0) : 0;
        $energy = $user_info['energy'] ? set_number($user_info['energy'], 0) : 0;
        if ($energy < $turntable_luckdraw_energy) {
            return json(['code' => 1, 'message' => '算力不足']);
        }
        $left_candy_nums = $candy_num - $turntable_luckdraw_consume_candy;
        if ($left_candy_nums < 0) {
            return json(['code' => 1, 'message' => '云链不足']);
        }
        //今日已抽奖记录
        $td_where = array('add_time', 'between', array(strtotime(date("Y-m-d 0:0:0", time())), strtotime(date("Y-m-d 23:59:59", time()))));
        $td_record_w[] = ['user_id', '=', $this->userId];
        $td_record_w[] = $td_where;
        $turntable_record = Db::table('turntable_record')->where($td_record_w)->order('add_time desc')->select();

        $turntable_nums = count($turntable_record);

        $left_nums = $turntable_luckdraw_num - $turntable_nums;
        if ($left_nums <= 0) {
            return json(['code' => 1, 'message' => '今日抽奖用完啦']);
        }
        if ($left_nums <= 0) {
            $left_nums = 0;
        }

        //随机一条配置
        $turntable_w['status'] = 1;
        $turntable_w['is_drawn'] = 1;
        $turntable_info = \app\common\entity\Turntable::where($turntable_w)
            ->order('rand()')->find();

        $turntable_num = isset($turntable_info['num']) ? $turntable_info['num'] : 0;
        if ($turntable_num <= 0) {
            return json(['code' => 1, 'message' => '未中奖']);
        }
        //减云链
//        $dec_r = User_m::setDecMagic($this->userId,$turntable_luckdraw_consume_candy);
//        if(!$dec_r){
//            return json(['code'=>1,'message'=>'云链不足']);
//        }
        //减云链明细记录
        $magiclog_m = new UserMagicLog();
        $dec_record_data = array(
            'magic' => $turntable_luckdraw_consume_candy,
            'type' => 14,
            'remark' => '转盘消耗' . $turntable_luckdraw_consume_candy . '云链'
        );

        $dec_record_r = $magiclog_m->changeUserMagic($this->userId, $dec_record_data, -1);
        if (!$dec_record_r) {
            return json(['code' => 1, 'message' => '云链不足']);
        }
        //加云链
//        $inc_r = User_m::setIncMagic($this->userId,$turntable_num);
//        if(!$inc_r){
//            return json(['code'=>1,'message'=>'抽奖失败']);
//        }
        //抽奖记录
        $turntabe_data = array(
            'user_id' => $this->userId,
            'num' => $turntable_num,
            'add_time' => time(),
        );
        Db::table('turntable_record')->insert($turntabe_data);
        //加云链明细记录

        $inc_record_data = array(
            'magic' => $turntable_num,
            'type' => 7,
            'remark' => '转盘奖励' . $turntable_num . '云链'
        );
        $inc_record_r = $magiclog_m->changeUserMagic($this->userId, $inc_record_data, 1);
        if (!$inc_record_r) {
            return json(['code' => 1, 'message' => '抽奖失败']);
        }
        $json_data = array(
            'code' => 0,
            'message' => '抽奖成功',
            'data' => array(
                'turntable_id' => $turntable_info['id'],
                'turntable_num' => $turntable_num,
                'cndy_img' => $turntable_info['img'],
                'cur_candy_num' => set_number($left_candy_nums, 0),
                'left_num' => ($left_nums - 1) > 0 ? ($left_nums - 1) : 0
            ),
        );
        cache_1::store('File')->set('do_times',time(),10);
        return json($json_data);
    }
    public function luckdraw_rule(){
        return $this->fetch('luckdraw_rule');
    }
    /**
     * @return mixed
     * 闪兑
     */
    public function flash_exchange()
    {
        $user_info = User_m::getUserInfo($this->userId);
        $magic = $user_info['magic'];
        $magic = set_number($magic, 0);
        //闪兑最大兑换数量 (与电能相关 x电能)
        $flash_exchange_max_rate = Config::getValue('flash_exchange_max_rate');
        $max_exchange = set_number($flash_exchange_max_rate*$user_info['energy'],0);

        //手续费
        $flash_exchange_rate = Config::getValue('flash_exchange_rate');
        //兑换比例
        $flash_exchange_bl = Config::getValue('flash_exchange_bl');
        //最小兑换额度
        $flash_exchange_min_num = Config::getValue('flash_exchange_min_num');

        //虚拟数据
        $flash_exchange_init_num = Config::getValue('flash_exchange_init_num');
        $flash_exchange_init_timestamp = Config::getValue('flash_exchange_init_timestamp');
        $flash_exchange_second_inc_num = Config::getValue('flash_exchange_second_inc_num');

        $left_times = time()-$flash_exchange_init_timestamp;
        $left_times = $left_times>0?$left_times:0;
        $fake_data = ($left_times*$flash_exchange_second_inc_num) + $flash_exchange_init_num;

        $show_fake_data = set_number($fake_data/10000,1);
        $data_view = array(
            'magic' => $magic,
            'user_info' => $user_info,
            'flash_exchange_rate' => $flash_exchange_rate,
            'flash_exchange_bl' => $flash_exchange_bl,
            'flash_exchange_min_num' => $flash_exchange_min_num,
            'max_exchange' => $max_exchange,
            'show_fake_data' => $show_fake_data,
        );

        return $this->fetch('flash_exchange', $data_view);
    }
    /**
     * 闪付卡操作
     */
    public function flash_exchange_op(Request $request){
        //闪兑开关
        $flash_exchange_switch = Config::getValue('flash_exchange_switch');
        //手续费比例
        $flash_exchange_rate = Config::getValue('flash_exchange_rate');
        //闪兑最低兑换数量
        $flash_exchange_min_num = Config::getValue('flash_exchange_min_num');
        //兑换比例
        $flash_exchange_bl = Config::getValue('flash_exchange_bl');
        //闪兑满足的电能限制
        $flash_exchange_min_energy = Config::getValue('flash_exchange_min_energy');
        //闪兑倍数
        $flash_exchange_bs = Config::getValue('flash_exchange_bs');

        //闪兑每日兑换次数
        $flash_exchange_daily_op_num = Config::getValue('flash_exchange_daily_op_num');
        //闪兑最大兑换数量 (与电能相关 x电能)
        $flash_exchange_max_rate = Config::getValue('flash_exchange_max_rate');

        //闪兑时间
        $flash_exchange_trade_time = Config::getValue('flash_exchange_trade_time');

        $flash_exchange_trade_time_arr = explode("@",$flash_exchange_trade_time);

        if($flash_exchange_switch != 1 || (date("G")< $flash_exchange_trade_time_arr[0]) || (date("G")> $flash_exchange_trade_time_arr[1])){
            return json(['code' => 1, 'message' => "暂时无法兑换"]);
        }
        $input_magic = $request->post('input_magic');
        $card = $request->post('card');

        $td_where[] = ['user_id','=',$this->userId];
        $td_where[] = array('add_time','between',array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time()))));
        $order_record = FlashExchangeOrders::where($td_where)->count();
        if($order_record >= $flash_exchange_daily_op_num){
            return json(['code' => 1, 'message' => "已超过每日兑换次数"]);
        }

        if(!$input_magic){
            return json(['code' => 1, 'message' => '请输入糖果数量']);
        }
        if(!$card){
            return json(['code' => 1, 'message' => '请输入银行卡号']);
        }
        $user_info = User_m::getUserInfo($this->userId);
        $magic = $user_info['magic'];
        if(!$user_info['zfb']){
            return json(['code' => 1, 'message' => '需绑定支付宝才能兑换','toUrl'=>"/index/member/zfb"]);
        }
        if($user_info['energy'] < $flash_exchange_min_energy){
            return json(['code' => 1, 'message' => "需要{$flash_exchange_min_energy}电能才能领取"]);
        }
        $max_exchange = set_number($flash_exchange_max_rate*$user_info['energy'],0);

        if($input_magic > $max_exchange){
            return json(['code' => 1, 'message' => "最多可以兑换{$max_exchange}数量"]);
        }
//        if($card == $user_info['card']){
//            return json(['code' => 1, 'message' => '银行卡不能与实名银行卡相同']);
//        }
        $input_magic = set_number($input_magic, 0);
        //手续费
        $fee = $input_magic * $flash_exchange_rate;
        $fee = set_number($fee,2);
        $total_magic = $fee +  $input_magic;
        if($magic < $total_magic){
            return json(['code' => 1, 'message' => '糖果不足']);
        }
        if($input_magic < $flash_exchange_min_num){
            return json(['code' => 1, 'message' => "最低{$flash_exchange_min_num}糖果起兑换"]);
        }
        if($flash_exchange_bs > 0){
            if(($input_magic % $flash_exchange_bs) != 0 ){
                return json(['code' => 1, 'message' => "需{$flash_exchange_bs}的倍数才能兑换"]);
            }
        }
        //是否满足闪兑条件
        $is_fill_r = UserStatistics::fillTradeReq($this->userId);
        if($is_fill_r['ret'] == 0){
            return json(['code' => 1, 'message' => $is_fill_r['msg'],'toUrl'=>'/index/member/assets']);
        }
        $ks = $is_fill_r['ks'];
        if($input_magic > $ks){
            return json(['code' => 1, 'message' => "最多可兑换{$ks}糖果"]);
        }
        $cny_num = set_number($input_magic/$flash_exchange_bl, 0);
        $sub_data = array(
            'mobile' => $user_info['mobile'],
            'user_id' => $this->userId,
            'real_name' => $user_info['real_name'],
            'card' => $card,
            'rate' => $flash_exchange_rate,
            'num' => $input_magic,
            'fee' => $fee,
            'total_num' => $total_magic,
            'cny_num' => $cny_num,
        );
        //明细 减云链
        $magiclog_m = new UserMagicLog();
        $dec_data = array(
            'magic' => $total_magic,
            'type' => 20,
            'remark' => '聚合兑换消耗'.$total_magic.'云链'
        );
        $dec_r = $magiclog_m->changeUserMagic($this->userId,$dec_data,-1);
        if(!$dec_r){
            return json(['code' => 1, 'message' => '兑换失败']);
        }
        $r = FlashExchangeOrders::addOrders($sub_data);
        if(!$r){
            return json(['code' => 1, 'message' => '兑换失败']);
        }
        //数据变动明细表
        UserStatisticsLog::changeUserMagic($this->userId,'sale_nums',array(
            'magic' => $input_magic,
            'type' => 3,
            'remark' => '闪兑消耗'.$input_magic.'云链',
        ),1);
        //减去兑换初始值
        $flash_exchange_init_num = Config::getValue('flash_exchange_init_num');
        $flash_exchange_init_num = $flash_exchange_init_num - $input_magic;
        Config::update(array('value'=>$flash_exchange_init_num),array('key'=>'flash_exchange_init_num'));
        return json(['code' => 0, 'message' => '兑换成功','toUrl'=>"flash_exchange_record"]);
    }
    /**
     * @return mixed
     * 兑换记录
     */
    public function flash_exchange_record(Request $request ){
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $get_data = FlashExchangeOrders::getList(array('user_id'=>$this->userId),$page,$limit);
        $list = $get_data['list'];
        $total = $get_data['total'];
        if ($request->isAjax()) {
            $data['content'] = $this->fetch('flash_exchange_record_ajax',array('list'=>$list));
            $data['count'] = array(
                'totalRows' => $total,
                'listRows' => $limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('flash_exchange_record',['list'=>$list]);

        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 兑换详情
     */
    public function flash_exchange_detail(Request $request){
        $id = $request->get('id');

        $info = FlashExchangeOrders::getInfoById($id);
        if(!$id || !$info){
            return $this->fetch('/publics/404');
        }
        return $this->fetch('flash_exchange_detail',['info'=>$info]);

    }

    /**
     * @return mixed
     * 借得到
     */
    public function borrow()
    {
        $user_info = User_m::getUserInfo($this->userId);

        $energy = $user_info['energy'];

        return $this->fetch('borrow', ['energy' => $energy]);
    }

    /**
     * @return mixed
     * 闪付卡
     */
    public function flash_pay_card()
    {
        $user_info = User_m::getUserInfo($this->userId);
        $energy = $user_info['energy'];

        return $this->fetch('flash_pay_card', ['energy' => $energy]);
    }



    /**
     * @return mixed
     * 收款
     */
    public function collect()
    {
        $phone = User_m::getUserPhone($this->userId);

        return $this->fetch('collect',['phone'=>$phone]);
    }

    /**
     * @return mixed
     * 收款记录
     */
    public function collect_record(Request $request)
    {
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $where['user_id'] = $this->userId;
        $record_r = payRecord::getRecord($where)->toArray();

        $p = ($page-1)*$limit;
        $record_r1 = array_slice($record_r,$p,$limit);

        if ($request->isAjax()) {

            $data['content'] = $this->fetch('collect_record_ajax',array('list'=>$record_r1));
            $data['count'] = array(
                'totalRows' => count($record_r),
                'listRows' => 8
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('collect_record',['list'=>$record_r1]);
        }
    }

    /**
     * @return mixed
     * 付款
     */
    public function pay()
    {

        //手续费
        $pay_rate = Config::getValue('pay_rate');
        $pay_min_num = Config::getValue('pay_min_num');

        $data_view = array(
            'pay_rate'=>$pay_rate,
            'pay_min_num'=>$pay_min_num,
        );
        return $this->fetch('pay',$data_view);
    }

    /**
     * 划款操作
     */
    public function do_pay(Request $request)
    {
        $user_m = new User_m();
        $pay_mobile = $request->post('pay_mobile');
        $pay_magic = $request->post('pay_magic');
        $trad_password = $request->post('trad_password');
        //划转开关
        $pay_switch = Config::getValue('pay_switch');
        if($pay_switch != 1){
            return json(['code'=>1,'message'=>'转账已关闭']);
        }
        if(!$pay_mobile){
            return json(['code'=>1,'message'=>'请输入收款是手机号']);
        }
        if(!$trad_password){
            return json(['code'=>1,'message'=>'请输入交易密码']);
        }
        if(!$pay_magic){
            return json(['code'=>1,'message'=>'请输入云链数量']);
        }
        //手续费
        $pay_rate = Config::getValue('pay_rate');
        //最低金额
        $pay_min_num = Config::getValue('pay_min_num');
        //划转倍数
        $pay_multi = Config::getValue('pay_multi');
        //最低算力
        $pay_min_energy = Config::getValue('pay_min_energy');

        if($pay_magic<$pay_min_num){
            return json(['code'=>1,'message'=>'最低划转金额'.$pay_min_num]);
        }
        if(($pay_magic % $pay_multi) != 0 ){
            return json(['code'=>1,'message'=>$pay_multi.'的倍数才能划转']);
        }

        $pay_info = $user_m->getInfoByMobile($pay_mobile);
        if(!$pay_info || $pay_info['is_certification'] != 1){
            return json(['code'=>1,'message'=>'该用户不存在/未实名']);
        }
        $auth = new Auth();
        if (!$auth->check($trad_password)) {
            return json(['code' => 1, 'message' => '交易密码错误']);
        }

        $info = $user_m->getUserInfo($this->userId);
        if($info['energy'] < $pay_min_energy){
            return json(['code'=>1,'message'=>"算力不低于{$pay_min_energy}"]);
        }
        if($info['magic'] < $pay_magic){
            return json(['code'=>1,'message'=>'云链不足']);
        }

        if($pay_mobile == $info['mobile']){
            return json(['code'=>1,'message'=>'不能向自己划拨云链']);
        }
        $sxf = $pay_magic*$pay_rate;
        $actual_magic =$pay_magic + $sxf;
        //减云链
        $magiclog_m = new UserMagicLog();
        $dec_data = array(
            'magic' => $actual_magic,
            'type' => 15,
            'remark' => '划拨'.$actual_magic.'云链'
        );
        $dec_r = $magiclog_m->changeUserMagic($this->userId,$dec_data,-1);
        if(!$dec_r){
            return json(['code'=>1,'message'=>'划拨失败']);
        }
        //加云链
        $inc_data = array(
            'magic' => $pay_magic,
            'type' => 10,
            'remark' => '收款'.$pay_magic.'云链'
        );
        $inc_r = $magiclog_m->changeUserMagic($pay_info['id'],$inc_data,1);
        if(!$inc_r){
            return json(['code'=>1,'message'=>'划拨失败']);
        }

        //转账记录
        $fkrecord_data = array(
            'user_id'=> $this->userId,
            'num' =>  $actual_magic,
            'pay_mobile' => $pay_mobile,
            'type' => 1,
            'sxf_num'=> $sxf,
            'pay_multi'=> $pay_multi,
            'actual_num'=> $pay_magic
        );

        $fkrecord_r = payRecord::saveRecord($fkrecord_data);
        //收款记录
        $skrecord_data = array(
            'user_id'=> $pay_info['id'],
            'num' => $pay_magic,
            'pay_mobile' => $info['mobile'],
            'type' => 2,
            'sxf_num'=> $sxf,
            'pay_multi'=> $pay_multi,
            'actual_num'=> $actual_magic
        );

        $skrecord_r = payRecord::saveRecord($skrecord_data);
        return json(['code'=>0,'message'=>'划拨成功','toUrl'=>'collect_record']);

    }
    public function qrcode_img(){

        $level = 'M';
        $size = 4;
        $phone = User_m::getUserPhone($this->userId);
        QRcode::png($phone ,false, $level, $size, 2);

    }
    public function get_user_name(Request $request){
        $user_m = new User_m();
        $pay_mobile = $request->post('pay_mobile');
        if(!$pay_mobile){
            return json(['code'=>1,'message'=>'请输入收款手机号']);
        }
        $pay_info = $user_m->getInfoByMobile($pay_mobile);

        if(!$pay_info || $pay_info['is_certification'] != 1){
            return json(['code'=>1,'message'=>'该用户不存在/未实名']);
        }
        $real_name= mb_convert_encoding($pay_info['real_name'],'UTF-8', 'UTF-8');

        $real_name = starReplace($real_name,0);

        return json(['code'=>0,'message'=>'ok','data'=>$real_name ]);

    }

    /**
     * 云链抽奖
     */
    public function candy_luckdraw(Request $request){

        $service = new \app\common\entity\ShopConfig();
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 6);
        $offset = ($page-1)*$limit;
        $total = $service->where(array('status'=>1))->count();
        $shop_list = $service->where(array('status'=>1))->order("rand()")->limit($offset,$limit)->select();
        $magic = \app\common\entity\User::where(array('id'=>$this->userId))->value('magic');
        if($request->isAjax()){
            $data['content'] = $this->fetch('candy_luckdraw_ajax',array('list'=>$shop_list,'magic'=>$magic));
            $data['count'] = array(
                'totalRows' => $total,
                'listRows' => $limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('candy_luckdraw',['list'=>$shop_list,'magic'=>$magic]);
        }
    }
    /**
     * 更多
     */
    public function welfare_more(){
        return $this->fetch('welfare_more');
    }

    /**
     * @return mixed
     * 火车票
     */
    public function train_station(){

        $times = time()+24*3600;
        $date = date("m月d日",$times);
        $week = get_week_name($times);
        $date_str = date("m/d/Y",$times);
        return $this->fetch('train_station',['date'=>$date,'week'=>$week,'date_str'=>$date_str]);

    }

    /**
     * 火车票详情
     */
    public function train_station_detail(Request $request){

        $date = $request->get('date');
        $start = $request->get('start');
        $end = $request->get('end');
        $week = $request->get('week');
        $date_str = $request->get('date_str');

        $date_str_timestamp = strtotime($date_str);
        if(!$date || !$start ||!$end ){
            $this->redirect('train_station');
        }
        $date_show = date("Y-m-d",$date_str_timestamp);
        $api_data = array(
            'date' => $date_show,
            'start' => $start,
            'end' => $end,
        );
        $is_back = 1;
        $back_week = get_week_name($date_str_timestamp-24*3600);
        $back_date = date("m月d日",$date_str_timestamp-24*3600);
        $back_date_str = date("m/d/Y",$date_str_timestamp-24*3600);
        if(strtotime(date("Y-m-d",time())) == $date_str_timestamp){
            $is_back = 0;
        }
        $go_week = get_week_name($date_str_timestamp+24*3600);
        $go_date = date("m月d日",$date_str_timestamp+24*3600);
        $go_date_str = date("m/d/Y",$date_str_timestamp+24*3600);
        $list = train_api($api_data);
      
        $show_data = array(
            'date'=>$date,
            'start'=>$start,
            'end'=>$end,
            'week'=>$week,
            'list'=>$list['list'],
            'is_back'=>$is_back,
            'back_week'=>$back_week,
            'back_date'=>$back_date,
            'back_date_str'=>$back_date_str,
            'go_week'=>$go_week,
            'go_date'=>$go_date,
            'go_date_str'=>$go_date_str,
        );
        return $this->fetch('train_station_detail',$show_data);
    }
}