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

class disease extends Admin{

    protected function initialize($dispatch){
        parent::initialize();
    }

    /**
     * 列表
     */
    public  function index(Request $request){

        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        $p = $request->get('p',1);
        $pinyin = $request->get('pinyin','');
        $page_num = $request->get('page_num',10);

        $offset = ($p-1) * $page_num;
        $where = array();
        $manager_id = Db::table('manage_user')->where('id','=',$USER_KEY_ID)->value('pid');
        if($manager_id){
            $where['manager_id'] = $manager_id;
        }
        if($pinyin){
            $where['pinyin'] = $pinyin;
        }
        if($AUTH_TYPE != 10){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }

        $total =  Db::table('disease')->where($where)->count();
        $list = Db::table('disease')->where($where)->limit($offset,$page_num)->order('id desc')->select();
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
        $title = $request->post('title');
        $pinyin = $request->post('pinyin');
        $description = $request->post('description');
        $advise = $request->post('advise');
        $biochemistry_notice = $request->post('biochemistry_notice');
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $manager_id = Db::table('manage_user')->where('id','=',$USER_KEY_ID)->value('pid');

        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != 10){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if(!$title || !$pinyin || !$description){
             return json()->data(['code'=>1,'message'=>'缺少参数']);
        }
        $sub_data = array(
            'title' => $title,
            'pinyin' => $pinyin,
            'description' => $description,
            'advise' => $advise,
            'biochemistry_notice' => $biochemistry_notice,
        );
        if($id){ //编辑
            $r = Db::table('disease')->where('id','=',$id)->update($sub_data);

        }else{//新增
            if($info = Db::table('disease')->where(array('pinyin'=>$pinyin))->find()){
                return json()->data(['code'=>1,'message'=>'拼音码已存在']);
            }
            $sub_data['manager_id'] = $manager_id;
            $r = Db::table('disease')->insert($sub_data);
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