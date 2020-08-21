<?php
namespace app\common\service\Product;

use app\common\entity\Log;
use app\common\entity\Product;
use app\common\entity\User;
use app\common\entity\UserJewel;
use app\common\entity\UserMagicLog;
use app\common\entity\UserProduct;

use think\Db;

class Compute
{
    private $product = null;

    //产生收益
    public function income($userProduct)
    {
        $product = $this->getProduct()[$userProduct->product_id];
        //判断上次计算时间 如果上次计算时间为0，且计算天数为0，那么都是第一次结算
        if ($userProduct->last_time == 0 && $userProduct->balance_day == 0) {
            //获取购买时间 通过购买时间和当前时间差来计算收益
            $second = strtotime(date('Y-m-d', time())) - $userProduct->buy_time;
            $yield = $this->getYieldByHour($product['min'], $product['max'], $second);
            $this->updateInfo($userProduct, $yield);
            return true;
        }

        if (strtotime(date('Y-m-d',time())) - strtotime(date('Y-m-d',$userProduct->last_time)) < 24 * 3600) {
            return false;
        }

        if ($userProduct->total_day - $userProduct->balance_day > 1) {
            //计算一天的
            $yield = $this->getYield($product['min'], $product['max']);
            $this->updateInfo($userProduct, $yield);
            return true;
        }

        if ($userProduct->total_day - $userProduct->balance_day == 1) {
            //计算最后一天的
            $second = $userProduct->end_time - strtotime(date('Y-m-d', $userProduct->end_time));
            $yield = $this->getYieldByHour($product['min'], $product['max'], $second);
            $this->updateInfo($userProduct, $yield);
            return true;
        }

    }

    protected function updateInfo($userProduct, $yield)
    {
        Db::startTrans();
        try {
            $userId = $userProduct->user_id;
            $user = User::where('id', $userId)->find();

            $day = $userProduct->balance_day + 1;

            //如果是最后一天收益，修改魔盒状态
            if ($day >= $userProduct->total_day) {
                //获取开采率
                $rate = $this->getProduct()[$userProduct->product_id]['rate'];
                $userProduct->status = UserProduct::STATUS_STOP;
                $user->product_rate = bcsub($user->product_rate, $rate, 8);
            }

            //计算宝石
            $jewel = $this->getJewel($yield);
            if ($jewel) {
                $jewelOld = $user->jewel;
                $jewelNew = bcadd($jewel, $jewelOld, 8);
                $user->jewel = $jewelNew;

                //写日志
                $result = UserJewel::addInfo($userId, "魔盒[{$userProduct->product_number}]收益{$day}天", $jewel, $jewelOld, $jewelNew);
                if (!$result) {
                    throw new \Exception('宝石计算错误');
                }
            }

            $old = $user->magic;
            $new = sprintf('%.8f', bcadd($old, $yield, 8));
            $user->magic = $new;

            if (!$user->save()) {
                throw new \Exception('修改会员魔石数量失败');
            }

            //填写日志
            $result = UserMagicLog::addInfo($userId, "魔盒[{$userProduct->product_number}]收益{$day}天", $yield, $old, $new, UserMagicLog::TYPE_INCOME);

            if (!$result) {
                throw new \Exception('写入魔盒日志失败');
            }

            //更新魔盒信息
            $userProduct->balance_day = $day;
            $userProduct->last_time = time();
            $userProduct->yestoday = $yield;
            $userProduct->total = $userProduct->total + $yield;

            if (!$userProduct->save()) {
                throw new \Exception('修改魔盒信息失败');
            }

            Db::commit();

        } catch (\Exception $e) {
            Db::rollback();

            Log::addLog(Log::TYPE_INCOME, $e->getMessage(), [
                'user_id' => $userProduct->user_id,
                'product_number' => $userProduct->product_number,
                'magic' => $yield
            ]);
        }
    }

    //计算宝石
    protected function getJewel($magic)
    {
        $rate = \app\common\entity\Config::getValue('rules_jewel_rate');
        if ($rate) {
            return round(bcmul($magic, $rate, 8) / 100, 8);
        }
        return 0;
    }

    //计算产量的魔石
    protected function getProduct()
    {
        if (is_null($this->product)) {
            $product = Product::all();
            $data = [];
            foreach ($product as $item) {
                $data[$item->id] = [
                    'min' => $item->yield_min,
                    'max' => $item->yield_max,
                    'rate' => $item->getRate()
                ];
            }
            $this->product = $data;
        }

        return $this->product;
    }

    protected function getYield($min, $max)
    {
        $yield = bcdiv(bcadd($min, $max, 8), 2, 3);
        //产生随机数
        $rand = $yield . mt_rand(1000, 9999);

        return sprintf('%.8f', $rand);
    }

    protected function getYieldByHour($min, $max, $second)
    {
        $rand = $this->getYield($min, $max);

        return sprintf('%.8f', bcmul(bcdiv($second, 24 * 3600, 8), $rand, 8));
    }

    /**
     * 退租
     */
    public function do_rehire($record){
//        $record_id = $request->post('record_id');

        if(!$record){
//            return json(['code'=>1,'message'=>'缺少参数']);
            return false;
        }
        $record_id = $record['id'];
        $user_id = $record['user_id'];
        $product_record_info = Product::getOneProductRecord($record_id);
        if(!$product_record_info){
//            return json(['code'=>1,'message'=>'不存在']);
            return false;
        }
        $period = $product_record_info['period'] * 24 * 3600;
        $left_day = ($period + $product_record_info['add_time'])-time();
        if($left_day > 0){
//            return json(['code'=>1,'message'=>'未到退租时间']);
            return false;
        }

        //返云链
        $return_candy_num = $product_record_info['return_candy_num'];
        $energy_num = $product_record_info['energy_num'];
        //返算力
        $dec_r = User::setDecEnergy($user_id,$energy_num);
        if(!$dec_r){
//            return json(['code'=>1,'message'=>'算力不足']);
            return false;
        }
        //收益记录
        $magic_data = array(
            'magic' => $return_candy_num,
            'remark' => '退租空间奖励'.$return_candy_num.'云链',
            'type'=>8,
        );
        $magic_log = UserMagicLog::changeUserMagic($user_id,$magic_data,1);
        if(!$magic_log){
            return false;
//            return json(['code'=>1,'message'=>'退租失败']);
        }
        //更新租用记录
        $save_where['id'] =  $record_id;
        $save_data =  array(
            'is_receive' =>  1,
            'receive_time' => time()
        );
        $save_r = Product::saveProductRecord($save_where,$save_data);
//        return json(['code'=>0,'message'=>'退租成功','toUrl'=>'index/product/expired_machine']);
    }

}


