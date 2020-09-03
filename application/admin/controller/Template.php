<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/9/3
 * Time: 20:57
 */

namespace app\admin\controller;


use think\Db;
use think\facade\Session;
use think\Request;

class Template extends Admin
{

    /**
     * @return $this
     * 获取超声/模板信息
     * stype 1 超声 2 影像
     */
    public function template(){

        return json()->data(['code'=>0,'message'=> '成功','data'=>array()]);

    }

    /**
     * 获得模板内容
     * id 模板id
     */
    public function format(){

    }

    /**
     * 添加/编辑模板
     */
    public function edit(Request $request){
        $pid = $request->post('pid');
        $id = $request->post('id');
        $stype = $request->post('stype');
        $title = $request->post('title');
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != -1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        if(!$stype){
            return json()->data(['code'=>1,'message'=>'参数错误']);
        }
        if(!$title){
            return json()->data(['code'=>1,'message'=>'请输入名称']);
        }
        if($pid > 0){ //新增二级菜单
            if($id){ //编辑
                $r = Db::table('template')
                    ->where('id','=',$id)
                    ->update(array('title' => $title));

            }else{ //新增
                $r = Db::table('template')
                    ->insert(array(
                        'title' => $title,
                        'level' => 2,
                        'pid' => $pid
                        ));
            }

        }else{ //新增一级菜单
            $add_data = array(
                'title' => $title,
                'level' => 1
            );
            $r = Db::table('template')->insert($add_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'操作失败']);
        }

        return json()->data(['code'=>0,'message'=>'操作成功']);

    }
    /**
     * 添加/编辑模板内容
     */
    public function edit_format(Request $request){

        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != -1 && $AUTH_TYPE != 1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }
        $id = $request->post('id');
        $diagnosis = $request->post('diagnosis');
        $imagingfindings = $request->post('imagingfindings');
        $popularization = $request->post('popularization');
        $propose = $request->post('propose');

        $info = Db::table('template')->where('id','=',$id)->find();
        if(!$info || !$id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        $edit_data = array(
            'diagnosis' => $diagnosis,
            'imagingfindings' => $imagingfindings,
            'popularization' => $popularization,
            'propose' => $propose,
        );
        if($AUTH_TYPE == -1){ //超级管理员
            $r = Db::table('template')->where('id','=',$id)->update($edit_data);

        }else{ //机构管理员
            $is_exist = 
            if(){

            }
            $edit_data['template_id'] = $id;
        }


    }

    /**
     * 删除模板
     */
    public function del(){

    }


}