<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:32
 */

namespace app\admin\controller;

use app\admin\exception\AdminException;
use think\Request;
class Cate extends Admin
{

    public function cateList(){
        $list = \app\common\entity\Cate::getAllCate();
        return $this->render('cateList',['list'=>$list]);
    }

    public function cateAdd(){
        return $this->render('cateAdd');
    }
    public function  add(Request $request){

        $data = [];
        if (!empty($_FILES['img']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('img');
            $aa = $uploadModel->upload();
            $data['img'] = $aa;
        }
        $data['cate_name'] = $request->post('cate_name');
        $data['status'] = $request->post('status');
        $data['sort'] = $request->post('sort');
        $r  = \app\common\entity\Cate::addCate($data);
        $this->success('新增成功', 'cate/cateList');

    }
    public function cateEdit(Request $request){

        $id = $request->get('id');

        $checkInfo = $this->checkInfo($id);
        return $this->render('cateEdit',['item'=>$checkInfo]);
    }
    public function  edit(Request $request){

        $data = [];
        if (!empty($_FILES['img']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('img');
            $aa = $uploadModel->upload();
            $data['img'] = $aa;
        }
        $id = $request->post('id');

        $data['cate_name'] = $request->post('cate_name');
        $data['status'] = $request->post('status');
        $data['sort'] = $request->post('sort');
        $r  = \app\common\entity\Cate::editCate($id,$data);
        $this->success('编辑成功', 'cate/cateList');

    }

    public function cateDel(Request $request){
        $service = new \app\common\entity\Cate();

        $id = $request->get('id');
        $r = $service->delCate($id);
        $this->success('删除成功', 'cate/cateList');
    }

    private function checkInfo($id){
        $service = new \app\common\entity\Cate();
        $entity = $service->where('id',$id)->find();

        if(!$entity){
            throw new  AdminException('对象不存在');
        }
        return $entity;
    }
}