<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/27
 * Time: 21:31
 */

namespace app\common\entity;
use think\Model;

class Invite extends Model
{

    protected $table = 'invite_record';
    protected $autoWriteTimestamp = false;


    public function getType($type)
    {
        switch ($type) {
            case 1:
                return '实名';
            case 2:
                return '租用空间';
            case 3:
                return '收益返佣';
            case 4:
                return '购物返佣';
            case 5:
                return '首次交易返佣';

        }
    }
    public function getLevel($level){
        switch ($level){
            case 1:
                return '一级';
            case 2:
                return '二级';
        }
    }

    /**
     * @param $data
     * @return false|int
     * 邀请返利记录
     */
    public function saveRecord($data){
        $entity = new self();
        $entity->user_id = $data['user_id'];
        $entity->sub_user_id = $data['sub_user_id'];
        $entity->add_time = time();
        $entity->types = $data['type'];
        $entity->level = $data['level'];
        $entity->is_cert = $data['is_cert'];
        $entity->content = $data['content'];
        $entity->num = $data['num']?$data['num']:0;
        return $entity->save();
    }

    /**
     * 获取下线人数
     */
    public function getInviteLevels($user_id,$level,$type,$mobile=''){
        $where = [];
        $where[] = ['i.user_id','=',$user_id];
        if($level){
            $where[] = ['i.level','in',$level];
        }
        if($mobile){
            $where[] = ['u.mobile','=',$mobile];
        }
        $where[] = ['i.types','=',$type];

        $list = self::alias('i')
            ->join('user u','i.sub_user_id=u.id','LEFT')
            ->where($where)
            ->field('i.*,u.id,u.mobile,u.energy,u.avatar')
            ->order('i.add_time desc')
            ->select();
        foreach ($list as &$value){
            $sum_w[] = ['user_id','=',$user_id];
            $sum_w[] = ['sub_user_id','=',$value['sub_user_id']];

            $sum_num = self::getInviteNum($sum_w);
            $value['sum_num'] = $sum_num;

        }
        return $list;
    }
    /**
     * 获取下线实名人数
     */
    public function getInviteCertLevels($user_id,$is_cert,$level,$type,$mobile=''){
        $where = [];
        $where[] = ['i.user_id','=',$user_id];
        $where[] = ['i.level','in',$level];
        $where[] = ['i.is_cert','=',$is_cert];
        $where[] = ['i.types','=',$type];
        if($mobile){
            $where[] = ['u.mobile','=',$mobile];
        }
        $list = self::alias('i')
            ->join('user u','i.sub_user_id=u.id','LEFT')
            ->where($where)
            ->field('i.*,u.id,u.mobile,u.energy,u.avatar')
            ->order('i.add_time desc')
            ->select();
        foreach ($list as &$value){
            $sum_w[] = ['user_id','=',$user_id];
            $sum_w[] = ['sub_user_id','=',$value['sub_user_id']];

            $sum_num = self::getInviteNum($sum_w);
            $value['sum_num'] = $sum_num;
        }
        return $list;
    }

    public function getCommissionRecord($where){
        $list = self::where($where)->order('add_time desc')->select();
        foreach ($list as &$value){
            $value['type_name'] = $this->getType($value['types']);
            $sub_mobile = User::where('id',$value['sub_user_id'])->value('mobile');
            $value['sub_mobile'] = $sub_mobile;
        }
        return $list;
    }
    public function getCommissionRecordList($where,$page,$limit){
        $offset = ($page-1) * $limit;
        $list = self::alias('i')->where($where)
            ->join('user u','u.id=i.sub_user_id','LEFT')
            ->order('i.add_time desc')
            ->limit($offset,$limit)
            ->field('i.*,u.mobile as sub_mobile')
            ->select();

        $total = self::alias('i')->where($where)->count();
        if($list){
            $list = $list->toArray();
            foreach ($list as &$value){
                $value['type_name'] = $this->getType($value['types']);
//            $sub_mobile = User::where('id',$value['sub_user_id'])->value('mobile');
//            $value['sub_mobile'] = $sub_mobile;
            }
        }


        return array(
            'list' => $list,
            'total' => $total,
        );
    }

    /**
     * @param $where
     * @return float|int
     * 获取返佣数量
     */
    public function getInviteNum($where){

        return self::where($where)->sum('num');
    }

    /**
     * @param $userid
     * @return float|int
     * 获取团队算力
     */
    public function getTeamEnergy($userid){
        $invite_certs_list = self::getInviteCertLevels($userid,2,'1,2',1);
        $team_ids = [];
        if($invite_certs_list){
            foreach ($invite_certs_list as $value){
                $team_ids[] = $value['sub_user_id'];
            }
        }
        //获取算力
        $user_m = new User();
        $energy_num = $user_m->getEngernSumNum($team_ids);
        return $energy_num;
    }
    /**
     * 分页获取下线实名人数
     */
    public function getInviteCertLevelsList($invite_w,$page,$limit){

        $count = self::alias('i')->where($invite_w)->count();
        $offset = ($page - 1) * $limit;
        $list = self::alias('i')
            ->join('user u','i.sub_user_id=u.id','LEFT')
            ->where($invite_w)
            ->limit($offset,$limit)
            ->field('i.*,u.id,u.mobile,u.energy,u.register_time,u.avatar')
            ->order('i.add_time desc')
            ->select();

        return array(
            'total' => $count,
            'list' => $list,
        );
    }

}