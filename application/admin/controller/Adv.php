<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:32
 */

namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\AdvConfig;
use think\Request;
class Adv extends Admin
{

    public function AdvList(Request $request){

        $where = array();
        $list = AdvConfig::getAllList($where);

        return $this->render('index',['list'=>$list]);
    }

    public function advAdd(Request $request){
//
        return $this->render('advAdd');
    }
    public function advEdit(Request $request){

        if($request->isPost()){
            $data = $request->post();
            if (!empty($_FILES['ad_logo']['tmp_name'])) {
                $uploadModel = new \app\common\service\Upload\Service('ad_logo');
                $aa = $uploadModel->upload();
                $data['ad_logo'] = $aa;
            }
            $r  = AdvConfig::edit($data);
            if(!$r){
                $this->error('操作失败');
            }
            $this->success('操作成功',url('AdvList'));
        }else{
            $id = $request->get('id');
            $checkInfo = $this->checkInfo($id);
            return $this->render('advEdit',['item'=>$checkInfo]);
        }
    }

    public function advDel(Request $request){

        $id = $request->get('id');
        $r = AdvConfig::del($id);
        if(!$r){
            return json(['code' => 1, 'message' => '删除失败', 'toUrl' => url('AdvList')]);
        }
        return json(['code' => 0, 'message' => '删除成功', 'toUrl' => url('AdvList')]);
    }
    public function advStatus(Request $request){
        $status = $request->get('status');
        $id = $request->get('id');

        $r = AdvConfig::where('id',$id)->update(['status'=>$status]);

        if(!$r){
            return json(['code' => 1, 'message' => '失败', 'toUrl' => url('AdvList')]);
        }
        return json(['code' => 0, 'message' => '成功', 'toUrl' => url('AdvList')]);
    }
    private function checkInfo($id){
        $entity = AdvConfig::getOne($id);

        if(!$entity){
            throw new  AdminException('对象不存在');
        }
        return $entity;
    }
}