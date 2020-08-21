<?php
namespace app\index\controller;

use app\admin\exception\AdminException;
use app\common\entity\AdvConfig;
use app\common\entity\Orders;
use app\common\entity\UserStatistics;
use app\common\entity\UserStatisticsLog;
use app\common\service\Product\Compute;
use think\Request;
use app\common\service\Users\Service;
use app\common\entity\User;
use app\common\entity\Config;
use app\common\entity\UserProduct;
use app\common\entity\ProductIncomeRecord;
use app\common\entity\UserMagicLog;
use app\common\entity\UserJewelLog;
use app\common\entity\Product as productModel;
use think\Db;

class Product extends Base
{
    public function index()
    {
        $user_info = User::getUserInfo($this->userId);
        $energy = $user_info['energy'];
        $product_candy_second = Config::getValue('product_candy_second');
        $product_max_hours = Config::getValue('product_max_hours');
        $product_receive_min_hours = Config::getValue('product_receive_min_hours');
        $product_receive_decimal_num = Config::getValue('product_receive_decimal_num');
        $product_receive_switch = Config::getValue('product_receive_switch');

        $pro_income_record_m = new ProductIncomeRecord();

        /*我瓜分的收益*/
        $last_record_where['user_id'] = $this->userId;
        $last_record = $pro_income_record_m->getLastRecord($last_record_where);

        /*间隔时间*/
        $interval_hours = $product_receive_min_hours;
        $accumulate_hours = $product_max_hours;
        $interval_second = $interval_hours * 3600;
        $accumulate_second = $accumulate_hours *3600;

        /*剩余秒数*/
        $left_second = 0;
        /*已过有效秒数*/
        $during_second = 0;

        $is_receive = 0;
        $xc_second = time() - $last_record['add_time'];

        if($last_record){

            if($xc_second >= $accumulate_second){
                $during_second = $accumulate_second;
                $left_second = 0;
            }else{
                $is_receive = 1;
                $during_second = $xc_second;
                $left_second = $accumulate_second - $xc_second;
            }
        }else{
            $x = time()-$user_info['register_time'];
            $during_second = $x;

            if($x>=$accumulate_second){
                $during_second = $accumulate_second;
                $left_second = 0;

            }else{
                $left_second = $accumulate_second - $x;
            }
            $is_receive = 1;
        }
        if($energy <= 0){
            $left_second = 0;
        }
        if($product_receive_switch == 0){
            $left_second = 0;
            $time_rate = 0;
            $during_second = 0;
        }
        $reward_num = $energy * $product_candy_second;
        $condy_sum_num = $during_second * $reward_num;
        $condy_sum_num = set_number($condy_sum_num,5);
        //累计时间08:00:00
        if($during_second>0){
            $show_during_second_arr = transToSecond($during_second);
            $show_during_second_str = $show_during_second_arr['a'].':'.$show_during_second_arr['b'].':'.$show_during_second_arr['c'];
            $show_during_second_str = "'{$show_during_second_str}'";
        }

        //进度
        $time_rate = set_number(($during_second/$accumulate_second),8);
        $time_rate = floor($time_rate*100);
        if($time_rate == 0 && $during_second>0){
            $time_rate = 1;
        }elseif ($time_rate>100 &&  $during_second>0){
            $time_rate = 100;
        }
        if($product_receive_switch == 0){
            $time_rate = 0;
        }
        //租用机器数量
        $p_where['user_id'] = $this->userId;
        $p_where['is_receive'] = 0;
        $p_list = productModel::getProductRecord($p_where);
        //随机广告位
        $adv_info = AdvConfig::getOneById(array(),'rand()');
        if($adv_info){
            $adv_info = $adv_info->toArray();
        }
        $list = array(
            'energy'=>$user_info['energy']?set_number($user_info['energy'],0):0,
            'magic' => $user_info['magic']?set_number($user_info['magic'],2):'0.00',
            'condy_sum_num' => $condy_sum_num,
            'left_second' => $left_second,
            'is_receive' => $is_receive,
            'decimal_num' => $product_receive_decimal_num,
            'reward_num' => $reward_num,
            'show_during_second_str' => $show_during_second_str?$show_during_second_str:"'00:00:00'",
            'time_rate' => $time_rate,
            'p_num' => count($p_list),
            'bj_k'=>rand(0,5),
            'adv_info'=>$adv_info
        );
        return $this->fetch('index', $list);
    }

