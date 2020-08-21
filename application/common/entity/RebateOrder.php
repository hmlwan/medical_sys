<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class RebateOrder extends Model
{

    protected $table = 'rebate_order';
    protected $autoWriteTimestamp = false;


    public static function getAll($where,$order='add_time desc'){

        return self::field('*')->where($where)->order($order)->select();

    }
    public static function edit($data){
        $entity = new self();
        $entity->order_no  = $data['order_no'];
        $entity->title  = $data['title'];
        $entity->order_price  = $data['order_price'];
        $entity->pre_income  = $data['pre_income'];
        $entity->status  = $data['status'];
        if($data['img'] && isset($data['img'])){
            $entity->img  = $data['img'];
        }
        if(isset($data['id']) && $data['id']){
            return $entity->save($entity,['id'=>$data['id']]);
        }else{
            $entity->add_time  = time();
            return $entity->save();
        }
    }

    public static function del($id){
        return self::destroy($id);
    }
    public static function getOne($id){
        return self::get($id);
    }
    public static function getOneByno($where){
        return self::where($where)->find();
    }
}