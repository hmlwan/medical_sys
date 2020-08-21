<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/29
 * Time: 21:12
 */

namespace app\admin\controller;




use think\Db;
use think\Request;

class Task extends Admin
{

    public function taskList(){

        $service = new \app\common\entity\task();
        $data = $service->select();

        return $this->render('task', [
            'list' => $data
        ]);
    }
    public function taskadd(){

        return $this->render('taskadd');

    }
    private function checkInfo2($id)
    {
        $entity = \app\common\entity\task::where('id', $id)->find();
        if (!$entity) {
            throw new AdminException('对象不存在');
        }

        return $entity;
    }
    public function deletes2(){


        $entity = $this->checkInfo2($_REQUEST['id']);

        if (!$entity->delete()) {
            throw new AdminException('删除失败');
        }

        return json(['code' => 0, 'message' => 'success']);

    }

    public function taskadds(){

        if (!empty($_FILES['img']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('img');
            $aa = $uploadModel->upload();
            $data['img'] = $aa;
        }
        $data['url'] = $_POST['url'];
        $data['task_name'] = $_POST['task_name'];
        $data['reward_remark'] = $_POST['reward_remark'];
        $data['reward_rate'] = $_POST['reward_rate'];
        $data['complete_num'] = $_POST['complete_num'];
        $service = new \app\common\entity\task();
        $result = $service->addArticle($data);

        $this->success('新增成功', 'taskList');


    }
    public function sladds2(){
        $id = $_REQUEST['id'];
        $entity = \app\common\entity\task::where('id', $id)->find();
        if (!$entity) {
            $this->error('用户对象不存在');
        }

        return $this->render('taskadds', [
            'info' => $entity
        ]);


    }
    public function taskddss2(){

        if (!empty($_FILES['img']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('img');
            $aa = $uploadModel->upload();
            $data['img'] = $aa;
        }

        $data['url'] = $_POST['url'];
        $data['task_name'] = $_POST['task_name'];
        $data['reward_remark'] = $_POST['reward_remark'];
        $data['reward_rate'] = $_POST['reward_rate'];
        $data['complete_num'] = $_POST['complete_num'];
        $service = new \app\common\entity\task();
        $entity = $this->checkInfo2($_POST['id']);
        $result = $service->updateS($entity, $data);

        $this->success('修改成功', 'taskList');
    }
    public function taskStatus(Request $request){
        $service = new \app\common\entity\task();
        $status = $request->get('status');
        $id = $request->get('id');

        $r = $service->where('id',$id)->update(['status'=>$status]);

        if(!$r){
            return json(['code' => 1, 'message' => '失败', 'toUrl' => url('taskList')]);
        }
        return json(['code' => 0, 'message' => '成功', 'toUrl' => url('taskList')]);
    }
    public function taskSwitch(Request $request){
        $service = new \app\common\entity\task();
        $status = $request->get('invite_sub_switch');
        $id = $request->get('id');

        $r = $service->where('id',$id)->update(['invite_sub_switch'=>$status]);

        if(!$r){
            return json(['code' => 1, 'message' => '失败', 'toUrl' => url('taskList')]);
        }
        return json(['code' => 0, 'message' => '成功', 'toUrl' => url('taskList')]);
    }
    public function taskDetail(Request $request){
        $task_daily_id = $request->get('id');
        $map['id'] = $task_daily_id;
        $list = Db::table('task_daily_detail')->where('task_daily_id',$task_daily_id)
            ->order('id asc')->paginate(20,false,['query'=>$map]);

        return $this->render('taskDetail',['task_daily_id'=>$task_daily_id,'list'=>$list]);
    }
    public function taskDetailAdd(Request $request){

        $task_daily_id = $request->get('task_daily_id');

        if($request->isAjax()){
            $num = $request->post('num');
            if(!$num){
                return json(['code' => 1, 'message' => '不能为空']);
            }
            $num_arr = explode("+",$num);
            $num_arr = array_filter($num_arr);
            $task_daily_id = $request->post('task_daily_id');

            foreach ($num_arr as $v){
                $r = Db::table('task_daily_detail')->insert(
                    [
                     'num'=>$v,
                     'task_daily_id'=>$task_daily_id,
                    ]
                );
            }
            return json(['code' => 0, 'message' => '成功', 'toUrl' => url('taskDetail?id='.$task_daily_id)]);
        }else{

            return $this->render('taskDetailAdd',['task_daily_id'=>$task_daily_id]);
        }
    }

    public function taskDetailEdit(Request $request){

        $id = $request->get('id');
        if($request->isAjax()){
            $id = $request->post('id');
            $num = $request->post('num');
            $task_daily_id = $request->post('task_daily_id');
            $r = Db::table('task_daily_detail')->where('id',$id)->update(array('num'=>$num));

            if(!$r){
                return json(['code' => 1, 'message' => '失败']);
            }
            return json(['code' => 0, 'message' => '成功', 'toUrl' => url('taskDetail?id='.$task_daily_id)]);
        }else{
            $checkInfo = Db::table('task_daily_detail')->where('id',$id)->find();
            return $this->render('taskDetailEdit',['info'=>$checkInfo]);
        }
    }
    public function deletes3(Request $request){
        $id = $request->get('id');

        $r = Db::table('task_daily_detail')->delete($id);
        if (!$r) {
            throw new AdminException('删除失败');
        }

        return json(['code' => 0, 'message' => 'success']);

    }

}