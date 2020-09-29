<?php
namespace app\common\service\Upload;

use app\common\entity\Config;

class ZipService
{
    public $name;

    protected $validate = [
        'ext' => array('zip')
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

        $upload_size_limit = 100;
        $size = $file->getInfo()['size'];
        $name = $file->getInfo()['name'];
        $ext = (explode('.',$name))[1];

        if($size > $upload_size_limit*1024*1024){ //超过上传最大值 M
            $this->error = "超过上传最大值";
            return false;
        }
        if(!in_array($ext,$this->validate['ext'])){
            $this->error = "格式要为zip";
            return false;
        }
        $info = $file->move('uploads/evaautograph');

        if ($info) {
            return $this->fileName = '/uploads/evaautograph/' . $info->getSaveName();
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