    public function do_receive(){

        $user_info = User::getUserInfo($this->userId);
        $energy = $user_info['energy'];
        $product_candy_second = Config::getValue('product_candy_second');
        $product_max_hours = Config::getValue('product_max_hours');
        $product_receive_min_hours = Config::getValue('product_receive_min_hours');
        $product_receive_decimal_num = Config::getValue('product_receive_decimal_num');
        $product_receive_switch = Config::getValue('product_receive_switch');
        $product_receive_min_num = Config::getValue('product_receive_min_num');
        if($product_receive_switch == 0){
            return json(['code'=>1,'message'=>'空间领取收益已关闭']);
        }
        $factory_fun_switch = Config::getValue('factory_fun_switch');
        $factory_buy_num_range = Config::getValue('factory_buy_num_range');
        $factory_energy_range = Config::getValue('factory_energy_range');
        $factory_min_buy_range = Config::getValue('factory_min_buy_range');
//        $market_income_rate = Config::getValue('market_income_rate');

        $pro_income_record_m = new ProductIncomeRecord();
//        $is_reduce = $request->post('is_reduce');
        /*我瓜分的收益*/
        $last_record_where['user_id'] = $this->userId;
        $last_record = $pro_income_record_m->getLastRecord($last_record_where);

        /*间隔时间*/
        $interval_hours = $product_receive_min_hours;
        $accumulate_hours = $product_max_hours;
        $interval_second = $interval_hours * 3600;
        $accumulate_second = $accumulate_hours *3600;
        /*剩余秒数*/
        $left_second = 0;
        /*已过有效秒数*/
        $during_second = 0;

        $xc_second = time() - $last_record['add_time'];
        if($energy <= 0){
            return json(['code'=>1,'message'=>'算力不足没有矿场收益']);
        }
        if($last_record){
            if($xc_second < $interval_second) { /*小于间隔时间*/
                $next_receive_date = date("H:i",$last_record['next_receive_time']);
                return json(['code'=>1,'message'=>"未攒满1小时不可领取"]);
            }else{
                if($xc_second >= $accumulate_second){
                    $during_second = $accumulate_second;
                }else{
                    $during_second = $xc_second;
                }
            }
        }else{
            $x = time()-$user_info['register_time'];
            if($x < $interval_second) { /*小于间隔时间*/
                $next_receive_date = date("H:i",$last_record['next_receive_time']);
                return json(['code'=>1,'message'=>"未攒满1小时不可领取"]);
            }
            $during_second = $x;
            if($x>=$accumulate_second){
                $during_second = $accumulate_second;
            }
        }
        $reward_num = $energy * $product_candy_second;
        $condy_reward_num = $during_second * $reward_num ;
        if($condy_reward_num < $product_receive_min_num){
            return json(['code'=>1,'message'=>'收益未达到可领取数量']);
        }

        $receive_decimal_num = $product_receive_decimal_num;
        $receive_decimal_num1= $product_receive_decimal_num+1;
        $condy_reward_num = sprintf("%.{$receive_decimal_num}f",substr(sprintf("%.{$receive_decimal_num1}f", $condy_reward_num), 0, -1));;


        //功能开关为1  领取挂单单数限制
        if($factory_fun_switch == 1){
            //已完成挂买单数量
            $factory_min_buy_range_arr = explode('@',$factory_min_buy_range);

            $td_trade_where[] = ['user_id', '=', $this->userId];
            $td_trade_where[] = ['types', '=', Orders::TYPE_BUY];
            $td_trade_where[] = ['status', '=', Orders::STATUS_DEFAULT];
            $td_trade_where[] = ['number', 'between', array($factory_min_buy_range_arr[0],$factory_min_buy_range_arr[1])];

            $trade_info = Orders::where($td_trade_where)->select();

            $trade_nums = $trade_info?count($trade_info):0;
            $factory_energy_range_arr = explode('@',$factory_energy_range);
            $factory_buy_num_range_arr = explode('@',$factory_buy_num_range);

            if($factory_buy_num_range_arr){
                $buy_num = 0;
                foreach ($factory_energy_range_arr as $e_k => $e_val){
                    $e_val_arr = explode('-',$e_val);
                    if(!$e_val_arr[1] && $energy>=$e_val_arr[0]){
                        $buy_num = $factory_buy_num_range_arr[$e_k];
                    }elseif ($energy>=$e_val_arr[0] && $energy<=$e_val_arr[1]){
                        $buy_num = $factory_buy_num_range_arr[$e_k];
                    }
                }
                if($buy_num!= 0 && $trade_nums < $buy_num){
                    return json(['code'=>1,'message'=>"需挂{$buy_num}个100-500的小买单才能领取收益",'toUrl'=>url('deal/buy_list')]);
                }
//                if($buy_num!= 0 && ($trade_nums < $buy_num)){
//                    $old_condy_reward_num = $condy_reward_num;
//                    $condy_reward_num = $condy_reward_num * $market_income_rate;
//                    $condy_reward_num = set_number($condy_reward_num,$receive_decimal_num1);
//                    $left_nums = $buy_num - $trade_nums;
//                    if($is_reduce != 1){
//                        return json(['code'=>2,'message'=>"当前收益为{$condy_reward_num}云链，再挂多少个小买单将获得{$old_condy_reward_num}云链",'data'=>array(
//                            'market_income_rate'=>intval($market_income_rate*100),
//                            'condy_reward_num'=>$condy_reward_num,
//                            'left_nums'=>$left_nums>0?$left_nums:0,
//                            'old_condy_reward_num'=>$old_condy_reward_num,
//                        )]);
//                    }
//
//                }
            }
        }
        //收益明细记录
        $magic_data = array(
            'magic' => $condy_reward_num,
            'remark' => '空间收益奖励'.$condy_reward_num.'云链',
            'type'=>1,
        );
        $magic_log = UserMagicLog::changeUserMagic($this->userId,$magic_data,1);
        if(!$magic_log){
            return json(['code'=>1,'message'=>'领取失败']);
        }
        //收益记录
        $pro_record_data = array(
            'user_id' => $this->userId,
            'num' => $condy_reward_num,
            'accumulate_second' => $during_second,
            'next_receive_time' => time()+$interval_second,
            'enery_num'=>$energy
        );
        $pro_income_record_m->saveProductRecord($pro_record_data);

        $this->addProductInviteRecord($user_info,$condy_reward_num);
        return json(['code'=>0,'message'=>'领取成功','data'=>array(
            'condy_reward_num'=>$condy_reward_num
        )]);

    }

