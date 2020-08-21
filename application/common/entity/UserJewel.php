<?php
namespace app\common\entity;

use think\Db;
use think\Model;

class UserJewel extends Model
{
    protected $table = 'user_jewel_log';

    public $autoWriteTimestamp = false;

    public static function addInfo($userId, $remark, $jewel, $old, $new)
    {
        $entity = new self();

        $entity->user_id = $userId;
        $entity->remark = $remark;
        $entity->jewel = $jewel;
        $entity->old = $old;
        $entity->new = $new;
        $entity->create_time = time();
        return $entity->save();
    }

    /**
     * @param User $user
     * @param $data
     * @param int $type 1:添加 -1:减少
     * @return bool
     */
    public function changeUserMagic(\app\common\entity\User $user, $data, $type = 1)
    {
        Db::startTrans();
        try {

            $old = $user->jewel;
            if ($type == 1) {
                $user->jewel = bcadd($old, $data['jewel'], 8);
                $jewel = $data['jewel'];
            }

            if ($type == -1) {
                $user->jewel = bcsub($old, $data['jewel'], 8);
                $jewel = -1 * $data['jewel'];
            }

            if ($user->save() === false) {
                throw new \Exception('保存失败');
            }

            $result = self::addInfo($user->getId(), $data['remark'], $jewel, $old, $user->jewel);

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

}