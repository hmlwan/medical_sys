<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class Cate extends Model
{

    protected $table = 'cate';
    protected $autoWriteTimestamp = false;
    protected static $cacheKey = "web_system_cate";

    public static function getAllCate(){

        return self::field('img,sort,status,cate_name,id')->order('sort asc')->select();

    }
    public static function addCate($data){
        $entity = new self();
        if($data['img']){
            $entity->img = $data['img'];
        }

        $entity->cate_name  = $data['cate_name'];
        $entity->status  = $data['status'];
        $entity->sort  = $data['sort'];

        return $entity->save();
    }
    public static function editCate($id,$data){

        $entity = new self();
        if(isset($data['img'])){
            $entity->img = $data['img'];
        }
        $entity->cate_name  = $data['cate_name'];
        $entity->status  = $data['status'];
        $entity->sort  = $data['sort'];

        return $entity->save($entity,['id'=>$id]);
    }
    public static function delCate($id){
        return self::destroy($id);
    }
    public function getOneCate($id){
        return self::get($id);
    }
}