    /*下线收益*/
    public function addProductInviteRecord($user,$condy_reward_num){
        $user_m = new \app\common\entity\User();
        $invite_m = new \app\common\entity\Invite();
        $parent_info_1 = $user_m->getParentInfo($user['pid']);
        if($parent_info_1 && $parent_info_1['is_certification'] == 1){
            $product_receive_one_return_rate = Config::getValue('product_receive_one_return_rate');
            $num1 = $product_receive_one_return_rate * $condy_reward_num;
            $num1 = set_number($num1,2);
            $data1 = array(
                'user_id'=>$parent_info_1['id'],
                'sub_user_id'=>$user['id'],
                'content' => '下线收益返佣'.$num1.'云链',
                'num' => $num1,
                'type'=>3,
                'level'=>1,
                'is_cert'=>2,
            );
            $invite_m->saveRecord($data1);
            //收益记录
            $magic_data1 = array(
                'magic' => $num1,
                'remark' => '一级下线收益返佣'.$num1.'云链',
                'type'=>5,
            );
            $magic_log1 = UserMagicLog::changeUserMagic($parent_info_1['id'],$magic_data1,1);


            $parent_info_2 = $user_m->getParentInfo($parent_info_1['pid']);
            if($parent_info_2 && $parent_info_1['is_certification'] == 1){
                $product_receive_two_return_rate = Config::getValue('product_receive_two_return_rate');
                $num2 = $product_receive_two_return_rate * $condy_reward_num;
                $num2 = set_number($num2,2);

                $data2 = array(
                    'user_id'=>$parent_info_2['id'],
                    'sub_user_id'=>$user['id'],
                    'content' => '下线收益返佣'.$num2.'云链',
                    'num' => $num2,
                    'type'=>3,
                    'level'=>2,
                    'is_cert'=>2,
                );
                $invite_m->saveRecord($data2);
                //收益记录
                $magic_data2 = array(
                    'magic' => $num2,
                    'remark' => '二级下线收益返佣'.$num2.'云链',
                    'type'=>5,
                );
                $magic_log2 = UserMagicLog::changeUserMagic($parent_info_2['id'],$magic_data2,1);
            }
        }
    }

