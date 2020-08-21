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
class Star extends Admin
{

    public function index(){
        $list = \app\common\entity\StarConfig::getAllStarConf(array());
        return $this->render('index',['list'=>$list]);
    }

    public function starAdd(Request $request){

        if($request->isAjax()){
            $validate = $this->validate($request->post(),'\app\admin\validate\StarConfigForm');
            if($validate !== true){
                return json(['code'=>1,'message'=>$validate]);
            }
            $r  = \app\common\entity\StarConfig::editStar($request->post());
            if(!$r){
                return json(['code' => 1, 'message' => '失败']);
            }
            return json(['code' => 0, 'message' => '成功', 'toUrl' => url('Star/index')]);
        }else{
            return $this->render('starAdd');

        }
    }
    public function starEdit(Request $request){

        $id = $request->get('id');
        if($request->isAjax()){
            $validate = $this->validate($request->post(),'\app\admin\validate\StarConfigForm');
            if($validate !== true){
                return json(['code'=>1,'message'=>$validate]);
            }
            $r  = \app\common\entity\StarConfig::editStar($request->post());
            if(!$r){
                return json(['code' => 1, 'message' => '失败']);
            }
            return json(['code' => 0, 'message' => '成功', 'toUrl' => url('Star/index')]);
        }else{
            $checkInfo = $this->checkInfo($id);
            return $this->render('starEdit',['item'=>$checkInfo]);
        }

    }
    public function starStatus(Request $request){
        $service = new \app\common\entity\StarConfig();
        $status = $request->get('status');
        $id = $request->get('id');

        $r = $service->where('id',$id)->update(['status'=>$status]);

        if(!$r){
            return json(['code' => 1, 'message' => '失败', 'toUrl' => url('Star/index')]);
        }
        return json(['code' => 0, 'message' => '成功', 'toUrl' => url('Star/index')]);
    }

    public function starDel(Request $request){
        $service = new \app\common\entity\StarConfig();

        $id = $request->get('id');
        $r = $service->delStar($id);
        if(!$r){
            return json(['code' => 1, 'message' => '删除失败', 'toUrl' => url('Star/index')]);
        }
        return json(['code' => 0, 'message' => '删除成功', 'toUrl' => url('Star/index')]);
    }

    private function checkInfo($id){
        $service = new \app\common\entity\StarConfig();
        $entity = $service->where('id',$id)->find();

        if(!$entity){
            throw new  AdminException('对象不存在');
        }
        return $entity;
    }
}