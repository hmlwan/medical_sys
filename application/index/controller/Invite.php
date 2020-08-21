<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/27
 * Time: 18:37
 */

namespace app\index\controller;

use think\Db;
use app\common\entity\User;
use app\common\entity\StarConfig;
use app\common\entity\StarRecord;
use app\common\entity\UserMagicLog;
use app\common\entity\UserInviteCode;

use think\Request;
class Invite extends Base
{

    public function index(){
        //下线2级的总人数
        $invite_m = new  \app\common\entity\Invite;
//        $invite_levels = $invite_m->getInviteLevels($this->userId,'1,2',1);
        $invite_levels_num = Db::table('user_statistics')
            ->where(array('user_id'=>$this->userId))
            ->value('total_sub_nums');
        //今日总佣金
        $td_commission_where[] = ['user_id','=',$this->userId];
        $td_commission_where[] = ['is_cert','=',2];
        $td_commission_where[] = array('add_time','between',array(strtotime(date("Y-m-d 0:0:0",time())),strtotime(date("Y-m-d 23:59:59",time()))));

        $td_commission_num = $invite_m->getInviteNum($td_commission_where);

        //邀请码
        $invite_code = \app\common\entity\UserInviteCode::getCodeByUserId($this->userId);
        //邀请链接
        $invite_url = "https://".$_SERVER['SERVER_NAME'].'/index/publics/register.html?code='.$invite_code;

        $data_view = array(
            'invite_url' => $invite_url,
            'invite_code' => $invite_code,
            'td_commission_num' => $td_commission_num?set_number($td_commission_num,0):0,
            'invite_levels_num' => $invite_levels_num
        );
        return $this->fetch('index',$data_view);
    }

