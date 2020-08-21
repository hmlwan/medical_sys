<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/5/18
 * Time: 21:29
 */
namespace app\index\controller;

use app\common\entity\Config;
use app\common\entity\RebateOrder;
use app\common\entity\UserMagicLog;
use app\common\entity\UserRebateOrder;
use app\common\entity\task;
use think\Request;

class Shop extends Base
{
    public function index(){
//每日任务
//        $task_m = new task();
//        $map[] = ['status','=',1];
//        $map[] = ['id','eq',1];
//        $task_list = $task_m->getAllTask($map)->toArray();
//        $td_where = array('add_time', 'between', array(strtotime(date("Y-m-d 0:0:0", time())), strtotime(date("Y-m-d 23:59:59", time()))));
//
//        foreach ($task_list as &$task_value) {
//
//            $task_id = $task_value['id'];
//
//            $reward_remark = $task_value['reward_remark'];
//            $reward_remark_arr = explode("@", $reward_remark);
//            $reward_rate = $task_value['reward_rate'];
//            $reward_rate_arr = explode('@', $reward_rate);
//            $complete_num = $task_value['complete_num'];
//            $complete_num_arr = explode('@', $complete_num);
//            $finish_where = array();
//            //已完成记录
//            $finish_where[] = ['user_id', '=', $this->userId];
//            $finish_where[] = ['task_id', '=', $task_id];
//            $finish_where[] = $td_where;
//
//            $finish_record = $task_m->getTaskRecord($finish_where);
//
//            $finish_num = $finish_record ? count($finish_record) : 0;
//            $is_receive = 0;
//            $is_all_receive = 0; //已领取完
//            $cur_reward_rate = '';
//            $cur_complete_num = '';
//
//            if ($finish_num == count($complete_num_arr)) {
//                $is_all_receive = 1;
//                $cur_reward_remark = $reward_remark_arr[$finish_num-1];
//            } else {
//                $cur_reward_rate = $reward_rate_arr[$finish_num];
//                $cur_complete_num = $complete_num_arr[$finish_num];
//                $cur_reward_remark = $reward_remark_arr[$finish_num];
//                $is_receive = 1;
//            }
//            $task_value['is_receive'] = $is_receive;
//            $task_value['is_all_receive'] = $is_all_receive;
//            $task_value['cur_reward_rate'] = $cur_reward_rate;
//            $task_value['cur_complete_num'] = $cur_complete_num;
//            $task_value['cur_reward_remark'] = $cur_reward_remark;
//        }

        $data_view = array(
            'task_list'=>array()
        );
        return $this->fetch('index',$data_view);
    }
}