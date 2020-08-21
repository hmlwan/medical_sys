<?php
/**
 * Created by PhpStorm.
 * User: hmlwan521
 * Date: 2020/5/5
 * Time: 下午2:58
 */

namespace app\index\controller;

use app\common\entity\Config;
use app\common\entity\MarketPrice;
use app\common\entity\User;
use app\common\entity\Orders;
use app\common\entity\UserStatistics;
use Think\Request;


class Deal extends Base
{
    /*求购列表*/
    public function index(Request $request){

        $page   = $request->get('page', 1);
        $limit  = $request->get('limit', 4);
        $mobile = $request->get('mobile', '');

        $model = new \app\index\model\Market();

        $data  = $model->getList(Orders::TYPE_BUY, $this->userId, $page, $limit, $mobile);

        $order_status = User::where(array('id'=>$this->userId))->value('order_status');
        $trade_fine_num = Config::getValue('trade_fine_num');
        $match_rand = Config::getValue('match_rand');
        $match_rand_arr = explode('@',$match_rand);

        $match_rand_str = rand($match_rand_arr[0]?$match_rand_arr[0]*100:0,$match_rand_arr[1]*100);

        $match_rand_str = set_number($match_rand_str/100,1);

        $count = $data['total'];
        $list = $data['list'];

        $is_stop_deal = $this->is_stop_deal();
        //等待匹配云链总数量
        $default_order_sum_num = Orders::where(array('status'=>1))->sum('number');
        $default_order_sum_num = set_number(($default_order_sum_num*$match_rand_str)/1000,1).'万';
        //交易完成总数量
        $finish_order_sum_num = Orders::where(array('status'=>4))->sum('number');
        //是否满足闪兑条件
        $is_fill_r = UserStatistics::fillTradeReq($this->userId);
        $is_fill = $is_fill_r['ret'];
        $fill_sale = 0;
        if($is_fill == 1){
//            $user_statistics = UserStatistics::getByUserId($this->userId);
            $fill_sale = $is_fill_r['ks'];
        }
        $data_view = array(
            'is_stop_deal' => $is_stop_deal,
            'web_start_time' => Config::getValue('web_start_time'),
            'web_end_time' => Config::getValue('web_end_time'),
            'web_switch_market' => Config::getValue('web_switch_market'),
            'list'=>$list,
            'market_money_rate' => Config::getValue('market_money_rate'),
            'default_order_sum_num'=>$default_order_sum_num,
            'finish_order_sum_num'=>$finish_order_sum_num,
            'order_status'=>$order_status,
            'trade_fine_num'=>$trade_fine_num,
            'is_fill'=> $fill_sale>0?$is_fill:0,
            'fill_sale'=> $fill_sale,
        );

        if($request->isAjax()){
            $ajax_data['content'] = $this->fetch('index_ajax',$data_view);
            $ajax_data['data'] = $data_view;
            $ajax_data['count'] = array(
                'totalRows' => $count,
                'listRows' => $limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $ajax_data]);
        }else{
            return $this->fetch('index',$data_view);
        }
    }
    //是否休市
    public function is_stop_deal(){
        $web_start_time = Config::getValue('web_start_time');

        $web_end_time = Config::getValue('web_end_time');
        $is_stop_deal = 1;
        if((time()>=strtotime($web_start_time)) && time()<=strtotime($web_end_time)){
            $is_stop_deal = 0;
        }

        return $is_stop_deal;
    }
    /**
     * @return mixed
     * 求购列表
     */
    public function buy_list(){
        $is_stop_deal = $this->is_stop_deal();

        //交易数量范围
         $market_min = Config::getValue('market_min');
         $market_max = Config::getValue('market_max');
        //单价
        $marketPrice = new MarketPrice();
        $prices  = $marketPrice->getCurrentPrice();

        //买入中
        $order = Orders::where('user_id', $this->userId)
            ->where('status', Orders::STATUS_DEFAULT)
            ->where('types', Orders::TYPE_BUY)
            ->order('create_time desc')
            ->select();
        //等待匹配云链总数量
        $default_order_sum_num = Orders::where(array('status'=>1))->sum('number');
        $order_status = User::where(array('id'=>$this->userId))->value('order_status');
        $trade_fine_num = Config::getValue('trade_fine_num');
        //等待匹配云链总数量
        $match_rand = Config::getValue('match_rand');
        $match_rand_arr = explode('@',$match_rand);

        $match_rand_str = rand($match_rand_arr[0]?$match_rand_arr[0]*100:0,$match_rand_arr[1]*100);

        $match_rand_str = set_number($match_rand_str/100,1);
        $default_order_sum_num = set_number(($default_order_sum_num*$match_rand_str)/1000,1).'万';
        //交易完成总数量
        $finish_order_sum_num = Orders::where(array('status'=>4))->sum('number');
        //是否满足闪兑条件
        $is_fill_r = UserStatistics::fillTradeReq($this->userId);
        $is_fill = $is_fill_r['ret'];
        $ks = 0;
        if($is_fill == 1){
//            $user_statistics = UserStatistics::getByUserId($this->userId);
            $ks = $is_fill_r['ks'];
        }
        $data_view = array(
            'is_stop_deal' => $is_stop_deal,
            'web_start_time' => Config::getValue('web_start_time'),
            'web_end_time' => Config::getValue('web_end_time'),
            'web_switch_market' => Config::getValue('web_switch_market'),
            'market_min' => $market_min,
            'market_max' => $market_max,
            'prices' => $prices,
            'market_money_rate' => Config::getValue('market_money_rate'),
            'order' => $order,
            'default_order_sum_num'=>$default_order_sum_num,
            'finish_order_sum_num'=>$finish_order_sum_num,
            'order_status'=>$order_status,
            'trade_fine_num'=>$trade_fine_num,
            'is_fill'=> $ks>0?$is_fill:0,
            'fill_sale'=> $ks,
        );
        return $this->fetch('buy_list',$data_view);
    }

    /**
     * @param Request $request
     * 买入
     */
    public function buy(Request $request){

        if($request->isPost()){
            $validate = $this->validate($request->post(),'\app\index\validate\BuyForm');
            if ($validate !== true) {
                return json(['code' => 1, 'message' => $validate]);
            }
            $order_status= User::where(array('id'=>$this->userId))->value('order_status');
            if($order_status == -1){
                return json(['code' => 1, 'message' => "你已被禁止交易"]);
            }
            $zfb = User::where(array('id'=>$this->userId))->value('zfb');
            if(!$zfb){
                return json(['code' => 1, 'message' => "支付宝预留信息未填写",'toUrl'=>url('/index/member/zfb')]);
            }
            //是否满足闪兑条件
            $is_fill_r = UserStatistics::fillTradeReq($this->userId);
            $is_fill = $is_fill_r['ret'];
            $msg = $is_fill_r['msg'];
            if($is_fill == 0){
                return json(['code' => 1, 'message' => $msg?$msg:'不符合申请条件','toUrl'=>'/index/member/assets']);
            }
            $number = $request->post('number');
            $ks = $is_fill_r['ks'];
            if($number > $ks){
                return json(['code' => 1, 'message' => "最多可申请{$ks}糖果"]);
            }
            /*当前挂单数*/
            $buy_w[] = ['user_id','=',$this->userId];
            $buy_w[] = ['status','=',Orders::STATUS_DEFAULT];
            $buy_list = Orders::where($buy_w)->select();
            $buy_count = count($buy_list);
            $trade_running_orders = Config::getValue('trade_running_orders');
            if($buy_count>= $trade_running_orders){
                return json(['code' => 1, 'message' => "最多同时能挂{$trade_running_orders}单"]);
            }
            //进行购买
            try {
                $model = new \app\index\model\Market();
                $model->buy($request->post('price'), $request->post('number'), $this->userId);

                return json(['code' => 0, 'message' => '申请成功', 'toUrl' => url('buy_list')]);
            } catch (\Exception $e) {

                return json(['code' => 1, 'message' => $e->getMessage()]);
            }
        }
    }
    /**
     * 出售
     */
    public function sale(Request $request){

        if ($request->isPost()) {

            $orderId = intval($request->post('order_id'));
            $order = Orders::where('id', $orderId)->find();

            $model = new \app\index\model\Market();
            try {
                $model->checkSaleTa($orderId, $this->userId);
            } catch (\Exception $e) {
                return json(['code' => 1, 'message' => $e->getMessage()]);
            }
            $order_status = User::where(array('id'=>$this->userId))->value('order_status');
            if($order_status == -1){
                return json(['code' => 1, 'message' => "你已被禁止交易"]);
            }
            $zfb = User::where(array('id'=>$this->userId))->value('zfb');
            if(!$zfb){
                return json(['code' => 1, 'message' => "支付宝预留信息未填写",'toUrl'=>url('/index/member/zfb')]);
            }
            //是否满足闪兑条件
            $is_fill_r = UserStatistics::fillTradeReq($this->userId);
            $is_fill = $is_fill_r['ret'];
            $msg = $is_fill_r['msg'];
            if($is_fill == 0){
                return json(['code' => 1, 'message' => $msg?$msg:'不符合出售条件','toUrl'=>'/index/member/assets']);
            }
            $ks = $is_fill_r['ks'];
            if(($order->number) > $ks){
                return json(['code' => 1, 'message' => "最多可出售{$ks}糖果"]);
            }
            /*每日成交次数*/
            $sale_w[] = ['target_user_id','=',$this->userId];
            $sale_w[] = array('finish_time','between',array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time()))));
            $sale_w[] = ['status','=',Orders::STATUS_FINISH];
            $sale_list = Orders::where($sale_w)->select();
            $sale_count = count($sale_list);
            $trade_order_dealed_num = Config::getValue('trade_order_dealed_num');
            if($sale_count>= $trade_order_dealed_num){
                return json(['code' => 1, 'message' => "交易每日最多成交{$trade_order_dealed_num}单"]);
            }

            //卖ta
            $result = $model->saleTa($orderId, $this->userId);

            if ($result) {
                //发短信
                $buy_mobile = User::getUserPhone($order->user_id);
                $content = getSMSTemplate('3751472');
                sendNewSMS($buy_mobile,$content,0);
                return json(['code' => 0, 'message' => '出售成功']);
            }
            return json(['code' => 1, 'message' => '出售失败']);
        }
    }
}