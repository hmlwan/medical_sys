<?php
namespace app\admin\controller;

class Upload extends Admin
{

    public function uploadEditor()
    {
        $uploadModel = new \app\common\service\Upload\Service('image');
        if ($uploadModel->upload()) {
            return json([
                'errno' => 0,
                'data' => [$uploadModel->fileName]
            ]);
        }
        return json([
            'errno' => 1,
            'fail ' => $uploadModel->error
        ]);
    }
}