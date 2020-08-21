<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/29
 * Time: 16:30
 */

namespace app\common\entity;


use think\Model;

class ProductIncomeRecord extends Model
{
    protected $table = 'product_income_record';

    protected $autoWriteTimestamp = false;

    public function getLastRecord($where){

        return self::where($where)->order('add_time desc')->find();
    }
    public function getRecord($where,$order='add_time desc'){

        return self::where($where)->order($order)->select();
    }
    public function saveProductRecord($data){
        $entity = new self;
        $entity->user_id = $data['user_id'];
        $entity->num = $data['num'];
        $entity->accumulate_second = $data['accumulate_second'];
        $entity->add_time = time();
        $entity->next_receive_time = $data['next_receive_time'];
        $entity->enery_num = $data['enery_num'];

        return $entity->save();

    }

}