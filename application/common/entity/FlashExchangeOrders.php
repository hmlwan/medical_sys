<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class FlashExchangeOrders extends Model
{

    protected $table = 'flash_exchange_orders';

    protected function setOrderNumber($memberId)
    {
        return date('Ymd') . $memberId . date('His');
    }
    public function getInfoById($id){
        return self::get($id);
    }
    public  function addOrders($data){
        $entity = new self();
        $entity->mobile = $data['mobile'];
        $entity->real_name = $data['real_name'];
        $entity->card = $data['card'];
        $entity->user_id = $data['user_id'];
        $entity->rate = $data['rate'];
        $entity->num = $data['num'];
        $entity->fee = $data['fee'];
        $entity->total_num = $data['total_num'];
        $entity->cny_num = $data['cny_num'];
        $entity->add_time = time();
        $entity->status = 0;
        $entity->order_no = self::setOrderNumber($data['user_id']);
        return $entity->save();
    }


    public function getList($where,$page,$limit){
        $count = self::where($where)->count();
        $list = self::where($where)->limit(($page-1)*$limit,$limit)->order("status asc,add_time desc")->select();
        return array(
            'list' => $list ? $list:array(),
            'total'=>$count ? $count:0
        );
    }

}