    /**
     * @return mixed
     * 领取记录
     */
    public function receive_record(Request $request){
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 12);
        $pro_income_record_m = new ProductIncomeRecord();
        $pro_income_map['user_id'] = $this->userId;
        $list = $pro_income_record_m->getRecord($pro_income_map)->toArray();

        $get_td_data = array();
        if($list){
            $get_td_data = \app\index\model\User::get_td_data($list,'add_time','num',4);
        }
        $sum_num_arr = $get_td_data['sum_num_arr'];
        $p = ($page-1)*$limit;
        $sum_num_arr1 = array_slice($sum_num_arr,$p,$limit);


        if ($request->isAjax()) {
            $data['content'] = $this->fetch('receive_record_ajax',array('list'=>$sum_num_arr1));
            $data['count'] = array(
                'totalRows' => count($sum_num_arr),
                'listRows' => 12
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('receive_record',['list'=>$sum_num_arr1 ]);
        }
    }
    /**
     * @return mixed
     * 领取明细
     */
    public function receive_detail(Request $request){
        $page = $request->get('p', 1);
        $times = $request->get('times');
        $limit = $request->get('limit', 12);
        $pro_income_record_m = new ProductIncomeRecord();
        $pro_income_map['user_id'] = $this->userId;
        $list = $pro_income_record_m->getRecord($pro_income_map)->toArray();
        $get_td_data = array();
        if($list){
            $get_td_data = \app\index\model\User::get_td_data($list,'add_time','num',4);
        }
        $num_arr = $get_td_data['num_arr'];
        $td_num_arr = $num_arr[$times];
        $p = ($page-1)*$limit;
        $td_num_arr1 = array_slice($td_num_arr,$p,$limit);

        if ($request->isAjax()) {

            $data['content'] = $this->fetch('receive_detail_ajax',array('list'=>$td_num_arr1));
            $data['count'] = array(
                'totalRows' => count($td_num_arr1),
                'listRows' => 12
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('receive_detail',['list'=>$td_num_arr1,'times'=> $times]);
        }
    }

    /**
     * 租用空间
     */
    public function machine_hire(){
        $where['status'] = 1;
        $list = productModel::getProductConf($where);

        return $this->fetch('machine_hire',['list'=>$list]);
    }
    /**
     *  租用空间
     */
    public function do_hire(Request $request){
        $product_id = $request->post('product_id');

        if(!$product_id){
            return json(['code'=>1,'message'=>'确少参数']);
        }
        $map['id'] = $product_id;
        $product_info = productModel::getOneProduct($map);
        if(!$product_info || $product_info['status'] != 1){
            return json(['code'=>1,'message'=>'该空间已被禁止租用']);
        }

        $usre_info = User::getUserInfo($this->userId);

        $candy_num = $product_info['candy_num'];
        if($usre_info['is_certification'] != 1){
            return json(['code'=>1,'message'=>'还未实名，请去实名','toUrl'=>'index/member/certification']);
        }
        if($usre_info['magic'] < $candy_num){
            return json(['code'=>1,'message'=>'云链不足']);
        }
        $running_machine_where['user_id'] = $this->userId;
        $running_machine_where['product_id'] = $product_id;
        $running_machine_where['is_receive'] = 0;
        $running_machine = productModel::getProductRecord($running_machine_where);
        if(count($running_machine)>= $product_info['hold_num']){
            return json(['code'=>1,'message'=>'已达到可持有数量']);
        }
        //收益记录
        $magic_data = array(
            'magic' => $candy_num,
            'remark' => '租用空间消耗'.$candy_num.'云链',
            'type'=>12,
        );
        $magic_log = UserMagicLog::changeUserMagic($this->userId,$magic_data,-1);
        if(!$magic_log){
            return json(['code'=>1,'message'=>'云链不足']);
        }
        //增加算力
        User::setIncEnergy($this->userId,$product_info['energy_num']);

        //租用记录
        $hire_data = array(
            'user_id' => $this->userId,
            'product_id' => $product_id,
            'period' =>  $product_info['period'],
            'candy_num' =>  $product_info['candy_num'],
            'return_candy_num' =>  $product_info['return_candy_num'],
            'energy_num' =>  $product_info['energy_num'],
        );
        $hire_r = productModel::addProductRecord($hire_data);

        //下线租用空间
        $this->addInviteRecord($usre_info,$product_info);
        //数据变动明细表
        UserStatisticsLog::changeUserMagic($this->userId,'buy_machine_num',array(
            'magic' => $candy_num,
            'type' => 2,
            'remark' => '消耗'.$candy_num.'云链购买机器',
        ));
        return json(['code'=>0,'message'=>'租用成功','toUrl'=>'index/product/running_machine']);
    }
    /*下线租用空间*/
    public function addInviteRecord($user,$product_info){
        $user_m = new \app\common\entity\User();
        $invite_m = new \app\common\entity\Invite();
        $parent_info_1 = $user_m->getParentInfo($user['pid']);
        if($parent_info_1 && $parent_info_1['is_certification'] == 1){
            $data1 = array(
                'user_id'=>$parent_info_1['id'],
                'sub_user_id'=>$user['id'],
                'content' => '一级下线租用空间返利'.$product_info['level_one_return_candy'].'云链',
                'num' => $product_info['level_one_return_candy'],
                'type'=>2,
                'level'=>1,
                'is_cert'=>2,
            );
            $invite_m->saveRecord($data1);
            //收益记录
            $magic_data1 = array(
                'magic' => $product_info['level_one_return_candy'],
                'remark' => '一级下线租用空间返利'.$product_info['level_one_return_candy'].'云链',
                'type'=>4,
            );
            $magic_log1 = UserMagicLog::changeUserMagic($parent_info_1['id'],$magic_data1,1);
            //统计
            UserStatistics::setFieldInc($parent_info_1['id'],'contribution_nums',$product_info['candy_num']);

            $parent_info_2 = $user_m->getParentInfo($parent_info_1['pid']);
            if($parent_info_2 && $parent_info_1['is_certification'] == 1){
                $data2 = array(
                    'user_id'=>$parent_info_2['id'],
                    'sub_user_id'=>$user['id'],
                    'content' => '二级下线租用空间奖励'.$product_info['level_two_return_candy'].'云链',
                    'num' => $product_info['level_two_return_candy'],
                    'type'=>2,
                    'level'=>2,
                    'is_cert'=>2,
                );
                $invite_m->saveRecord($data2);
                //收益记录
                $magic_data2 = array(
                    'magic' => $product_info['level_two_return_candy'],
                    'remark' => '二级下线租用空间返利'.$product_info['level_two_return_candy'].'云链',
                    'type'=>4,
                );
                $magic_log2 = UserMagicLog::changeUserMagic($parent_info_2['id'],$magic_data2,1);
                //统计
                UserStatistics::setFieldInc($parent_info_2['id'],'contribution_nums',$product_info['candy_num']);
            }
        }
    }
    /**
     * 运行空间
     */
    public function running_machine(){
        $where['user_id'] = $this->userId;
        $where['is_receive'] = 0;
        $list = productModel::getProductRecord($where);

        if($list){
            foreach ($list as $k=>&$value){
                $period = $value['period'] * 24 * 3600;
                $left_day = ($period + $value['add_time']) - time() ;

                if ($left_day > 0) {
                    $value['is_rehire'] = 2;
                    if($left_day > 24*3600){
                        $value['left_day'] = intval($left_day / (24 * 3600)).'天';
                    }elseif ($left_day>3600){
                        $value['left_day'] = intval($left_day / 3600).'小时';
                    }elseif ($left_day>60){
                        $value['left_day'] = intval($left_day / 60).'分钟';
                    }else{
                        $value['left_day'] = '1分钟';
                    }
                } else {//可退租
//                    $value['is_rehire'] = 1;
                    Compute::do_rehire($value);
                    unset($list[$k]);
                }
            }
        }

        return $this->fetch('running_machine',['list'=>$list]);
    }



    /**
     * 过期空间
     */
    public function expired_machine(){
        $where['user_id'] = $this->userId;
        $where['is_receive'] = 1;
        $list = productModel::getProductRecord($where,'receive_time desc');
        return $this->fetch('expired_machine',['list'=>$list]);
    }

    /**
     * @return mixed
     * 新手教程
     */
    public function newcomer_intro(){
        return $this->fetch('newcomer_intro');
    }
}