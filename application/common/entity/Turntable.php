<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class Turntable extends Model
{

    protected $table = 'turntable';
    protected $autoWriteTimestamp = false;

    public static function getAll($where,$limit,$order='sort asc'){
        return self::field('*')->where($where)->order($order)->limit($limit)->select();
    }
    public static function edit($data){

        $entity = new self();
        if(isset($data['img'])){
            $entity->img = $data['img'];
        }
        $entity->num  = $data['num'];
        $entity->sort  = $data['sort'];
        $entity->des  = $data['des'];

        if(isset($data['id']) && $data['id']){
            return $entity->save($entity,['id'=>$data['id']]);
        }else{
            return $entity->save();
        }
    }
    public static function del($id){
        return self::destroy($id);
    }
    public function getOne($id){
        return self::get($id);
    }


}