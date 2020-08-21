<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class UserStatistics extends Model
{

    protected $table = 'user_statistics';

    public function getByUserId($user_id){
        return self::where(array('user_id'=>$user_id))->find();

    }

    public function setFieldInc($user_id,$field,$num){

        if(!$user_id){
            return false;
        }
        if(self::getByUserId($user_id)){
            $r = self::where('user_id',$user_id)->setInc($field,$num);

            if($field != 'contribution_nums'){
                self::where('user_id',$user_id)->setInc("total_sub_nums",$num);
            }

        }else{
            $r = self::insert(array(
                'user_id' =>  $user_id,
                'one_sub_nums' => $field == 'one_sub_nums' ? $num : 0,
                'two_sub_nums' => $field == 'two_sub_nums' ? $num : 0,
                'contribution_nums' => $field == 'contribution_nums'? $num : 0,
                'total_sub_nums' => $field != 'contribution_nums' ? $num : 0,
                'sale_nums'=> $field == 'sale_nums' ? $num : 0, //用户闪兑或者市场卖出的总值
                'buy_nums'=> $field == 'buy_nums' ? $num : 0, //用户充值或者市场买入的总值
            ));
        }
        if(false !== $r){
            return true;
        }
        return false;
    }

    /**
     * 是否满足出售或闪兑条件
     * 1的值>100,2的值>1的值X2 && 3的值X2<1的值
     */
    public function fillTradeReq($user_id){
        //最低充值或者买入值100
        $quota_min_num = Config::getValue('quota_min_num');
        //糖果使用比例来限制出售1@2@2
        $quota_trade_rule = Config::getValue('quota_trade_rule');

        $statistics_info = self::getByUserId($user_id);
        if(!$quota_min_num || !$quota_trade_rule){
            return array(
                'ret' => 1,
            );
        }
        if(!$statistics_info){
            return array(
                'ret' => 0,
                'msg' => '流入金不足'
            );
        }
        $is_fill = 1;
        $msg = '';
        $ks = 0;
        $quota_trade_rule_arr = explode('@',$quota_trade_rule);
        $buy_nums = $statistics_info['buy_nums'];
        $buy_machine_num = $statistics_info['buy_machine_num'];
        $sale_nums = $statistics_info['sale_nums'];
        //满足条件
//        if(($buy_nums > ($quota_trade_rule_arr[0] * $quota_min_num))
//            && ($buy_machine_num > $quota_trade_rule_arr[1] * $buy_nums)
//            && ($sale_nums < $quota_trade_rule_arr[2] * $buy_nums)
//        ){
//            $is_fill = 0;
//        }
        if(($buy_nums < ($quota_trade_rule_arr[0] * $quota_min_num))){
            $is_fill = 0;
            $msg = '流入金不足';
        }elseif (($buy_machine_num < $quota_trade_rule_arr[1] * $buy_nums)){
            $is_fill = 0;
            $msg = '消费金不足';
        }else if(($sale_nums > $quota_trade_rule_arr[2] * $buy_nums)){
            $is_fill = 0;
            $msg = '流入金不足';
        }else{
            $ks = $quota_trade_rule_arr[2] * $buy_nums - $sale_nums;
        }
        if($ks <= 0){
            $is_fill = 0;
        }
        return array(
            'ret' => $is_fill,
            'msg' => $msg,
            'ks' => $ks >0 ?$ks :0,


        );

    }

}