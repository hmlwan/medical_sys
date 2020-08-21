<?php
namespace app\common\entity;

use think\Model;

class Product extends Model
{
    /**
     * @var string 对应的数据表名
     */
    protected $table = 'product';

    protected $autoWriteTimestamp = true;

    public function getProductName()
    {
        return $this->product_name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function getInfoById($id)
    {
        return self::where(['id' => $id])->find();
    }

    //计算开采率
    public function getRate()
    {
        return bcdiv((bcadd($this->rate_min, $this->rate_max, 5)), 2, 5);
    }
}