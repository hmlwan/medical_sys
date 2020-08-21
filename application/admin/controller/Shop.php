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
class Shop extends Admin
{

    public function index(){
        $list = \app\common\entity\ShopConfig::getAllShopConf(array());
        return $this->render('index',['list'=>$list]);
    }

    public function shopAdd(Request $request){

        return $this->render('shopAdd');
    }
    public function shopEdit(Request $request){

        if($request->isPost()){
            $data = $request->post();
            $validate = $this->validate($request->post(),'\app\admin\validate\ShopConfigForm');
            if($validate !== true){
                return json(['code'=>1,'message'=>$validate]);
            }
            if (!empty($_FILES['img']['tmp_name'])) {
                $uploadModel = new \app\common\service\Upload\Service('img');
                $aa = $uploadModel->upload();
                $data['img'] = $aa;
            }
            $r  = \app\common\entity\ShopConfig::editShop($data);
            if(!$r){
                $this->error('操作失败');
            }
            $this->success('操作成功',url('index'));

        }else{
            $id = $request->get('id');
            $checkInfo = $this->checkInfo($id);
            return $this->render('shopEdit',['item'=>$checkInfo]);
        }

    }
    public function shopStatus(Request $request){
        $service = new \app\common\entity\ShopConfig();
        $status = $request->get('status');
        $id = $request->get('id');

        $r = $service->where('id',$id)->update(['status'=>$status]);

        if(!$r){
            return json(['code' => 1, 'message' => '失败', 'toUrl' => url('index')]);
        }
        return json(['code' => 0, 'message' => '成功', 'toUrl' => url('index')]);
    }

    public function shopDel(Request $request){
        $service = new \app\common\entity\ShopConfig();

        $id = $request->get('id');
        $r = $service->delShop($id);
        if(!$r){
            return json(['code' => 1, 'message' => '删除失败', 'toUrl' => url('index')]);
        }
        return json(['code' => 0, 'message' => '删除成功', 'toUrl' => url('index')]);
    }

    private function checkInfo($id){
        $service = new \app\common\entity\ShopConfig();
        $entity = $service->where('id',$id)->find();

        if(!$entity){
            throw new  AdminException('对象不存在');
        }
        return $entity;
    }
}