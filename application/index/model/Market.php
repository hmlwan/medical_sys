<?php
namespace app\index\model;

use app\common\entity\Config;
use app\common\entity\MarketPrice;
use app\common\entity\Orders;
use app\common\entity\User;
use app\common\entity\UserMagicLog;
use app\common\entity\UserStatisticsLog;
use think\Db;

class Market
{
    /**
     * 买入
     */
    public function buy($price, $number, $userId)
    {
//        if ($this->checkOrder($userId)) {
//            throw new \Exception('你还有交易未完成，请先去完成交易');
//        }
        $model = new \app\common\entity\Orders();
        $result = $model->add($userId, [
            'price' => $price,
            'number' => $number
        ], Orders::TYPE_BUY);

        if (!$result) {
            throw new \Exception('申请失败');
        }
    }

    /**
     * 卖出
     */
    public function sale($price, $number, $userId)
    {
        $order = new Orders();
        $entity = $order->add($userId, [
            'price' => $price,
            'number' => $number
        ], Orders::TYPE_SALE);

        if (!$entity) {
            return false;
        }

        $userInfo = User::where('id', $userId)->find();
        if (!$userInfo) {
            return false;
        }

        $model = new UserMagicLog();
        $result = $model->changeUserMagic($userInfo, [
            'magic' => $number + $entity->charge_number,
            'remark' => '卖出交易',
            'type' => UserMagicLog::TYPE_ORDER
        ], -1);

        return $result;

    }


    /**
     * 买ta
     */
    public function buyTa($orderId, $userId)
    {
        $order = Orders::where('id', $orderId)->find();
        if (!$order || $order->types != Orders::TYPE_SALE) {
            throw new \Exception('对象不存在');
        }
        if ($order->status != Orders::STATUS_DEFAULT) {
            throw new \Exception('订单已被别人买入了');
        }
        if ($order->user_id == $userId) {
            throw new \Exception('自己的订单不能买入哦');
        }

        if ($this->checkOrder($userId)) {
            throw new \Exception('你还有交易未完成');
        }

        $order->status = Orders::STATUS_PAY;
        $order->target_user_id = $userId;
        $order->match_time = time();

        if (!$order->save()) {
            throw new \Exception('买入失败');
        }
    }

    /**
     * 卖ta
     */
    public function saleTa($orderId, $userId)
    {
        $order = Orders::where('id', $orderId)->find();

        $userInfo = User::where('id', $userId)->find();
        if (!$userInfo) {
            return false;
        }

        $model = new UserMagicLog();
        $magic = $order->number + $order->charge_number;
        $result = $model->changeUserMagic($userId, [
            'magic' => $magic,
            'remark' => '市场卖出'.$magic.'云链',
            'type' => 13
        ], -1);

        if ($result) {

            $order->status = Orders::STATUS_PAY;
            $order->target_user_id = $userId;
            $order->match_time = time();

            $order->save();


            return true;
        }

        return false;
    }

    /**
     * 卖ta 验证
     */
    public function checkSaleTa($orderId, $userId)
    {
        $order = Orders::where('id', $orderId)->find();
        if (!$order || $order->types != Orders::TYPE_BUY) {
            throw new \Exception('订单已经不存在');
        }
        if ($order->status != Orders::STATUS_DEFAULT) {
            throw new \Exception('订单已被别人出售了');
        }
        if ($order->user_id == $userId) {
            throw new \Exception('自己的订单不能出售哦');
        }
        $user = User::where('id', $userId)->find();
        if (($user->magic) < bcadd($order->number, $order->charge_number, 8)) {
            throw new \Exception(sprintf('余额不足', $order->charge_number));
        }
        $trade_min_energy = Config::getValue('trade_min_energy');
        if (($user->energy) <  $trade_min_energy) {
            throw new \Exception("算力大于{$trade_min_energy}T时才能出售");
        }
        $checkOrder = $this->checkOrder($order->user_id,$userId);
        if ($checkOrder['ret'] != 0) {
            throw new \Exception($checkOrder['msg']);
        }
    }


    /**
     * 买入取消
     */
    public function cancelBuy($order)
    {
        return Orders::saveOrder(array('id'=>$order->id),array('status'=>5,'expired_time'=>time()));
//        return $order->delete();
    }

    /**
     * 卖出取消,退回会员的魔石和手续费
     */
    public function cancelSale($order)
    {
        $userInfo = User::where('id', $order->user_id)->find();
        if (!$userInfo) {
            return false;
        }

        $model = new UserMagicLog();
        $result = $model->changeUserMagic($userInfo, [
            'magic' => $order->number + $order->charge_number,
            'remark' => '取消卖出交易',
            'type' => UserMagicLog::TYPE_ORDER
        ], 1);

        if ($result) {
            $order->delete();
            return true;
        }
        return false;
    }

