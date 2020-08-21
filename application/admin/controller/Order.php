<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\Orders;
use app\common\entity\User;
use app\common\entity\UserMagicLog;
use app\common\service\Market\Auth;
use app\index\model\Market;
use think\Request;

class Order extends Admin
{
    /**
     * @power 交易市场|求购订单
     * @rank 4
     */
    public function index(Request $request)
    {
        $list = $this->search($request, Orders::TYPE_BUY);

        return $this->render('list', [
            'list' => $list,
            'type' => Orders::TYPE_BUY
        ]);
    }

    /**
     * @power 交易市场|出售订单
     */
    public function show(Request $request)
    {
        $list = $this->search($request, Orders::TYPE_SALE);
        return $this->render('list', [
            'list' => $list,
            'type' => Orders::TYPE_SALE
        ]);
    }

    /**
     * @power 交易市场|出售订单@订单详细
     * @method GET
     */
    public function detail(Request $request)
    {
        $id = $request->get('id');
        $order = Orders::where('id', $id)->find();
        if (!$order) {
            $this->error('对象不存在');
        }
        $userInfo = User::where('id', $order->user_id)->find();
        if ($order->status > 1) {
            $targetUser = User::where('id', $order->target_user_id)->find();
        }
        return $this->render('detail', [
            'order' => $order,
            'userInfo' => $userInfo,
            'targetUser' => isset($targetUser) ? $targetUser : ''
        ]);
    }

    /**
     * @power 交易市场|出售订单@取消订单
     */
    public function delete(Request $request)
    {
        if ($request->isPost()) {
            $id = $request->request('id');
            $order = Orders::where('id', $id)->find();
            if (!$order) {
                throw new AdminException('订单不存在');
            }
//            if ($order->status > Orders::STATUS_PAY) {
//                throw new AdminException('订单已付款了， 不能取消');
//            }

            $result = false;
            if ($order->status == 1) {
                Orders::saveOrder(array('id'=>$order->id),array('status'=>5,'expired_time'=>time()));

//                $service = new Market();
//                if ($order->types == Orders::TYPE_BUY) {
//                    $result = $service->cancelBuy($order);
//                } elseif ($order->types == Orders::TYPE_SALE) {
//                    $result = $service->cancelSale($order);
//                }
            } else if ($order->status == 2 || $order->status == 6) {
//                $result = $order->cancel();
                Orders::saveOrder(array('id'=>$order->id),array('status'=>5,'expired_time'=>time()));
                $re_magic = $order->number + $order->charge_number;
                $uml_m = new UserMagicLog();

                $r = $uml_m->changeUserMagic($order->target_user_id, [
                    'magic' => $re_magic,
                    'remark' => '市场卖出退回'.$re_magic.'云链',
                    'type' => 18
                ], 1);
            }

            if (!$result) {
                throw new AdminException('取消失败');
            }

            return json(['code' => 0, 'message' => '取消成功']);
        }
    }

    /**
     * @power 交易市场|出售订单@确认收款
     */
    public function update(Request $request)
    {
        if ($request->isPost()) {
            $id = $request->request('id');
            $order = Orders::where('id', $id)->find();
            if (!$order) {
                throw new AdminException('订单不存在');
            }
            if ($order->status < Orders::STATUS_CONFIRM) {
                throw new AdminException('订单还没付款，不能操作');
            }

            $result = true;
            if ($order->status == 3 ||$order->status == 6 ) {
                //是否首次交易成功
                $trade_succuss_w = array(
                    'user_id' => $order->user_id,
                    'status' => 4,
                );
                $trade_succuss_info = Orders::get($trade_succuss_w);

                $result = Orders::saveOrder(array('id'=>$order->id),array('status'=>4,
                    'finish_time'=>time()));
                $number = $order->number;
                $r = UserMagicLog::changeUserMagic($order->user_id, [
                    'magic' => $number,
                    'remark' => '市场买入'.$number.'云链',
                    'type' => 2
                ], 1);
                if(empty($trade_succuss_info)){
                    $user_entity = User::getUserInfo($order->user_id);
                    Auth::addTradeInviteRecord($user_entity);
                }

            }
//            $result = $order->confirm();
            if (!$result) {
                throw new AdminException('操作失败');
            }

            return json(['code' => 0, 'message' => '操作成功']);
        }
    }

    protected function search($request, $type)
    {
        $query = Orders::alias('o')->field('o.*,u.nick_name,u.real_name,u.mobile')
            ->where('o.types', $type);
        if ($status = $request->get('status')) {
            $query->where('o.status', $status);
            $map['status'] = $status;
        }
        if ($keyword = $request->get('keyword')) {
            $type = $request->get('type');
            switch ($type) {
                case 'mobile':
                    $query->where('u.mobile', $keyword);
                    break;
                case 'number':
                    $query->where('o.order_number', $keyword);
                    break;
            }
            $map['type'] = $type;
            $map['keyword'] = $keyword;
        }

        $userTable = (new User())->getTable();
        $list = $query->leftJoin("$userTable u", 'u.id = o.user_id')
            ->order("field(o.status,6,1,2,3,4,5)")
            ->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);
        return $list;
    }
}