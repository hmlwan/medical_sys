<?php
namespace app\common\service\Users;

use app\common\entity\User;
use think\facade\Request;
use think\facade\Session;

class Service
{
    /**
     * 加密前缀
     */
    const PREFIX_KEY = "eco_member";

    /**
     * 加密函数
     */
    public function getPassword($password)
    {
        return md5(md5(self::PREFIX_KEY . $password));
    }

    /**
     * 验证密码
     */
    public function checkPassword($password, User $entity)
    {
        return $this->getPassword($password) === $entity->getPassword();
    }

    public function checkSafePassword($password, User $entity)
    {
        return $this->getPassword($password) === $entity->getSafePassword();
    }

    public function addUser($data)
    {
        $entity = new User();
        $entity->mobile = $data['mobile'];
        $entity->nick_name = $data['nick_name'];
        $entity->password = $this->getPassword($data['password']);
        $entity->trad_password = $this->getPassword($data['trad_password']);
        $entity->wx = $data['wx'] ?? '';
        $entity->zfb = $data['zfb'] ?? '';
        $entity->card_name = $data['card_name'] ?? '';
        $entity->card = $data['card'] ?? '';
        $entity->real_name = $data['real_name'] ?? '';
        $entity->card_id = $data['card_id'] ?? '';
        $entity->register_time = time();
        $entity->register_ip = $data['ip'] ?? Request::ip();
        $entity->status = User::STATUS_DEFAULT;
        $entity->is_certification = $data['is_certification'] ?? User::AUTH_SUCCESS;

        if ($entity->save()) {
            return $entity->getId();
        }

        return false;
    }

    public function updateUser(User $user, $data)
    {
        $user->nick_name = $data['nick_name'];
        if ($data['password']) {
            $user->password = $this->getPassword($data['password']);
        }

        if ($data['trad_password']) {
            $user->trad_password = $this->getPassword($data['trad_password']);
        }

        $user->wx = $data['wx'] ?? '';
        $user->zfb = $data['zfb'] ?? '';
        $user->card_name = $data['card_name'] ?? '';
        $user->card = $data['card'] ?? '';
        $user->real_name = $data['real_name'] ?? '';
        $user->card_id = $data['card_id'] ?? '';
        $user->is_certification = $data['is_certification'];

        return $user->save();
    }

    /**
     * 检查用户名是否已存在
     */
    public function checkMobile($name, $id = 0)
    {
        $entity = user::where('mobile', $name);
        if ($id) {
            $entity->where('id', '<>', $id);
        }
        return $entity->find() ? true : false;
    }

    /**
     * 银行卡号 微信号 支付宝账号 唯一
     */
    public function checkMsg($type, $account, $id = '')
    {
        return \app\common\entity\User::where("$type", $account)->where('id', '<>', $id)->find();
    }

}