    /**
     * @return mixed
     * 邀请记录
     */
    public function invite_record(Request $request){


        $type = $request->get('type','');
        $page = $request->get('page',1);
        $limit = $request->get('limit',6);
        $invite_m = new  \app\common\entity\Invite;

        //已累计云链数量
        $commission_where['user_id'] = $this->userId;
        $commission_where['is_cert'] = 2;
        $commission_num = $invite_m->getInviteNum($commission_where);

        //团队人数统计
        $user_statistics = Db::table('user_statistics')
            ->where(array('user_id'=>$this->userId))->find();

        $invite_w = array();
        $invite_w[] = ['i.user_id','=',$this->userId];
        if($type == 1){
            $invite_w[] = ['i.level','in',2];

        }elseif($type == 2){
//            $list = $invite_no_cert;
        }else{
            $invite_w[] = ['i.level','in',1];
        }
//        $invite_w[] = ['i.is_cert','=',2];
        $invite_w[] = ['i.types','=',1];

        $invite_res = $invite_m->getInviteCertLevelsList($invite_w,$page,$limit);

//        $invite_one = $invite_m->getInviteLevels($this->userId,1,1);

        //二级人数
//        $invite_two = $invite_m->getInviteLevels($this->userId,2,1);

        //未实名人数
//        $invite_no_cert = $invite_m->getInviteCertLevels($this->userId,1,'1,2',1);
        //团队算力
//        $team_energy = $invite_m->getTeamEnergy($this->userId);
        $team_energy = $user_statistics['contribution_nums'];

        //星级达人
//        $star_record_m = new  \app\common\entity\StarRecord;
//        $star_record_where['user_id'] = $this->userId;
//        $star_record_list = $star_record_m->getAllStarRecord($star_record_where);
//        $star_conf_one = '';
//        if($star_record_list){
//            $star_ids = array();
//            foreach ($star_record_list as $value){
//                $star_ids[] = $value['star_id'];
//            }
//            $star_ids = arr_unique($star_ids);
//
//            $star_conf_m = new  \app\common\entity\StarConfig();
//            $star_conf_where[] = ['id','in',$star_ids];
//            $star_confs = $star_conf_m->getAllStarConf($star_conf_where,'k desc');
//            $star_conf_one = $star_confs[0]['k'];
//        }
        $invite_one_count = $user_statistics['one_sub_nums']?$user_statistics['one_sub_nums']:0;
        $invite_two_count = $user_statistics['two_sub_nums']?$user_statistics['two_sub_nums']:0;

//        $invite_no_cert_count = $invite_no_cert?count($invite_no_cert):0;
        $list = $invite_res['list'];
        $total = $invite_res['total'];

        if($request->isAjax()){
            $data['content'] = $this->fetch('invite_record_ajax',array('list'=>$list));
            $data['count'] = array(
                'totalRows' => $total,
                'listRows' => $limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            $data_view = array(
                'invite_one' => $invite_one_count,
                'list'=>$list,
//            'invite_cert_one' => $invite_cert_one_count,
                'invite_two' => $invite_two_count,
//            'invite_no_cert'=>$invite_no_cert_count,
                'team_energy'=>$team_energy,
//            'invite_cert_two' => $invite_cert_two_count,
//            'star_conf_one' => $star_conf_one,
                'commission_num' => $commission_num?set_number($commission_num,2):'0.00',
            );
            return $this->fetch('invite_record',$data_view);
        }
    }
    /**
     * @return mixed
     * 直推人数
     */
    public function invite_level_one(Request $request){
        $invite_m = new  \app\common\entity\Invite;
        $invite_one = $invite_m->getInviteLevels($this->userId,1,1)->toArray();

        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $p = ($page-1)*$limit;
        $invite_one1 = array_slice($invite_one,$p,$limit);
        foreach ($invite_one1 as &$value){
            $value['mobile'] =  User::getUserPhone($value['sub_user_id']);
        }

        if($request->isAjax()){
            $data['content'] = $this->fetch('invite_level_one_ajax',array('list'=>$invite_one1));
            $data['count'] = array(
                'totalRows' => count($invite_one),
                'listRows' => 8
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('invite_level_one',['list'=>$invite_one1]);

        }
    }

    /**
     * @return mixed
     * 间推人数
     */
    public function invite_level_two(Request $request){
        $invite_m = new  \app\common\entity\Invite;
        $invite_two = $invite_m->getInviteLevels($this->userId,2,1)->toArray();

        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $p = ($page-1)*$limit;
        $invite_two1 = array_slice($invite_two,$p,$limit);
        foreach ($invite_two1 as &$value){
            $value['mobile'] =  User::getUserPhone($value['sub_user_id']);
        }
        if($request->isAjax()){
            $data['content'] = $this->fetch('invite_level_two_ajax',array('list'=>$invite_two1));
            $data['count'] = array(
                'totalRows' => count($invite_two),
                'listRows' => 8
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            return $this->fetch('invite_level_two',['list'=>$invite_two1]);
        }
    }

    /**
     * @return mixed
     * 星级达人
     */
    public function star_man(){
        $star_record_m = new  \app\common\entity\StarRecord;
        $map['user_id'] = $this->userId;
        $list = $star_record_m->getAllStarRecord($map);

        return $this->fetch('star_man',['list'=>$list]);

    }

    /**
     * @return mixed
     * 佣金记录
     */
    public function commission_record(Request $request){
        $invite_m = new  \app\common\entity\Invite;

        $where[] = ['i.user_id','=',$this->userId];
        $where[] = ['i.is_cert','=',2];
        $where[] = ['i.num','neq',0];

//        $sum_num_arr = array();
//        if($commission_record){
//            $get_td_data = \app\index\model\User::get_td_data($commission_record,'add_time','num',4);
//            $sum_num_arr = $get_td_data['sum_num_arr'];
//        }

        $page = $request->get('p', 1);
        $limit = $request->get('limit', 8);
        $res = $invite_m->getCommissionRecordList($where,$page,$limit);

        $commission_record = $res['list'];
        $total = $res['total'];
        if($request->isAjax()){

            $data['content'] = $this->fetch('commission_record_ajax',array('list'=>$commission_record));
            $data['count'] = array(
                'totalRows' => $total,
                'listRows' => $limit
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);

        }else{
            return $this->fetch('commission_record',['list'=>$commission_record]);
        }
    }

    /**
     * @return mixed
     * 佣金记录明细
     */
    public function commission_detail(Request $request){
        $page = $request->get('p', 1);
        $times = $request->get('times');
        $limit = $request->get('limit', 8);
        $where[] = ['user_id','=',$this->userId];
        $where[] = ['is_cert','=',2];
        $invite_m = new  \app\common\entity\Invite;

        $commission_record = $invite_m->getCommissionRecord($where)->toArray();
        $get_td_data = array();
        if($commission_record){
            $get_td_data = \app\index\model\User::get_td_data($commission_record,'add_time','num',4);
        }
        $num_arr = $get_td_data['num_arr'];

        $td_num_arr = $num_arr[$times];
        $p = ($page-1)*$limit;
        $td_num_arr1 = array_slice($td_num_arr,$p,$limit);

        if ($request->isAjax()) {
            $data['content'] = $this->fetch('commission_detail_ajax',array('list'=>$td_num_arr1));
            $data['count'] = array(
                'totalRows' => count($td_num_arr1),
                'listRows' => 8
            );
            return json(['code' => 0, 'message' => 'success', 'data' => $data]);
        }else{
            return $this->fetch('commission_detail',['list'=>$td_num_arr1,'times'=> $times]);
        }
    }

    /**
     * @return mixed
     * 规则
     */
    public function rule(){
        return $this->fetch('rule');

    }

    /**
     * 邀请扶持
     */
    public function invite_star(){

        //团队人数统计
        $user_statistics = Db::table('user_statistics')
            ->where(array('user_id'=>$this->userId))->find();
        //团队总贡献
        $energy_num = $user_statistics['contribution_nums'] ? $user_statistics['contribution_nums']:0;
        //团队人数
        $invite_certs_num = $user_statistics['total_sub_nums'] ? $user_statistics['total_sub_nums']:0;

        $starconf_m = new StarConfig();
        $s_where['status'] = 1;
        $star_conf_list = $starconf_m->getAllStarConf($s_where);

        //查询领取记录以及是否达到条件
        $map = array();
        foreach ($star_conf_list as &$value){
            $map['user_id'] = $this->userId;
            $map['star_id'] = $value['id'];
            $is_receive = StarRecord::getOneStarRecord($map);

            if($is_receive){
                $value['is_receive'] = 1; //已领取
            }else{
                if(($invite_certs_num >= $value['cert_num']) && ($energy_num >= $value['energy_num'])){
                    $value['is_receive'] = 2; //未领取
                }else{
                    $value['is_receive'] = 3;//未达到
                }
            }
        }

        $view_data = array(
            'invite_certs_num'=>$invite_certs_num,
            'energy_num' => $energy_num,
            'star_conf_list' => $star_conf_list
        );
        return $this->fetch('invite_star',$view_data);

    }
     public function receive(Request $request){

         $star_id = $request->post('star_id');
         if(!$star_id){
            return json(['code'=>1,'message'=>'参数缺少']);
         }
         $starconf_m = new StarConfig();
         $star_conf_info = $starconf_m->getOneStar($star_id)->toArray();

         if(!$star_conf_info){
             return json(['code'=>1,'message'=>'星级扶持不存在']);
         }
         $reward_num = $star_conf_info['reward_num'];

         $userInfo = $this->userInfo;

         if($userInfo->is_certification != 1){
             return json(['code'=>1,'message'=>'还未实名，请先去实名认证','toUrl'=>'index/member/certification']);
         }

         $starrecord_m = new StarRecord();
         //是否领取
        $is_exist = $starrecord_m->getStarRecord(['user_id'=>$this->userId,'star_id'=>$star_id]);
        if($is_exist){
            return json(['code'=>1,'message'=>'已领取啦']);
        }
         $energy = User::where(array('id'=>$this->userId))->value('energy');

         if($energy < $star_conf_info['energy']){
            return json(['code'=>1,'message'=>"自身算力不低于{$star_conf_info['energy']}T"]);
         }
         //明细记录
         $magiclog_m = new UserMagicLog();
         $recor_data = array(
             'magic' => $reward_num,
             'type' => 6,
             'remark' => '领取'.$star_conf_info['star_name']."奖励{$reward_num}云链"
         );
         $record_r = $magiclog_m->changeUserMagic($this->userId,$recor_data,1);

         if($record_r){
             //领取记录
             $data = array(
                 'user_id' => $this->userId,
                 'star_id' => $star_id,
                 'num' => $reward_num,
                 'star_k'=>$star_conf_info['k']
             );
             $receive_r = $starrecord_m->saveStarRecord($data);
             return json(['code'=>0,'message'=>'领取成功','data'=>$reward_num]);
         }else{
             return json(['code'=>1,'message'=>'领取失败']);
         }
     }


}