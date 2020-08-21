<?php
namespace app\common\entity;

use think\Db;
use think\Model;

class UserStatisticsLog extends Model
{
    protected $table = 'user_statistics_log';

    public $autoWriteTimestamp = false;

    public function getType($type)
    {
        switch ($type) {
            case 1:
                return '流入金';//buy_nums
            case 2:
                return '消费金';//buy_machine_num
            case 3:
                return '流出金';//sale_nums
        }
    }


    public static function addInfo($userId, $remark, $magic, $old, $new, $type)
    {
        $entity = new self();

        $entity->user_id = $userId;
        $entity->remark = $remark;
        $entity->magic = $magic;
        $entity->old = $old;
        $entity->new = $new;
        $entity->create_time = time();
        $entity->types = $type;

        return $entity->save();
    }

    /**
     * @param User $user
     * @param $data
     * @param int $type 1:添加 -1:减少
     * @return bool
     */
    public function changeUserMagic($user_id,$field, $data, $type = 1)
    {
        Db::startTrans();
        try {

            $user = new UserStatistics();
            $userinfo = $user->getByUserId($user_id);
            $old_magic = isset($userinfo[$field])?$userinfo[$field]:0;
            $magic = $data['magic'];
            if ($type == 1) {
                $new_magic = bcadd($old_magic, $data['magic'], 8);
            }

//            if ($type == -1) {
//                $new_magic = bcsub($old_magic, $data['magic'], 8);
//                $magic = -1 * $data['magic'];
//            }

            if($user::setFieldInc($user_id,$field,$magic) === false){
                throw new \Exception('保存失败');
            }
//            if ($user->save([$field => $new_magic],['user_id'=>$user_id]) === false) {
//                throw new \Exception('保存失败');
//            }

            $result = self::addInfo($user_id, $data['remark'], $magic, $old_magic, $new_magic, $data['type']);

            if (!$result) {
                throw new \Exception('保存失败');
            }

            Db::commit();

            return true;
        } catch (\Exception $e) {

            Db::rollBack();

            return false;
        }
    }


    //查询账单
    public function magicloglist($type = '', $userId = '', $page = 1, $limit = 20)
    {
        $offset = ($page - 1) * $limit;
        $query = self::where('user_id', $userId)->field('*');
        if ($type == 1) {
            $query->where("magic", "GT", 0);
        } else if($type == -1){
            $query->where("magic", "LT", 0);
        }

        $total = self::where('user_id', $userId)->count();

        $list = $query->order("create_time desc")->limit($offset, $limit)->select();

        foreach ($list as $key => &$value) {
            $value['types'] = self::getType($value['types']);
        }

        return array(
            'list' =>$list,
            'total' =>$total,
        );
    }
}