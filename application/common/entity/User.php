<?php
namespace app\common\entity;

use app\common\service\Users\Cache;
use think\Db;
use think\Model;

class User extends Model
{
    const STATUS_DEFAULT = 1;
    const STATUS_FORBIDDED = -1;

    const STATUS_ORDER_DEFAULT = 1;
    const STATUS_ORDER_FORBIDDED = -1;

    const AUTH_SUCCESS = 1;
    const AUTH_ERROR = -1;

    protected $createTime = 'register_time';
    protected $login_time = 'login_time';
    /**
     * @var string 对应的数据表名
     */
    protected $table = 'user';

    protected $autoWriteTimestamp = true;

    public function getId()
    {
        return $this->id;
    }

    /**
     * 获取用户名
     */
    public function getName()
    {
        return $this->real_name;
    }

    /**
     * 获取密码
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getSafePassword()
    {
        return $this->trad_password;
    }


    /**
     * 获取禁用时间
     */
    public function getForbiddenTime()
    {
        return $this->forbidden_time ? date('Y-m-d H:i:s', $this->forbidden_time) : 0;
    }

    /**
     * 判断是否被禁用
     */
    public function isForbiddened()
    {
        return $this->forbidden_time ? true : false;
    }

    /**
     * 获取注册时间
     */
    public function getRegisterTime()
    {
        return $this->register_time;
    }

    /**
     * 获取最后登录时间
     */
    public function getLoginTime()
    {
        return $this->login_time;
    }

    public function getLevel()
    {
        switch($this->level){
            case 1:
                return Config::getValue('user_level_1');
            case 2:
                return Config::getValue('user_level_2');
            case 3:
                return Config::getValue('user_level_3');
            case 4:
                return Config::getValue('user_level_4');
            case 5:
                return Config::getValue('user_level_5');
        }
    }

    public static function checkMobile($mobile)
    {
        return self::where('mobile', $mobile)->find();
    }

    //获取直推人数
    public function getChildTotal()
    {
        return self::where('pid', $this->getId())->count();
    }

    public function getTeamInfo()
    {
        $model = new Cache();
        $users = $model->getAllUsers();
        $ids = $this->getTeam($this->getId(), $users);
        $team = User::where('id', 'in', $ids)->field('id,energy')->select()->toArray();
        $rate = array_reduce($team, function ($rate, $val) {
            return $rate + $val['energy'];
        });
        return [
            'total' => count($team),
            'energy' => $rate ? $rate : 0
        ];
    }
    //获取团队的人数
    public function getTeam($memberId, $users)
    {
        $Teams = array();//最终结果
        $mids = array($memberId);//第一次执行时候的用户id
        do {
            $othermids = array();
            $state = false;
            $k = 1;
            foreach ($mids as $valueone) {
                foreach ($users as $key => $valuetwo) {
                    if ($valuetwo['pid'] == $valueone) {
                        $Teams[] = $valuetwo['id'];//找到我的下级立即添加到最终结果中
                        $othermids[] = $valuetwo['id'];//将我的下级id保存起来用来下轮循环他的下级
                        //array_splice($users, $key, 1);//从所有会员中删除他
                        $state = true;
                    }
                }
            }
            $mids = $othermids;//foreach中找到的我的下级集合,用来下次循环
            $k +=1;
        } while ($state == true && $k<3);

        return $Teams;
    }


    public function getChilds($memberId)
    {
        $childs = self::where('pid', $memberId)
            ->field('*')
            ->select();
        return $childs;
    }

    /**
     * 获取用户上级信息
     */
    public function getParentInfo($pid)
    {

        $data = self::where('id', $pid)->field('*')->find();

        return $data ? $data : '';
    }



    /**
     * 获取用户冻结的资金
     */
    public function getFreeze()
    {
        //订单表中，用户出售的number和手续费 user_id = 用户id 且 types 为出售 （状态为 2 或者 3 或者为1）
        //获取 target_user_id = 用户id 且 types 为买入 （状态为 2 或者 3 ）

        $model = new Orders();
        $tableName = $model->getTable();
        $sql = <<<SQL
SELECT sum(`number`) as numbers, sum(`charge_number`) as charge_number FROM {$tableName} WHERE user_id={$this->id} AND types = 2 AND status IN (1,2,3) LIMIT 1
SQL;
        $sale = Db::query($sql);

        $sql = <<<SQL
SELECT sum(`number`) as numbers, sum(`charge_number`) as charge_number FROM {$tableName} WHERE target_user_id={$this->id} AND types = 1  AND status IN (2,3) LIMIT 1
SQL;
        $buy = Db::query($sql);

        $freeze = bcadd($sale[0]['numbers'], $sale[0]['charge_number'], 8) + bcadd($buy[0]['numbers'], $buy[0]['charge_number'], 8);

        return [
            'freeze' => $freeze,
            'total' => count($sale[0]['numbers']) + count($buy[0]['numbers'])
        ];
    }

    /**
     * 获取算力
     */
    public function getEngernSumNum($user_ids){

        $where = [];
        $where[] = ['id','in',$user_ids];

        return self::where($where)->sum('energy');

    }

    /*增加云链数量*/
    public function setIncMagic($user_id,$magic){
        return self::where('id',$user_id)->setInc('magic',$magic);
    }
    /*增加算力数量*/
    public function setIncEnergy($user_id,$energy){
        return self::where('id',$user_id)->setInc('energy',$energy);
    }
    /*获取用户信息*/
    public function getUserInfo($user_id){
        return self::where('id',$user_id)->find();
    }
    public function getInfoByMobile($mobile){
        return self::where('mobile',$mobile)->find();

    }
    /*获取用户信息*/
    public function getUserPhone($user_id){
        $info = self::getUserInfo($user_id);
        return $info['mobile'];
    }
    /*减云链数量*/
    public function setDecMagic($user_id,$magic){
        $now_magic = (self::getUserInfo($user_id))['magic'];
        if($now_magic<$magic){
            return false;
        }
        return self::where('id',$user_id)->setDec('magic',$magic);
    }
    /*减算力数量*/
    public function setDecEnergy($user_id,$energy){
        $now_energy= (self::getUserInfo($user_id))['energy'];
        if($now_energy < $energy){
            return false;
        }
        return self::where('id',$user_id)->setDec('energy',$energy);
    }
}