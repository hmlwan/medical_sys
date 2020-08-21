<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/5/8
 * Time: 18:04
 */

namespace app\index\controller;


use app\common\entity\AdvConfig;
use app\common\entity\Config;
use app\common\entity\RebateOnlineOrder;
use app\common\entity\UserMagicLog;
use app\common\entity\UserRebateOrder;
use think\Request;

class Rebate extends Base
{

    public function index(){

        return $this->fetch('index');
    }
    public function do_index(Request $request){

        $order_no = $request->post('order_no');
        $order_no = trim($order_no);
        if(!$order_no){
            return json(['code' => 1, 'message' => '请输入订单编号']);
        }
        if(UserRebateOrder::getOne(['order_no'=>$order_no])){
            return json(['code' => 1, 'message' => '该订单已被绑定']);
        }
        $rebate_sub_num = Config::getValue('rebate_sub_num');
        $td_where[] = ['user_id','=',$this->userId];
        $td_where[] = array('add_time','between',array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time()))));
        $td_record = UserRebateOrder::getAll($td_where)->toArray();

        if(count($td_record) >= $rebate_sub_num){
            return json(['code' => 1, 'message' => '今日绑定次数用完啦']);
        }
        $data = array(
            'user_id' => $this->userId,
            'order_no' => $order_no,
        );
        $r = UserRebateOrder::saveOrder($data);
        if(!$r){
            return json(['code' => 1, 'message' => '绑定失败']);
        }
         return json(['code' => 0, 'message' => '绑定成功', 'toUrl' => url('deal')]);
    }
    /**
     * @return mixed
     * 进行中
     */
    public function deal(Request $request){

        $rebate_invalid_day = Config::getValue('rebate_invalid_day');
        $where[] = ['user_id','=',$this->userId];
        $where[] = ['is_receive','=',0];
        $times = time()- $rebate_invalid_day*24*3600;
        $where[] = ['add_time','>=',$times];
        $list_arr = array();
        $rebate_rate = Config::getValue('rebate_rate');

        $list = UserRebateOrder::getAll($where)->toArray();
        $online_order_m = new RebateOnlineOrder;
        $rebate_days = Config::getValue('rebate_days');

        if($list){
            foreach ($list as &$value){
                $order_where = array();
                $order_where[] = ['order_no','=',$value['order_no']];
                $order_info = $online_order_m->getOneByno($order_where);

                if($order_info){
                    $time = strtotime($order_info['end_time']) + $rebate_days*24*3600;

                    if(($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_PAY) ||
                        ($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_SETTLEMENT) && (time()<$time)
                    ){
                        if(($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_SETTLEMENT) && (time()<$time)){
                            $left_receive_days = $time - time();
                            $value['left_receive_days'] = $left_receive_days <= 24*3600?1:ceil($left_receive_days/(24*3600));
                        }
                        $value['no_find'] = 0;
                        $value['title'] = $order_info['title'];
                        $value['img'] = $order_info['itempic'];
                        $value['order_price'] = $order_info['pay_money'];
                        $value['pre_income'] = $order_info['estimate_money'];
                        $value['create_time'] = $order_info['create_time'];
                        $value['order_status'] = $order_info['order_status'];
                        $value['re_candy_num'] = set_number($order_info['estimate_money'] *$rebate_rate,2);
                        $list_arr[] = $value;
                    }
                }else{
                    if($value['add_time'] >= $times){
                        $value['no_find'] = 1;
                        $list_arr[] = $value;
                    }
                }
            }
        }
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $p = ($page-1)*$limit;
        $list_arr1 = array_slice($list_arr,$p,$limit);
        if($request->isAjax()){
            $data['content'] = $this->fetch('deal_ajax',array('list'=>$list_arr1));
            $data['count'] = array(
                'totalRows' => count($list_arr1),
                'listRows' =>$limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            return $this->fetch('deal',['list'=>$list_arr1]);
        }
    }
    /**
     * @return mixed
     *  未领取
     */
    public function no_receive(Request $request){

        $rebate_invalid_day = Config::getValue('rebate_invalid_day');
        $rebate_rate = Config::getValue('rebate_rate');
        $where[] = ['user_id','=',$this->userId];
        $where[] = ['is_receive','=',0];
        $times = time()- $rebate_invalid_day * 24*3600;
        $where[] = ['add_time','>=',$times];
        $list_arr = array();
        $list = UserRebateOrder::getAll($where)->toArray();
        $rebate_days = Config::getValue('rebate_days');
        if($list){
            foreach ($list as &$value){
                $order_where = array();
                $order_where[] = ['order_no','=',$value['order_no']];
                $order_info = RebateOnlineOrder::getOneByno($order_where);
                $time = strtotime($order_info['end_time']) + $rebate_days*24*3600;

                if($order_info && ($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_SETTLEMENT) && (time()>=$time)){
                    $value['title'] = $order_info['title'];
                    $value['img'] = $order_info['itempic'];
                    $value['order_price'] = $order_info['pay_money'];
                    $value['create_time'] = $order_info['create_time'];
                    $value['pre_income'] = $order_info['estimate_money'];
                    $value['re_candy_num'] = set_number($order_info['estimate_money'] *$rebate_rate,2);
                    $list_arr[] = $value;
                }
            }
        }
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $p = ($page-1)*$limit;
        $list_arr1 = array_slice($list_arr,$p,$limit);
        if($request->isAjax()){
            $data['content'] = $this->fetch('no_receive_ajax',array('list'=>$list_arr1));
            $data['count'] = array(
                'totalRows' => count($list_arr1),
                'listRows' =>$limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            //随机广告位
            $adv_info = AdvConfig::getOneById(array(),'rand()');
            if($adv_info){
                $adv_info = $adv_info->toArray();
            }
            return $this->fetch('no_receive',['list'=>$list_arr1,'adv_info'=>$adv_info]);

        }
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * 领取返利
     */
    public function do_receive(Request $request){

        $rebate_days = Config::getValue('rebate_days');
        $id = $request->post('id');
        $re_candy_num = $request->post('re_candy_num');
        $user_order = UserRebateOrder::getOne(array('id'=>$id));
        if(!$user_order){
            return json(['code' => 1, 'message' => '订单不存在']);
        }
        $rebate_switch = Config::getValue('rebate_switch');
        if($rebate_switch == 0){
            return json(['code' => 1, 'message' => '领取开关已关闭']);
        }
        $online_order_m = new RebateOnlineOrder;

        $order_where[] = ['order_no','=',$user_order['order_no']];
        $order_info = $online_order_m->getOneByno($order_where);
        if(!$order_info){
            return json(['code' => 1, 'message' => '订单不存在']);
        }
        $times = strtotime($order_info['end_time']) + $rebate_days*24*3600;
        if($times>time()){
            return json(['code' => 1, 'message' => '还未到领取时间']);
        }
        $update_data = array(
            'is_receive'=>1,
            'num'=>$re_candy_num,
            'receive_time'=>time(),
        );
        $update_r = UserRebateOrder::where('id',$id)->update($update_data);
        if(!$update_r){
            return json(['code' => 1, 'message' => '领取失败']);
        }
        //收益记录
        $magic_data = array(
            'magic' => $re_candy_num,
            'remark' => '购物返佣'.$re_candy_num.'云链',
            'type'=>11,
        );
        $magic_log = UserMagicLog::changeUserMagic($this->userId,$magic_data,1);
        return json(['code' => 0, 'message' => '领取成功', 'magic' => $re_candy_num]);
    }

    /**
     * @return mixed
     *  已领取
     */
    public function alreadly_receive(Request $request){

        $where[] = ['user_id','=',$this->userId];
        $where[] = ['is_receive','=',1];
        $rebate_rate = Config::getValue('rebate_rate');

        $list = UserRebateOrder::getAll($where)->toArray();
        if($list){
            foreach ($list as &$value){
                $order_where = array();
                $order_where[] = ['order_no','=',$value['order_no']];
                $order_info = RebateOnlineOrder::getOneByno($order_where);
                if($order_info){
                    $value['title'] = $order_info['title'];
                    $value['img'] = $order_info['itempic'];
                    $value['order_price'] = $order_info['pay_money'];
                    $value['pre_income'] = $order_info['estimate_money'];
                    $value['create_time'] = $order_info['create_time'];
                    $value['re_candy_num'] = set_number($order_info['estimate_money'] *$rebate_rate,2);

                }
            }
        }
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $p = ($page-1)*$limit;
        $list1 = array_slice($list,$p,$limit);
        if($request->isAjax()){
            $data['content'] = $this->fetch('alreadly_receive_ajax',array('list'=>$list1));
            $data['count'] = array(
                'totalRows' => count($list1),
                'listRows' =>$limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            return $this->fetch('alreadly_receive',['list'=>$list1]);
        }

    }

    /**
     * @return mixed
     * 已失效
     *
     */
    public function expired(Request $request){
        $rebate_invalid_day = Config::getValue('rebate_invalid_day');
        $rebate_rate = Config::getValue('rebate_rate');

        $where[] = ['user_id','=',$this->userId];
        $where[] = ['is_receive','=',0];
        $list = UserRebateOrder::getAll($where)->toArray();
        $expire_list = array();
        if($list){
            foreach ($list as &$value){
                $order_where = array();
                $order_where[] = ['order_no','=',$value['order_no']];
                $order_info = RebateOnlineOrder::getOneByno($order_where);
                $times = time()-$rebate_invalid_day*24*3600;
                //|| $value['add_time'] < $times
                if($order_info){
                    if($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_EXPIRED ){
                        $value['no_find'] = 0;
                        $value['title'] = $order_info['title'];
                        $value['img'] = $order_info['itempic'];
                        $value['order_price'] = $order_info['pay_money'];
                        $value['pre_income'] = $order_info['estimate_money'];
                        $value['create_time'] = $order_info['create_time'];
                        $value['re_candy_num'] = set_number($order_info['estimate_money'] *$rebate_rate,2);
                        $expire_list[] = $value;
                    }
                }else{
                    if($value['add_time'] < $times){
                        $value['no_find'] = 1;
                        $expire_list[] = $value;
                    }
                }

            }
        }
        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $p = ($page-1)*$limit;
        $expire_list1 = array_slice($expire_list,$p,$limit);
        if($request->isAjax()){
            $data['content'] = $this->fetch('expired_ajax',array('list'=>$expire_list1));
            $data['count'] = array(
                'totalRows' => count($expire_list1),
                'listRows' =>$limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            return $this->fetch('expired',['list'=>$expire_list1]);
        }
    }
}