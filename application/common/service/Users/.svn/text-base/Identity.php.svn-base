<?php
namespace app\common\service\Users;

use app\common\entity\User;
use think\facade\Cache;
use think\facade\Session;

class Identity
{
    const SESSION_NAME = 'flow_box_member';
    const CACHE_NAME = 'flow_box_member_%s';
    const CACHE_TTS = 3600;

    public function getUserInfo($userId = 0)
    {
        $userId = $userId ? $userId : $this->getUserId();
        $userInfo = Cache::remember($this->getCacheName($userId), function () use ($userId) {
            $user = User::where('id', $userId)->find();
            return json_encode([
                'user_id' => $userId,
                'mobile' => $user->mobile,
                'nick_name' => $user->nick_name,
                'avatar' => $user->avatar,
                'level' => $user->level,
                'wx' => $user->wx,
                'zfb' => $user->zfb,
                'card_name' => $user->card_name,
                'card' => $user->card,
                'product_rate' => $user->product_rate,
                'magic' => $user->magic,
                'wx_image_url' => $user->wx_image_url,
                'zfb_image_url' => $user->zfb_image_url,
                'is_certification' => $user->is_certification,
                'real_name' => $user->real_name,
                'card_id' => $user->card_id,
                'card_right' => $user->card_right,
                'card_left' => $user->card_left,
                'certification_fail' => $user->certification_fail,
            ]);
        }, self::CACHE_TTS);

        return json_decode($userInfo);
    }

    public function delCache($userId = 0)
    {
        $userId = $userId ? $userId : $this->getUserId();
        Cache::rm($this->getCacheName($userId));
    }

    public function saveSession(User $user)
    {
        Session::set(self::SESSION_NAME, [
            'id' => $user->getId(),
            'mobile' => $user->mobile,
        ]);
    }


    public function getUserId()
    {
        $info = Session::get(self::SESSION_NAME);
        return $info['id'] ?? 0;
    }

    public function getUserMobile()
    {
        $info = Session::get(self::SESSION_NAME);
        return $info['mobile'] ?? '';
    }

    public function getCacheName($userId)
    {
        return sprintf(self::CACHE_NAME, $userId);
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $this->delCache($this->getUserId());
        Session::delete(self::SESSION_NAME);

    }
}