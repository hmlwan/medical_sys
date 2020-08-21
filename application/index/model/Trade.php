<?php
namespace app\index\model;

use app\common\entity\Orders;
use think\Db;

class Trade
{
    //获取用户交易中的买入订单
    public function getBuyList($userId)
    {
        //买入订单(user_id = 自己 types为1 或者 target_user_id = 自己 types=2)
        $model = new Orders();
        $tableName = $model->getTable();
        $buyType = Orders::TYPE_BUY;
//        $saleType = orders::TYPE_SALE;
        $saleType = orders::TYPE_BUY;
        $status = Orders::STATUS_PAY . ',' . Orders::STATUS_CONFIRM.',' . Orders::STATUS_COMPLAIN;
        $sql = <<<SQL
SELECT * from {$tableName} WHERE (user_id = {$userId} AND types={$buyType} AND status in ({$status}))
OR (target_user_id = {$userId} AND types={$saleType} AND status in ({$status})) ORDER BY create_time desc
SQL;
        $list = Db::query($sql);
        foreach ($list as &$value){
            if($value['user_id'] ==$userId){
                $value['types'] = 1;
            }elseif ($value['target_user_id'] ==$userId){
                $value['types'] = 2;
            }
            $order_status = \app\common\entity\User::where('id','=',$userId)->value('order_status');
            $value['order_status'] = $order_status;
        }
        return $list;
    }

    //获取用户交易中的出售订单
    public function getSaleList($userId)
    {
        //买入订单(user_id = 自己 type为2 或者 target_user_id = 自己 type=1)
        $model = new Orders();
        $tableName = $model->getTable();
        $buyType = Orders::TYPE_BUY;
        $saleType = orders::TYPE_SALE;
        $status = Orders::STATUS_PAY . ',' . Orders::STATUS_CONFIRM;
        $sql = <<<SQL
SELECT * from {$tableName} WHERE (user_id = {$userId} AND types={$saleType} AND status in ({$status}))
OR (target_user_id = {$userId} AND types={$buyType} AND status in ({$status}))
SQL;
        return Db::query($sql);
    }

    //获取用户已完成的订单
    public function getFinishList($userId)
    {
        $model = new Orders();
        $tableName = $model->getTable();
        $status = Orders::STATUS_FINISH;
        $sql = <<<SQL
SELECT * from {$tableName} WHERE (user_id = {$userId} AND status = {$status})
OR (target_user_id = {$userId}  AND status = {$status}) ORDER BY finish_time desc
SQL;
        $list = Db::query($sql);
        foreach ($list as &$value){
            if($value['user_id'] ==$userId){
                $value['types'] = 1;
            }elseif ($value['target_user_id'] ==$userId){
                $value['types'] = 2;
            }
        }
        return $list;
    }
    //获取用户已完成的订单
    public function getExpiredList($userId)
    {
        $model = new Orders();
        $tableName = $model->getTable();
        $status = Orders::STATUS_CONCALL;
        $sql = <<<SQL
SELECT * from {$tableName} WHERE (user_id = {$userId} AND status = {$status}  AND match_time>0)
OR (target_user_id = {$userId}  AND status = {$status}  AND match_time>0 ) ORDER BY expired_time desc
SQL;
        $list = Db::query($sql);
        foreach ($list as &$value){
            if($value['user_id'] ==$userId){
                $value['types'] = 1;
            }elseif ($value['target_user_id'] ==$userId){
                $value['types'] = 2;
            }
        }
        return $list;
    }
}