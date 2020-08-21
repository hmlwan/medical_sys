<?php
namespace app\index\validate;

use app\common\entity\Config;
use app\common\entity\MarketPrice;
use think\Validate;

class BuyForm extends Validate
{
    protected $rule = [
        'number' => 'require|checkNumber',
        'price' => 'require|checkPrice',
    ];

    protected $message = [
        'number.require' => '申请数量不能为空',
        'price.require' => '单价不能为空',
    ];

    public function checkNumber($value, $rules, $data = [])
    {
        if (!preg_match('/^[1-9]\d*$/', $value)) {
            return '申请数量必须为大于1的正整数';
        }

        $min = Config::getValue('market_min');
        $max = Config::getValue('market_max');

        if ($min > 0 && $value < $min) {
            return sprintf('申请数量需在%s-%s之间', $min, $max);
        }
        if ($max > 0 && $value > $max) {
            return sprintf('申请数量需在%s-%s之间', $min, $max);
        }
        if( $value % 100 != 0 ){
            return '申请数量只能为100的倍数';
        }
        return true;
    }

    public function checkPrice($value, $rules, $data = [])
    {
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $value)) {
            return '购买单价最多为2位小数';
        }
        $marketPrice = new MarketPrice();
        $prices = $marketPrice->getCurrentPrice();

        $min = $prices['prices']['min'];
        $max = $prices['prices']['max'];

        if ($value < $min || $value > $max) {
            return sprintf('单价在%s-%s之间', $min, $max);
        }
        return true;
    }


}