<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/9/3
 * Time: 20:57
 */

namespace app\admin\controller;


use app\common\service\Users\Jwt;
use think\Db;
use think\facade\Session;
use think\Request;

class Template extends Admin
{
    protected function initialize($dispatch){
        parent::initialize();
    }

    /**
     * @return $this
     * 获取超声/模板信息
     * stype 1 超声 2 影像
     */
    public function index(Request $request){

        $stype = $request->post('stype');
        $list = Db::table('template')->where('stype','=',$stype)->order('id asc')->select();
        $arr = array();
        if($list){
            foreach ($list as $value){
                if($value['level'] == 1){

                    $children = array();
                    foreach ($list as $v){
                      if($v['level'] == 2 && $value['id'] == $v['pid']) {
                          $children[] = array(
                              'label' => $v['title'],
                              'id' => $v['id'],
                              'level' => 2,
                              'pid'=>$v['pid']
                          );
                      }
                    }
                    $arr[] = array(
                        'label' => $value['title'],
                        'id' => $value  ['id'],
                        'children' => $children,
                        'level' => 1
                    );
                }
            }
        }
        return json()->data(['code'=>0,'data'=>$arr]);
    }

    /**
     * 获得模板内容
     * id 模板id
     */
    public function format(Request $request){

        $id = $request->post('id');
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        if(!$id){
            return json()->data(['code'=>1,'message'=>'未知错误']);
        }
        $w = array(
            'manager_id' => $USER_KEY_ID,
            'template_id' => $id,
        ) ;
        $info = Db::table('template_agency')
            ->where($w)
            ->field('title,diagnosis,imagingfindings,popularization,propose')
            ->find();
        if(empty($info)){
            $info = Db::table('template')
                ->where("id",'=',$id)
                ->field('title,diagnosis,imagingfindings,popularization,propose')
                ->find();
        }
        return json()->data(['code'=>0,'message'=> '成功','data'=>$info]);
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
                        'pid' => $pid,
                        'stype' => $stype,
                 ));
            }

        }else{ //新增一级菜单
            $add_data = array(
                'title' => $title,
                'level' => 1,
                'stype' => $stype,
            );
            $r = Db::table('template')->insert($add_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        $auto_id = Db::name('template')->getLastInsID();
        return json()->data(['code'=>0,'message'=>'成功','data'=>$auto_id]);
    }

    /**
     * @param Request $request
     * @return $this
     *  编辑名称
     */
    public function edit_name(Request $request){
        $id = $request->post('id');
        $title = $request->post('title');
        if(!$id || !$title){
            return json()->data(['code'=>1,'message'=>'缺少参数']);
        }
        $r = Db::table('template')->where('id','=',$id)->update(array(
            'title' => $title
        ));
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        Db::table('template_agency')->where('template_id','=',$id)->update(array(
            'title' => $title
        ));

        return json()->data(['code'=>0,'message'=>'成功']);
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
            $is_exist_w = array(
                'manager_id' =>  $USER_KEY_ID,
                'template_id' =>  $id,
            ) ;
            $is_exist = Db::table('template_agency')->where($is_exist_w)->find();
            if($is_exist){
                $r = Db::table('template_agency')->where($is_exist_w)->update($edit_data);
            }else{
                $edit_data['template_id'] = $id;
                $edit_data['manager_id'] = $USER_KEY_ID;
                $edit_data['title'] = $info['title'] ? $info['title'] : '';
                $edit_data['pid'] = $info['pid'] ? $info['pid'] : "";
                $edit_data['stype'] = $info['stype'] ? $info['stype'] : "";
                $edit_data['level'] = $info['level'] ? $info['level'] : "";
                $r = Db::table('template_agency')->insert($edit_data);

            }
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }

    /**
     * 删除模板
     * id 模板id
     */
    public function del(Request $request){
        $id = $request->post('id');
        $level = $request->post('level');

        if($level == 1){ //一级菜单
            $r = Db::table('template')->where('id','=',$id)->delete();
            if(false === $r){
                return json()->data(['code'=>1,'message'=>'失败']);
            }
            Db::table('template')->where('pid','=',$id)->delete();
            //删除机构创建的模版
            Db::table('template_agency')->where('template_id','=',$id)->delete();
            Db::table('template_agency')->where('pid','=',$id)->delete();

        }else{  //二级菜单
            $info = Db::table('template')->where('id','=',$id)->find();

            if(!$id || !$info){
                return json()->data(['code'=>1,'message'=>'未知错误']);
            }
            $r = Db::table('template')->where('id','=',$id)->delete();
            if(false === $r){
                return json()->data(['code'=>1,'message'=>'失败']);
            }
            Db::table('template_agency')->where('template_id','=',$id)->delete();
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }
    public function get_template_data(Request $request){
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $manager_id = Db::table('manage_user')->where('id','=',$USER_KEY_ID)->value('pid');

        $stype = $request->post('stype');
        $where = array(
            'stype' =>$stype,
        );
        $list = Db::table('template')->where($where)->order('id asc')->select();
        foreach ($list as $key => $value){
            $info = Db::table('template_agency')
                ->where(array(
                    'template_id'=>$value['id'],
                    'stype' =>$stype,
                    'manager_id'=>$manager_id)
                )
                ->find();
            if($info){
                unset($info['template_id']);
                unset($info['manager_id']);
                $list[$key] = $info;
            }
        }
        $arr = [];

        foreach ($list as $k => $v){
            if($v['level'] == 1){
                foreach($list as $k1=>$v1){
                    if($v1['level'] == 2 && $v['id'] == $v1['pid']){
                        $v['sub_temp'][] = $v1;
                    }
                }
                $arr[] = $v;
            }
        }
        return json()->data(['code'=>0,'message'=>'成功','data'=>$arr]);
    }
}