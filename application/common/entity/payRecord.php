<?php
/**
 * Created by PhpStorm.
 * User: hmlwan521
 * Date: 2020/5/4
 * Time: 下午12:01
 */

namespace app\common\entity;


use think\Model;
use app\common\entity\User as User_m;
class payRecord extends Model
{

    protected $table = 'pay_record';
    public $autoWriteTimestamp = false;

    public function getType($type){

        switch ($type){
            case '1':
                return '划拨';
            case '2':
                return '收款';

        }
    }
    public function getRecord($where,$order='add_time desc'){
       $list = self::where($where)->order($order)->select();
        foreach ($list as &$value){
            $value['show_type'] = self::getType($value['types']);
            $info = User_m::getUserInfo($value['user_id']);
            $avatar = $info['avatar'];
            $value['avatar'] = $avatar;
        }
        return $list;

    }

    public function saveRecord ($data){
        $entity = new self();

        $entity->user_id = $data['user_id'];
        $entity->num = $data['num'];
        $entity->types = $data['type'];
        $entity->pay_mobile = $data['pay_mobile'];
        $entity->add_time = time();

        return $entity->save();

    }
}