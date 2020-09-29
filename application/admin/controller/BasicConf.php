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

class BasicConf extends Admin{

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
        $total =  Db::table('basic_conf')->count();
        $list = Db::table('basic_conf')->limit($offset,$page_num)->order('id desc')->select();
        foreach ($list as &$value){
            $detail_conf = Db::table('basic_detail_conf')->where('basic_id','=',$value['id'])->select();
            $value['detail_conf'] = $detail_conf?$detail_conf:array();
        }
        $data = array(
            'total' => $total,
            'list' => $list,
        );
        return json()->data(['code'=>0,'data'=>$data]);
    }
    public function edit(Request $request){
        $id = $request->post('id');
        $label = $request->post('label');
        $title = $request->post('title');
        $desc = $request->post('desc');
        $AUTH_TYPE = Session::get('AUTH_TYPE');

        if($AUTH_TYPE != -1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if(!$title || !$label ){
            return json()->data(['code'=>1,'message'=>'缺少参数']);
        }
        $sub_data = array(
            'label' => $label,
            'title' => $title,
            'desc' => $desc,
        );
        if($id){
            $r = Db::table('basic_conf')->where('id','=',$id)->update($sub_data);
        }else{
            if($info = Db::table('basic_conf')->where(array('label'=>$label))->find()){
                return json()->data(['code'=>1,'message'=>'标识已存在']);
            }
            $r = Db::table('basic_conf')->insert($sub_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }
    /**
     * 编辑
     */
    public function edit_detail(Request $request){

        $id = $request->post('id');
        $basic_id = $request->post('basic_id');
        $label = $request->post('label');
        $name = $request->post('name');
        $fieldname = $request->post('fieldname');
        $ranges = $request->post('ranges');
        $unit = $request->post('unit');
        $popularization = $request->post('popularization');
        $propose = $request->post('propose');
        $litefield = $request->post('litefield');
        $desc = $request->post('desc');
        $operator = $request->post('operator');
        $USER_KEY_ID = Session::get('USER_KEY_ID');

        $AUTH_TYPE = Session::get('AUTH_TYPE');

        if($AUTH_TYPE != -1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if(!$name){
            return json()->data(['code'=>1,'message'=>'缺少类型名称']);
        }
        if( !$fieldname ){
            return json()->data(['code'=>1,'message'=>'缺少字段名']);
        }
        if( !$desc ){
             return json()->data(['code'=>1,'message'=>'缺少描述']);
        }
        if( !$litefield ){
            return json()->data(['code'=>1,'message'=>'缺少数据标识']);
        }

        $sub_data = array(
            'name' => $name,
            'fieldname' => $fieldname,
            'label' => $label,
            'ranges' => $ranges,
            'unit' => $unit,
            'popularization' => $popularization,
            'propose' => $propose,
            'litefield' => $litefield,
            'desc' => $desc,
            'operator' => $operator,
        );
        if($id){ //编辑
            $r = Db::table('basic_detail_conf')->where('id','=',$id)->update($sub_data);
        }else{//新增
            if(!$basic_id){
                return json()->data(['code'=>1,'message'=>'未知错误']);
            }
            $sub_data['basic_id'] = $basic_id;
            if($info = Db::table('basic_detail_conf')
                ->where(array('label'=>$label,'basic_id' => $basic_id))
                ->find()){
                return json()->data(['code'=>1,'message'=>'标识已存在']);
            }
            $r = Db::table('basic_detail_conf')->insert($sub_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);

    }
    public function del(Request $request){
        $id = $request->post('id');
        $type = $request->post('type');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != -1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if($type == 1){
            $r = Db::table('basic_conf')->where('id','=',$id)->delete();
            if($r){
                Db::table('basic_detail_conf')->where('basic_id','=',$id)->delete();
            }
        }else{
            $r = Db::table('basic_detail_conf')->where('id','=',$id)->delete();
        }
        if(false == $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }
}