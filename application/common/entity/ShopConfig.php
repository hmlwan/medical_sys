<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class ShopConfig extends Model
{

    protected $table = 'shop_config';
    protected $autoWriteTimestamp = false;


    public static function getAllShopConf($where){

        return self::field('*')->where($where)->select();

    }
    public static function editShop($data){
        $entity = new self();
        $entity->title  = $data['title'];
        $entity->price  = $data['price'];
        $entity->is_zy  = $data['is_zy'];
        if($data['img']){
            $entity->img  = $data['img'];
        }
        if(isset($data['id']) && $data['id']){
            return $entity->save($entity,['id'=>$data['id']]);
        }else{
            return $entity->save();
        }
    }

    public static function delShop($id){
        return self::destroy($id);
    }
    public function getOneShop($id){
        return self::get($id);
    }
}