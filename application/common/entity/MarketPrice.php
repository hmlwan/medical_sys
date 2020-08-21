<?php
namespace app\common\entity;

use think\facade\Cache;
use think\Model;

class MarketPrice extends Model
{
    protected $table = 'market_price';

    protected $autoWriteTimestamp = false;

    private $startPrice = 0.10;

    const CACHE_NAME = 'market_price_cache';

    /**
     * @return mixed 获取当前价格
     */
    public function getCurrentPrice()
    {
        $cacheTime = strtotime(date('Y-m-d', time())) + 24 * 3600 - time() - 1;
        $model = new self();
        $prices = Cache::remember(self::CACHE_NAME, function () use ($model) {
            $start = strtotime(date('Y-m-d', time()));
            $end = $start + 24 * 3600;
            $data = $model->where('creates_time', '>=', $start)->where('creates_time', '<', $end)->find();

            $price = !$data ? $price = $model->makePrice() : $data->price;

            $yesterday = $model->where('creates_time', '<', $start)->order('id', 'desc')->find();
            $min = $yesterday ? $yesterday->price : $model->startPrice;

            return [
                'prices' => [
                    'max' => $price > $min ? $price : $min,
                    'min' => $price > $min ? $min : $price,
                    'current' => $price,
                    'current_china' => self::getChinaMoney($price, 3)
                ],
                'echarts' => $model->getEchartsList()
            ];
        }, $cacheTime);

        return $prices;
    }

    /**
     * @return bool|string 生成价格
     */
    public function makePrice()
    {
        //获取昨日价格
        $start = strtotime(date('Y-m-d', time()));
        $yesterday = self::where('creates_time', '<', $start)->order('id', 'desc')->find();
        $start = $yesterday ? $yesterday->price : $this->startPrice;
        $rate = Config::getValue('market_rate');
        if ($start > 0) {
            $price = bcadd($start, $start * $rate / 100, 2);
        } else {
            $price = bcadd(0, 1 * $rate / 100, 2);
        }

        $model = new self();
        $model->price = round($price, 2);
        $model->creates_time = time();

        if ($model->save()) {
            return $price;
        }
        return false;
    }

    public function getEchartsList()
    {
        //获取前五天的值
        $list = self::order('id', 'desc')->limit(5)->select();
        $xalais = '';
        $yalais = '';
        if (count($list) < 5) {
            $offset = 5 - count($list);
            for ($i = $offset; $i >= 1; $i--) {
                $xalais .= "'" . date('m-d', $list[0]->creates_time - $i * 24 * 3600) . "',";
                $yalais .= 0 . ',';
            }
        }
        for ($i = count($list) - 1; $i >= 0; $i--) {
            $item = $list[$i];
            $xalais .= "'" . date('m-d', $item->creates_time) . "',";
            $yalais .= $item->price . ',';
        }
        return [
            'xalias' => substr($xalais, 0, -1),
            'yalias' => substr($yalais, 0, -1)
        ];
    }

    /**
     * 美金转换为人民币
     */
    public static function getChinaMoney($price, $number = 2)
    {
        $rate = Config::getValue('market_money_rate');
        return bcmul($price, $rate, $number);
    }

}