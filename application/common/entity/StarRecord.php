<?php
/**
 * Created by PhpStorm.
 * User: hmlwan521
 * Date: 2020/4/28
 * Time: 下午3:32
 */

namespace app\common\entity;


use think\Model;

class StarRecord extends Model
{

    protected $table = 'star_record';
    protected $autoWriteTimestamp = false;


    public function getStarRecord($where){
        return self::get($where);

    }
    public static function getOneStarRecord($map){

        return self::where($map)->find();

    }
    public static function getAllStarRecord($where,$order='add_time desc'){

        return self::field('*')->where($where)->order($order)->select();

    }


    public function saveStarRecord($data){
        $entity = new self();
        $entity->user_id = $data['user_id'];
        $entity->star_id = $data['star_id'];
        $entity->star_k = $data['star_k'];
        $entity->num = $data['num'];
        $entity->add_time = time();

        return $entity->save();
    }

}