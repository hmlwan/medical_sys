<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class AdvConfig extends Model
{

    protected $table = 'ad_config';
    protected $autoWriteTimestamp = false;


    public static function getAllList($where,$order='op_time desc'){

        return self::where($where)->order($order)->paginate(15);

    }
    public static function edit($data){
        $entity = new self();
        $entity->ad_url  = $data['ad_url'];
        $entity->op_time  = time();

        if($data['ad_logo'] && isset($data['ad_logo'])){
            $entity->ad_logo  = $data['ad_logo'];
        }
        if(isset($data['id']) && $data['id']){
            return $entity->save($entity,['id'=>$data['id']]);
        }else{
            return $entity->save();
        }
    }

    public static function del($id){
        return self::destroy($id);
    }
    public static function getOne($id){
        return self::get($id);
    }
    public static function getOneById($where,$order){
        return self::where($where)->order($order)->find();
    }
}