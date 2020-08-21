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
class Turntable extends Admin
{

    public function turntableList(){
        $list = \app\common\entity\Turntable::getAll(array());
        return $this->render('turntableList',['list'=>$list]);
    }

    public function cateAdd(){
        return $this->render('cateAdd');
    }

    public function turntableEdit(Request $request){

        $id = $request->get('id');
        $checkInfo = array();
        if($id){
            $checkInfo = $this->checkInfo($id);
        }
        return $this->render('turntableEdit',['item'=>$checkInfo]);
    }
    public function  turntableAdd(Request $request){

        $data = [];
        if (!empty($_FILES['img']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('img');
            $aa = $uploadModel->upload();
            $data['img'] = $aa;
        }
        $post_id = $request->post('id');
        if($post_id){
            $data['id'] = $post_id;
        }
        $data['num'] = $request->post('num');
        $data['sort'] = $request->post('sort');
        $data['des'] = $request->post('des');
        $r  = \app\common\entity\Turntable::edit($data);
        $this->success('成功', 'turntableList');

    }

    public function turntableDel(Request $request){
        $service = new \app\common\entity\Turntable();

        $id = $request->get('id');
        $r = $service->del($id);
        return json(['code'=>0,'message'=>'删除成功']);
    }

    private function checkInfo($id){
        $service = new \app\common\entity\Turntable();
        $entity = $service->where('id',$id)->find();

        if(!$entity){
            throw new  AdminException('对象不存在');
        }
        return $entity;
    }
    public function turntableStatus(Request $request){
        $status = $request->get('status');
        $id = $request->get('id');
        $service = new \app\common\entity\Turntable();

        $r = $service->where('id',$id)->update(['status'=>$status]);

        if(!$r){
            return json(['code' => 1, 'message' => '失败', 'toUrl' => url('turntableList')]);
        }
        return json(['code' => 0, 'message' => '成功', 'toUrl' => url('turntableList')]);
    }
    public function turntableDrawn(Request $request){
        $is_drawn = $request->get('is_drawn');
        $id = $request->get('id');
        $service = new \app\common\entity\Turntable();

        $r = $service->where('id',$id)->update(['is_drawn'=>$is_drawn]);

        if(!$r){
            return json(['code' => 1, 'message' => '失败', 'toUrl' => url('turntableList')]);
        }
        return json(['code' => 0, 'message' => '成功', 'toUrl' => url('turntableList')]);
    }
}