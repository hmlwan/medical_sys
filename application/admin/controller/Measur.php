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

class Measur extends Admin{

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
        $total =  Db::table('measur')->count();
        $list = Db::table('measur')->limit($offset,$page_num)->order('id desc')->select();
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
        $label = $request->post('label');
        $name = $request->post('name');
        $fieldname = $request->post('fieldname');
        $ranges = $request->post('ranges');
        $unit = $request->post('unit');
        $part_id = $request->post('part_id');
        $popularization = $request->post('popularization');
        $propose = $request->post('propose');
        $litefield = $request->post('litefield');
        $USER_KEY_ID = Session::get('USER_KEY_ID');

        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != -1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if(!$name || !$label || !$fieldname){
             return json()->data(['code'=>1,'message'=>'缺少参数']);
        }
        $sub_data = array(
            'name' => $name,
            'fieldname' => $fieldname,
            'label' => $label,
            'ranges' => $ranges,
            'unit' => $unit,
            'part_id' => $part_id,
            'popularization' => $popularization,
            'propose' => $propose,
            'litefield' => $litefield,
        );
        if($id){ //编辑
            $r = Db::table('measur')->where('id','=',$id)->update($sub_data);
        }else{//新增
            if($info = Db::table('measur')->where(array('label'=>$label))->find()){
                return json()->data(['code'=>1,'message'=>'标识已存在']);
            }
            $r = Db::table('measur')->insert($sub_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);

    }
    public function del(Request $request){
        $id = $request->post('id');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != -1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        $r = Db::table('measur')->where('id','=',$id)->delete();
        if(false == $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);

    }

    //获取测值数据
    public function get_measur(Request $request){
        $part_id = $request->post('part_id');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != 7){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        $list = Db::table('measur')->where('part_id','=',$part_id)->select();

        return json()->data(['code'=>0,'message'=>'成功','data'=>$list]);
    }

}