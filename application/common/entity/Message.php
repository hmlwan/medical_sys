<?php
namespace app\common\entity;

use think\Model;

class Message extends Model
{

    protected $createTime = 'create_time';
    /**
     * @var string 对应的数据表名
     */
    protected $table = 'message';

    protected $autoWriteTimestamp = true;

    /**
     * 问题提交时间
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }
}