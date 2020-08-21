<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class UserRebateOrder extends Model
{

    protected $table = 'user_rebate_order';
    protected $autoWriteTimestamp = false;

    public function saveOrder($data){
        $entity = new self();
        $entity->user_id = $data['user_id'];
        $entity->order_no = $data['order_no'];
        $entity->add_time = time();
        $entity->is_receive = 0;
        $entity->num = $data['num'];
        return $entity->save();
    }
    public function getOne($where){
        $entity = new self();
        return $entity->where($where)->find();

    }
    public function getAllList($where,$order='ur.add_time desc'){

        $entity = new self();

        $list = $entity->alias('ur')
            ->leftJoin("user u", 'ur.user_id = u.id')
            ->where($where)
            ->order($order)
            ->field('ur.*,u.mobile')
            ->paginate(15);


        return $list;

    }
    public function getAll($where,$order='add_time desc'){
        $entity = new self();
        $list = $entity->where($where)->order($order)->select();
        return $list;
    }
}