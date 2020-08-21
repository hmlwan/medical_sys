<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:32
 */

namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\RebateOrder;
use app\common\entity\Config;
use app\common\entity\RebateOnlineOrder;
use app\common\entity\UserRebateOrder;
use think\Request;
class Rebate extends Admin
{

    public function orderList(Request $request){

        $where = array();
        $list = UserRebateOrder::getAllList($where);
        $data = $list->toArray()['data'];
        $rebate_invalid_day = Config::getValue('rebate_invalid_day');

        $times = time()- $rebate_invalid_day*24*3600;
        $rebate_days = Config::getValue('rebate_days');
        $rebate_rate = Config::getValue('rebate_rate');
        $page = $list->render();
        foreach ($data as &$value){
            $order_where = array();
            $order_where[] = ['trade_id','=',$value['order_no']];
            $order_info = RebateOnlineOrder::getOneByno($order_where);
            if($order_info){
                $value['no_find'] = 0;
                $value['title'] = $order_info['title'];
                $value['img'] = $order_info['itempic'];
                $value['order_price'] = $order_info['pay_money'];
                $value['pre_income'] = $order_info['estimate_money'];
                $value['create_time'] = $order_info['create_time'];
                $value['create_time'] = $order_info['create_time'];
                $value['re_candy_num'] = set_number($order_info['estimate_money'] *$rebate_rate,2);

                $time =  $value['add_time'] + $rebate_days*24*3600;
                if(($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_PAY) ||
                    ($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_SETTLEMENT) && (time()<$time)
                ){
                    $value['status'] = 1; //进行中
                }elseif (($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_SETTLEMENT) && (time()>=$times)){
                    $value['status'] = 2;//未领取
                }elseif ($order_info['order_status'] == RebateOnlineOrder::ORDER_STATUS_EXPIRED){
                    $value['status'] = 4;//失效
                }elseif ($value['is_receive'] == 1){
                    $value['status'] = 3;//已领取
                }
            }else{
                $value['no_find'] = 1;
                if($value['add_time'] >= $times){
                    $value['status'] = 1;
                }else{
                    $value['status'] = 4;
                }
            }
        }

        return $this->render('index',['list'=>$data,'page'=>$page]);
    }

    public function orderAdd(Request $request){

        return $this->render('orderAdd');
    }
    public function orderEdit(Request $request){

        if($request->isPost()){
            $data = $request->post();
            if (!empty($_FILES['img']['tmp_name'])) {
                $uploadModel = new \app\common\service\Upload\Service('img');
                $aa = $uploadModel->upload();
                $data['img'] = $aa;
            }
            $r  = RebateOrder::edit($data);
            if(!$r){
                $this->error('操作失败');
            }
            $this->success('操作成功',url('orderList'));
        }else{
            $id = $request->get('id');
            $checkInfo = $this->checkInfo($id);
            return $this->render('orderEdit',['item'=>$checkInfo]);
        }
    }

    public function orderDel(Request $request){

        $id = $request->get('id');
        $r = RebateOrder::del($id);
        if(!$r){
            return json(['code' => 1, 'message' => '删除失败', 'toUrl' => url('orderList')]);
        }
        return json(['code' => 0, 'message' => '删除成功', 'toUrl' => url('orderList')]);
    }

    private function checkInfo($id){
        $entity = RebateOrder::getOne($id);

        if(!$entity){
            throw new  AdminException('对象不存在');
        }
        return $entity;
    }
}