    /**
     * 判断用户是否还有交易没完成
     */
    public function checkOrder($userId,$target_user_id)
    {
        $trade_user_whitelist = Config::getValue('trade_user_whitelist');

        $trade_user_whitelist_arr = array();
        $trade_user_whitelist_ids = array();
        if($trade_user_whitelist){
            $trade_user_whitelist_arr = array_unique(array_filter(explode('@',$trade_user_whitelist)));
            if($trade_user_whitelist_arr){
                $trade_user_whitelist_ids = User::where('mobile','in',$trade_user_whitelist_arr)->column('id');

            }
        }
        $total1 = Orders::where('user_id|target_user_id', $userId)
            ->where('status', 'in', [Orders::STATUS_PAY, Orders::STATUS_CONFIRM, Orders::STATUS_COMPLAIN])
            ->count();

        if($trade_user_whitelist_ids && in_array($target_user_id,$trade_user_whitelist_ids)){
            $total2 = 0;
        }else{
            $total2 = Orders::where('user_id|target_user_id', $target_user_id)
                ->where('status', 'in', [Orders::STATUS_PAY, Orders::STATUS_CONFIRM, Orders::STATUS_COMPLAIN])
                ->count();
        }
        if($total1 > 0){
            return array('ret'=>1,'msg'=>'对方有未完成订单');
        }
        if($total2 > 0){
            return array('ret'=>1,'msg'=>'您有未完成的订单');
        }
        return array('ret'=>0);
    }

    /**
     * 获取列表
     */
    public function getList($type, $userId, $page = 1, $limit = 20, $mobile = '')
    {
        $orderModel = new Orders();
        $orderTable = $orderModel->getTable();
        $userModel = new User();
        $userTable = $userModel->getTable();
        $trade_order_num = Config::getValue('trade_order_num');
        $trade_order_is_open_random = Config::getValue('trade_order_is_open_random');
        $finishStatus = Orders::STATUS_FINISH;
        $defaultStatus = Orders::STATUS_DEFAULT;

        $offset = ($page - 1) * $limit;
        if(($page - 1) == 0 && $trade_order_num < $page * $limit ){
            $limit = $trade_order_num;
        }elseif(($page - 1) > 0 && $trade_order_num < $page * $limit){
            $limit = $trade_order_num - $offset;
        }
        $limit = $limit>0?$limit:0;

        $sql = <<<SQL
SELECT o.number,o.status,o.user_id,o.price,o.id,o.total_price,o.charge_number,o.total_price_china,u.nick_name,u.mobile,u.avatar,u.order_status,u.comment_rate,
(SELECT count(*) FROM {$orderTable} where user_id = o.user_id and status = {$finishStatus} limit 1) as finish
FROM {$orderTable} as o LEFT JOIN {$userTable} as u ON o.user_id=u.id WHERE o.status ={$defaultStatus} AND
o.types={$type} AND user_id<>{$userId}
SQL;
        if ($mobile) {
            $sql .= " AND u.mobile='{$mobile}' ";
        }

        $count_sql = <<<SQL
SELECT count(*) as total FROM {$orderTable} as o LEFT JOIN {$userTable} as u ON o.user_id=u.id WHERE o.status ={$defaultStatus} AND
o.types={$type} AND user_id<>{$userId}
SQL;
        $total = Db::query($count_sql);

        if($trade_order_is_open_random == 1){
            $sql .= " ORDER BY RAND() limit {$offset},{$limit} ";
        }else{
            $sql .= " ORDER BY o.create_time DESC limit {$offset},{$limit} ";
        }

        $list = Db::query($sql);

        $data = [];
        if ($list) {
            foreach ($list as $key => $item) {
                $data[$key]['avatar'] = $item['avatar'] ? $item['avatar'] : '/static/img/headphoto.png';
                $data[$key]['nick_name'] = $item['nick_name'];
                $data[$key]['number'] = $item['number'];
                $data[$key]['mobile'] = $item['mobile'];
                $data[$key]['show_mobile'] = yc_phone($item['mobile']);
                $data[$key]['order_id'] = $item['id'];
                $data[$key]['price'] = sprintf('%.2f', $item['price']);
                $data[$key]['finish'] = $item['finish'] ? $item['finish'] : 0;
                $data[$key]['comment'] = $item['comment_rate'];
                $data[$key]['china_price'] = $item['total_price_china'];
                $data[$key]['total_money'] = $item['total_price'];
                $data[$key]['charge_number'] = $item['charge_number'];
                $data[$key]['total_number'] = $item['charge_number']+$item['number'];
                $data[$key]['id'] = $item['id'];
                $data[$key]['status'] = $item['status'];
                $data[$key]['user_id'] = $item['user_id'];
                $data[$key]['order_status'] = $item['order_status'];
            }
        }
        return array(
            'total'=>$trade_order_num>$total[0]['total']?$total[0]['total']:$trade_order_num,
            'list'=>$data
        );
    }
}