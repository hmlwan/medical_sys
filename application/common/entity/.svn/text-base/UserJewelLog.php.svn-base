<?php
namespace app\common\entity;

use think\Db;
use think\Model;

class UserJewelLog extends Model
{
    protected $table = 'user_jewel_log';

    public $autoWriteTimestamp = false;


    public static function addInfo($userId, $remark, $jewel, $old, $new)
    {
        $entity = new self();

        $entity->user_id = $userId;
        $entity->remark = $remark;
        $entity->jewel = $jewel;
        $entity->old = $old;
        $entity->new = $new;
        $entity->create_time = time();

        return $entity->save();
    }
}