<?php
/**
 * Created by PhpStorm.
 * User: hmlwan521
 * Date: 2020/5/5
 * Time: 下午5:09
 */

namespace app\index\controller;


use app\common\entity\Config;
use app\common\entity\Orders;
use app\common\entity\User;
use app\common\entity\UserMagicLog;
use app\common\entity\UserStatistics;
use app\common\entity\UserStatisticsLog;
use app\common\service\Market\Auth;
use think\Request;
use app\index\model\Market;


class Order extends Base
{
    //进行中订单
    public function index(Request $request){
        $model = new \app\index\model\Trade();

        $buy = $model->getBuyList($this->userId);

        $limit = $request->get('limit',4);
        $page = $request->get('p', 1);

        $p = ($page-1)*$limit;
        $buy1 = array_slice($buy,$p,$limit);
        $order_status = 1;
        if($buy1){
            if($buy1[0]['order_status'] == -1){
                $order_status = -1;
            }
        }
        $trade_fine_num = Config::getValue('trade_fine_num');
        if($request->isAjax()){
            $data['content'] = $this->fetch('index_ajax',array('list'=>$buy1));
            $data['count'] = array(
                'totalRows' => count($buy),
                'listRows' => $limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            return $this->fetch('index', [
                'list' => $buy1,'trade_fine_num'=>$trade_fine_num,'order_status'=>$order_status
            ]);
        }
    }
    /*解封*/
    public function unblock(Request $request){
        $info = User::getUserInfo($this->userId)->toArray();
        if($info['order_status'] == 1){
            return json(['code' => 1, 'message' => '无需解封', 'toUrl' =>url('index') ]);
        }
        $trade_fine_num = Config::getValue('trade_fine_num');
        if($info['magic']<$trade_fine_num){
            return json(['code' => 2, 'message' => '余额不足' ]);
        }
        $r = UserMagicLog::changeUserMagic($this->userId, [
            'magic' => $trade_fine_num,
            'remark' => '违约罚金'.$trade_fine_num.'云链',
            'type' => 16
        ], -1);
        if(!$r){
            return json(['code' => 2, 'message' => '解封失败']);
        }

        User::where(array('id'=>$this->userId))->update(array('order_status'=>1));
        return json(['code' => 0, 'message' => '解封成功']);
    }
    /**
     * @param Request $request
     * @return mixed
     * 完成订单
     */
    public function finish(Request $request){

        $model = new \app\index\model\Trade();
        $finish = $model->getFinishList($this->userId);
        return $this->fetch('finish',['list'=>$finish]);
    }

    /**
     * @return mixed
     * 未完成订单
     */
    public function expired(){
        $model = new \app\index\model\Trade();
        $expired = $model->getExpiredList($this->userId);
        return $this->fetch('expired',['list'=>$expired]);
    }
    /**
     * 买家详情
     */
    public function buy_detail(Request $request){
        $order_id = $request->get('order_id');
        $info = Orders::get($order_id);
        $sale_info = User::getUserInfo($info['target_user_id']);
        if(empty($info)||empty($sale_info)|| $info['user_id'] != $this->userId){
            return $this->fetch('/publics/404');
        }
        $info = $info->toArray();
        //卖家信息
        $sale_info= $sale_info->toArray();
        $status = $info ['status'];
        $left_time = 0;
        if($status == 2){//付款剩余时间
            //买家打款时间(小时)
            $trade_buyer_pay_hours = Config::getValue('trade_buyer_pay_hours');
            $left_time = ($trade_buyer_pay_hours*3600)-(time()- $info['match_time']);
        }
        if($status == 3){ //卖家处理时间
            $trade_saler_deal_hours = Config::getValue('trade_saler_deal_hours');
            $left_time = ($trade_saler_deal_hours*3600)-(time()- $info['pay_time']);
        }
        if($status == 6){ //投诉卖家处理时间
            $trade_complain_saler_deal_hours = Config::getValue('trade_complain_saler_deal_hours');
            $left_time = ($trade_complain_saler_deal_hours*3600)-(time()- $info['report_time']);
        }
        if($left_time<0){
            $left_time = 0;
        }
        $show_left_time_str = '';
        if($left_time > 0){
            $left_time_arr = transToSecond($left_time);
            $show_left_time_str = $left_time_arr['a'].'小时'.$left_time_arr['b'].'分钟'.$left_time_arr['c'].'秒';
            $show_left_time_str = "'{$show_left_time_str}'";
        }

        $data_view = array(
            'info' => $info,
            'sale_info' => $sale_info,
            'left_time'=>$left_time,
            'show_left_time_str' =>  $show_left_time_str?$show_left_time_str:"'00小时00分钟00秒'"
        );
        return $this->fetch('buy_detail',$data_view);

    }
    /**
     * 卖家详情
     */
    public function sale_detail(Request $request){
        $order_id = $request->get('order_id');
        $info = Orders::get($order_id)->toArray();
        //卖家信息
        $buy_info = User::getUserInfo($info['user_id']);
        if(empty($info)|| empty($buy_info) || $info['target_user_id'] != $this->userId){
            return $this->fetch('/publics/404');
        }

        $buy_info = $buy_info?$buy_info->toArray():'';

        $status = $info['status'];

        $left_time = 0;
        if($status == 2){//付款剩余时间
            //买家打款时间(小时)
            $trade_buyer_pay_hours = Config::getValue('trade_buyer_pay_hours');
            $left_time = ($trade_buyer_pay_hours*3600)-(time()- $info['match_time']);
        }
        if($status == 3){ //卖家处理时间
            $trade_saler_deal_hours = Config::getValue('trade_saler_deal_hours');
            $left_time = ($trade_saler_deal_hours*3600)-(time()- $info['pay_time']);
        }
        if($status == 6){ //投诉卖家处理时间
            $trade_complain_saler_deal_hours = Config::getValue('trade_complain_saler_deal_hours');
            $left_time = ($trade_complain_saler_deal_hours*3600)-(time()- $info['report_time']);
        }
        if($left_time<0){
            $left_time = 0;
        }
        $show_left_time_str = '';
        if($left_time>0){
            $left_time_arr = transToSecond($left_time);
            $show_left_time_str = $left_time_arr['a'].'小时'.$left_time_arr['b'].'分钟'.$left_time_arr['c'].'秒';
            $show_left_time_str = "'{$show_left_time_str}'";
        }
        $data_view = array(
            'info' => $info,
            'buy_info' => $buy_info,
            'left_time'=>$left_time,
            'show_left_time_str' =>  $show_left_time_str?$show_left_time_str:"'00小时00分钟00秒'"
        );
        return $this->fetch('sale_detail',$data_view);

    }
    /**
     * 确定付款
     */
    public function pay(Request $request){

        if ($request->isPost()) {
            $id = intval($request->post('order_id'));
            $image = $request->post('image');

            $order = Orders::where('id', $id)->find();
            if (!$order) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }
            if ($order->user_id != $this->userId) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }
            /*if ($order->status != Orders::STATUS_PAY) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }*/
            if (empty($image)) {
                return json(['code' => 1, 'message ' => '请先上传打款凭据截图']);
            }
            $order->status = Orders::STATUS_CONFIRM;
            $order->pay_time = time();
            $order->image = $image;
            if ($order->save()) {
                //发短信
                $sale_mobile = User::getUserPhone($order->target_user_id);
//                $content = getSMSTemplate('3751470');
//                sendNewSMS($sale_mobile,$content,0);
                return json(['code' => 0, 'message' => '付款成功']);
            }
            return json(['code' => 1, 'message' => '操作失败，请联系管理员处理']);
        }
    }
    /*我要举报*/
    public function report(Request $request){
        if ($request->isPost()) {
            $id = intval($request->post('order_id'));

            $order = Orders::where('id', $id)->find();
            if (!$order) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }
            if ($order->target_user_id != $this->userId) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }
            /*if ($order->status != Orders::STATUS_PAY) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }*/
            $order->status = Orders::STATUS_COMPLAIN;
            $order->report_time = time();
            if ($order->save()) {
                //发短信
                $buy_mobile = User::getUserPhone($order->user_id);
                $content = getSMSTemplate('3751468');
                sendNewSMS($buy_mobile,$content,0);
                return json(['code' => 0, 'message' => '举报成功']);
            }
            return json(['code' => 1, 'message' => '操作失败，请联系管理员处理']);
        }
    }
    /**
     * 确定已收款
     */
    public function confirm(Request $request){
        if ($request->isPost()) {
            $id = intval($request->post('order_id'));

            $order = Orders::where('id', $id)->find();
            if (!$order) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }
            if ($order->target_user_id != $this->userId) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }
            /*if ($order->status != Orders::STATUS_PAY) {
                return json(['code' => 1, 'message ' => '操作错误']);
            }*/
            //是否首次交易成功
            $trade_succuss_w = array(
                'user_id' => $order->user_id,
                'status' => 4,
            );
            $trade_succuss_info = Orders::get($trade_succuss_w);
            $order->status = Orders::STATUS_FINISH;
            $order->finish_time = time();

            if ($order->save()) {
                //发短信
                $number = $order->number;
                $r = UserMagicLog::changeUserMagic($order['user_id'], [
                    'magic' => $number,
                    'remark' => '市场买入'.$number.'云链',
                    'type' => 2
                ], 1);
                if(!$r){
                    return json(['code' => 1, 'message' => '收款失败']);
                }

//                $buy_mobile = User::getUserPhone($order->user_id);
//                $content = getSMSTemplate('3751464');
//                sendNewSMS($buy_mobile,$content,0);
                //数据变动明细表
                UserStatisticsLog::changeUserMagic($order->user_id,'buy_nums',array(
                    'magic' => $number,
                    'type' => 1,
                    'remark' => '市场买入'.$number.'云链',
                ));
                //数据变动明细表
                UserStatisticsLog::changeUserMagic($order->target_user_id,'sale_nums',array(
                    'magic' => $number,
                    'type' => 3,
                    'remark' => '市场卖出'.$number.'云链',
                ));

                if(empty($trade_succuss_info)){
                    $user_entity = User::getUserInfo($order->user_id);
                    Auth::addTradeInviteRecord($user_entity);
                }
                return json(['code' => 0, 'message' => '收款成功','toUrl'=>url("finish")]);
            }
            return json(['code' => 1, 'message' => '操作失败，请联系管理员处理']);
        }
    }
    /**
     * 取消
     */
    public function cancel(Request $request){
        if ($request->isPost()) {
            $type = $request->post('type');
            $id = $request->post('order_id');

            $order = Orders::where('id', $id)->find();
            if (!$order) {
                return json(['code' => 1, 'message' => '对象不存在']);
            }

            if ($order->user_id != $this->userId) {
                return json(['code' => 1, 'message' => '请求错误']);
            }

            if ($order->status != Orders::STATUS_DEFAULT) {
                return json(['code' => 1, 'message' => '订单已在交易中，请在交易中去继续操作']);
            }
            $trade_order_cancel_hours = Config::getValue('trade_order_cancel_hours');
            if(time()<($order->create_time + $trade_order_cancel_hours*3600)){
                return json(['code' => 1, 'message' => '未到取消订单时间']);
            }
            $service = new Market();
            switch ($type) {
                case 'buy':
                    $result = $service->cancelBuy($order);
                    break;
                case 'sale':
                    $result = $service->cancelSale($order);
                    break;
                default:
                    return json(['code' => 1, 'message' => '请求错误']);
            }

            if ($result) {
                return json(['code' => 0, 'message' => '取消成功','toUrl'=>url('deal/buy_list')]);
            }
            return json(['code' => 1, 'message' => '取消失败']);
        }
    }
}