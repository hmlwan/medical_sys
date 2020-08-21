<?php
namespace app\common\service\Upload;

use app\common\entity\Config;

class Service
{
    public $name;

    protected $validate = [
        'ext' => 'jpg,png,gif,jpeg'
    ];

    public $fileName;

    public $error;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * 单文件上传
     */
    public function upload()
    {
        $file = request()->file($this->name);

        $upload_size_limit = Config::getValue('upload_size_limit');
        $size = $file->getInfo()['size'];
        if($size > $upload_size_limit*1024*1024){ //超过上传最大值 M
            return false;
        }
        $info = $file->move('uploads');
        if ($info) {

            
            return $this->fileName = '/uploads/' . $info->getSaveName();;

        } else {

            $this->error = $file->getError();
            return false;
        }
    }

    /**
     * 多文件上传
     */
    public function uploadAll()
    {

    }
}