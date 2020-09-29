<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Upload extends Admin
//class Upload extends Controller
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
    //上传压缩包
    public function upload_xdt_zip(){
        $uploadModel = new \app\common\service\Upload\ZipService('image');

        if ($uploadModel->upload()) {
            $full_path = $uploadModel->fileName;

//            $full_path = UPLOAD_PATH."/uploads/evaautograph/20200913/a61f82da89048c67d035e2853160f99c.zip";
            $zipname = UPLOAD_PATH.$full_path;
            $zip = new \ZipArchive();

            list($filename) = explode('.',$full_path);
            $filename1 = UPLOAD_PATH.$filename;
            $res = $zip->open($zipname);

            if($res === true){
                $zip->extractTo($filename1);
                $zip->close();
                unlink($zipname);
            }
            $get_all_files = get_all_files($filename1);

            $pic_arr = array();
            foreach ($get_all_files as $key=>$val){

                foreach ($val as $value){
                    $pic_path =  $filename.'/'.$key.'/'.$value;
                    $pic_name = explode('.',$value)[0];
                    $info = Db::table('user')->where('check_number','=',$pic_name)->find();
                    if($info){
                        Db::table('user')->where('check_number','=',$pic_name)->update(array(
                            'evaautograph'=>$pic_path
                        ));
                    }
                }
            }
            return json([
                'errno' => 0,
                'fail'=> '上传成功'
            ]);
        }
        return json([
            'errno' => 1,
            'fail ' => $uploadModel->error
        ]);

    }
}