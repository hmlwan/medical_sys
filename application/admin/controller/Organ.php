<?php
/**
 * Created by PhpStorm.
 * User: hmlwan521
 * Date: 2020/9/5
 * Time: 下午6:00
 */
namespace app\admin\controller;

use think\Db;
use think\facade\Session;
use think\Request;

class Organ extends Admin{

    protected function initialize($dispatch){
        parent::initialize();
    }

    /**
     * 列表
     */
    public  function index(Request $request){

        $p = $request->get('p',1);
        $page_num = $request->get('page_num',10);
        $offset = ($p-1) * $page_num;
        $total =  Db::table('organ')->count();
        $list = Db::table('organ')->limit($offset,$page_num)->order('id desc')->select();
        $data = array(
            'total' => $total,
            'list' => $list,
        );
        return json()->data(['code'=>0,'data'=>$data]);
    }

    /**
     * 编辑
     */
    public function edit(Request $request){


        $id = $request->post('id');
        $abridge = $request->post('abridge');
        $name = $request->post('name');
        $address = $request->post('address');
        $mobile = $request->post('mobile');
        $status = $request->post('status',1);
        $USER_KEY_ID = Session::get('USER_KEY_ID');

        $AUTH_TYPE = Session::get('AUTH_TYPE');
//        if($AUTH_TYPE != 1){
//            return json()->data(['code'=>1,'message'=>'无操作权限']);
//        }
        if(!$name || !$abridge || !$address){
             return json()->data(['code'=>1,'message'=>'缺少参数']);
        }
        $manger_id = $USER_KEY_ID;
        $sub_data = array(
            'abridge' => $abridge,
            'name' => $name,
            'address' => $address,
            'mobile' => $mobile,
            'status' => $status,
        );
        if($id){ //编辑
            $r = Db::table('organ')->where('id','=',$id)->update($sub_data);

        }else{//新增
            if($info = Db::table('organ')->where(array('abridge'=>$abridge,'manger_id'=>$manger_id))->find()){
                return json()->data(['code'=>1,'message'=>'标识以存在']);
            }

            $sub_data['manger_id'] = $manger_id;
            $r = Db::table('organ')->insert($sub_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);

    }
    public function del(Request $request){
        $id = $request->post('id');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != 1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }


    }




}