<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class StarConfig extends Model
{

    protected $table = 'star_config';
    protected $autoWriteTimestamp = false;


    public static function getAllStarConf($where,$order='sort asc'){

        return self::field('*')->where($where)->order($order)->select();

    }
    public static function editStar($data){
        $entity = new self();
        $entity->star_name  = $data['star_name'];
        $entity->k  = $data['key'];
        $entity->detail_bg_color  = $data['detail_bg_color'];
        $entity->receive_bg_color  = $data['receive_bg_color'];
        $entity->cert_num  = $data['cert_num'];
        $entity->reward_num  = $data['reward_num'];
        $entity->energy_num  = $data['energy_num'];
        $entity->status  = $data['status'];
        $entity->sort  = $data['sort'];
        $entity->energy  = $data['energy'];
        $entity->create_time  = time();

        if(isset($data['id']) && $data['id']){
            return $entity->save($entity,['id'=>$data['id']]);
        }else{
            return $entity->save();
        }
    }

    public static function delStar($id){
        return self::destroy($id);
    }
    public function getOneStar($id){
        return self::get($id);
    }
}