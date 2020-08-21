<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:32
 */

namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\FlashExchangeOrders;
use app\common\entity\RebateOrder;
use app\common\entity\Config;
use app\common\entity\RebateOnlineOrder;
use app\common\entity\UserRebateOrder;
use think\Request;
class Exchange extends Admin
{

     public function index(Request $request){
         $where = array();
         $keyword = $request->get('keyword');
         if($keyword){
             $where[] = ['mobile','=',$keyword];
         }
         $page = $request->get('p',1);
         $limit = $request->get('limit',20);
         $data = FlashExchangeOrders::where($where)->order('add_time desc')->paginate($limit);

         return $this->render('index',['list'=>$data]);
     }

     public function confirm_order(Request $request){
         $id = $request->get('id');
         $status = $request->get('status');
         $info = FlashExchangeOrders::getInfoById($id);
         if(!$id || !$info){
             return json(['code' => 1, 'message' => '参数错误', 'toUrl' => url('index')]);
         }
         $r = FlashExchangeOrders::update(array('status'=>$status),array('id'=>$id));
         if(false === $r){
             return json(['code' => 1, 'message' => '操作失败', 'toUrl' => url('index')]);
         }
         return json(['code' => 0, 'message' => '操作成功', 'toUrl' => url('index')]);
     }
}