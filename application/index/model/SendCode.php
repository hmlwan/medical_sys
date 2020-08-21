<?php
namespace app\index\model;

use think\facade\Session;

class SendCode
{
    public $mobile;
    public $code;
    public $type;

    public function __construct($mobile, $type,$content='')
    {
        $this->mobile = $mobile;
        $this->type   = $type;
        $this->content   = $content;
    }

    public function send()
    {
        $this->setCode();
        //调用发生接口
        $r = $this->sendCode();
        if ($r['ret'] == 0) {
            $this->saveCode(); //保存code值到session值

            return $r;
        }
        return $r;
    }

    public function getSessionName()
    {
        $sessionNames = [
            'register'        => 'register_code_',
            'change-password' => 'change-password_',
            'market'          => 'market_sale_',
            'market_sale'     => 'market_sale_ta_',
        ];

        return $sessionNames[$this->type] . $this->mobile;
    }

    private function getCode()
    {
        return Session::get($this->getSessionName());
    }

    private function setCode()
    {
        $this->code = mt_rand(100000, 999999);
//         $this->code = 1234;
    }

    private function saveCode()
    {
        Session::set($this->getSessionName(), $this->code);
    }

    private function sendCode()
    {
        // 阿里云发送
        $phone = $this->mobile;
        $code  = $this->code;
        $content  = $this->content;
        $content = str_replace('@',$code,$content);
        $r = sendNewSMS($phone,$content);
//        $url   = 'http://' . $_SERVER['HTTP_HOST'] . '/aliyun/demo/sendSms.php?mobile=' . $phone . '&code=' . $code;
//        @file_get_contents($url);
        return $r;
    }

    public function checkCode($code)
    {
        $trueCode = $this->getCode();

        if ($trueCode == $code) {
            Session::delete($this->getSessionName());
            return true;
        }

        return false;
    }

}
