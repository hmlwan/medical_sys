<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\ManageGroup;
use app\common\entity\ManageUser;
use app\common\entity\ManageUserGroup;
use think\Db;
use think\facade\Session;
use think\Request;


class Manage extends Admin
{
    /**
     * @power 权限管理|用户管理
     * @rank 1
     */
    public function index(Request $request)
    {
        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $AUTH_TYPE = Session::get('AUTH_TYPE');

        $p = $request->get('p',1);
        $page_num = $request->get('page_num',10);
        $offset = ($p-1) * $page_num;
        $where = array();
        if($AUTH_TYPE == 1){
            $where['pid'] = $USER_KEY_ID;
        }elseif ($AUTH_TYPE == -1){
            $where['pid'] = $USER_KEY_ID;
        }

        $total =  Db::table('manage_user')->where($where)->count();
        $list = Db::table('manage_user')
            ->limit($offset,$page_num)
            ->where($where)
            ->field('id,manage_name,real_name,auth_type,forbidden_time,sign_img,status')
            ->order('id desc')->select();
        foreach ($list as &$value){
            $value['auth_type_name'] = ManageUser::getType($value['auth_type']);
        }
        $data = array(
            'total' => $total,
            'list' => $list,
        );
        return json()->data(['code'=>0,'data'=>$data]);

    }

    /**
     * @power 权限管理|用户管理@添加用户
     */
    public function create()
    {
        return $this->render('edit', [
            'groups' => ManageGroup::all()
        ]);
    }

    /**
     * @power 权限管理|用户管理@编辑用户
     */
    public function edit(Request $request)
    {
        $id = $request->post('id');
        $manage_name = $request->post('manage_name');
        $real_name = $request->post('real_name');
        $auth_type = $request->post('auth_type');
        $password = $request->post('password');
        $sign_img = $request->post('sign_img');
        $forbidden_time = $request->post('forbidden_time');
        $status = $request->post('status',1);
        $AUTH_TYPE = $AUTH_TYPE = Session::get('AUTH_TYPE');
        if($AUTH_TYPE != -1  && $AUTH_TYPE != 1){
            return json()->data(['code'=>1,'message'=>'无操作权限']);
        }

        if(!$manage_name){
            return json()->data(['code'=>1,'message'=>'请输入用户名']);
        }
        if($auth_type == 0){
            return json()->data(['code'=>1,'message'=>'请选择权限']);
        }
        $service = new \app\admin\service\rbac\Users\Service();
        $USER_KEY_ID = Session::get('USER_KEY_ID');


        $add_data = array(
            'manage_name' => $manage_name,
            'auth_type' => $auth_type,
            'real_name' => $real_name,
            'sign_img' => $sign_img,
            'forbidden_time' => $forbidden_time,
            'status' => $status,
            'update_time' => time(),
        );
        if($password){
            $password_salt = $service->getPasswordSalt();
            $password = $service->getPassword($request->post('password'), $password_salt);
            $add_data['password'] = $password;
            $add_data['password_salt'] = $password_salt;
        }
        if($id > 0){
            $r = Db::table('manage_user')
                ->where('id','=',$id)
                ->update($add_data);

        }else{
            $add_data['create_time'] = time();
            $add_data['pid'] = $USER_KEY_ID;
            if(!$password){
                return json()->data(['code'=>1,'message'=>'请输入密码']);
            }
            $r = Db::table('manage_user')->insert($add_data);
        }
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }
    public function edit_pass(Request $request){

        $USER_KEY_ID = Session::get('USER_KEY_ID');
        $oldpass = $request->post('oldpass');
        $newpass = $request->post('newpass');
        $repass = $request->post('repass');
        $service = new \app\admin\service\rbac\Users\Service();
        if(!$oldpass){
            return json()->data(['code'=>1,'message'=>'请输入旧密码']);
        }
        if($newpass != $repass){
            return json()->data(['code'=>1,'message'=>'密码不一致']);
        }


        $entity = ManageUser::get($USER_KEY_ID);

        if(!$service->checkPassword($oldpass,$entity)){
            return json()->data(['code'=>1,'message'=>'旧密码错误']);
        }
        $password_salt = $service->getPasswordSalt();
        $password = $service->getPassword($newpass, $password_salt);
        $add_data['password'] = $password;
        $add_data['password_salt'] = $password_salt;
        $r = $entity->where('id','=',$USER_KEY_ID)->update($add_data);
        if(false === $r){
            return json()->data(['code'=>1,'message'=>'失败']);
        }
        return json()->data(['code'=>0,'message'=>'成功']);
    }

    /**
     * @power 权限管理|用户管理@添加用户
     */
    public function save(Request $request)
    {
        $result = $this->validate($request->post(), 'app\admin\validate\ManageForm');

        if (true !== $result) {
            return json()->data(['code' => 1, 'message' => $result]);
        }

        $service = new \app\admin\service\rbac\Users\Service();
        if ($service->checkName($request->post('name'))) {
            throw new AdminException('用户名已被注册,请重新填写');
        }

        $groupIds = $request->post()['groupIds'];

        Db::startTrans();
        try {
            $entity = new ManageUser();
            $entity->manage_name = $request->post('name');
            $entity->password_salt = $service->getPasswordSalt();
            $entity->password = $service->getPassword($request->post('password'), $entity->getPasswordSalt());

            if (!$entity->save()) {
                throw new \Exception('保存失败');
            }

            foreach ($groupIds as $groupId) {
                $result = ManageUserGroup::addInfo($entity->getId(), $groupId);
                if (!$result) {
                    throw new \Exception('保存失败');
                }
            }

            Db::commit();

            return json(['code' => 0, 'toUrl' => url('/admin/manage')]);
        } catch (\Exception $e) {
            Db::rollback();

            throw new AdminException($e->getMessage());
        }
    }

    /**
     * @power 权限管理|用户管理@编辑用户
     * @method GET
     */
    public function update(Request $request, $id)
    {
        $entity = $this->checkInfo($id);

        $result = $this->validate($request->post(), 'app\admin\validate\ManageEditForm');

        if (true !== $result) {
            return json()->data(['code' => 1, 'message' => $result]);
        }

        $service = new \app\admin\service\rbac\Users\Service();
        if ($service->checkName($request->input('name'), $id)) {
            throw new AdminException('用户名已被注册,请重新填写');
        }

        $groupIds = $request->post()['groupIds'];

        Db::startTrans();
        try {
            $entity->manage_name = $request->post('name');
            if ($password = $request->post('password')) {
                $entity->password = $service->getPassword($password, $entity->getPasswordSalt());
            }

            if ($entity->save() === false) {
                throw new \Exception('保存失败');
            }

            foreach ($groupIds as $groupId) {
                $result = ManageUserGroup::addInfo($entity->getId(), $groupId);
                if (!$result) {
                    throw new \Exception('保存失败');
                }
            }

            Db::commit();

            return json(['toUrl' => url('/admin/manage')]);
        } catch (\Exception $e) {
            Db::rollback();

            throw new AdminException($e->getMessage());
        }

    }

    /**
     * @power 权限管理|用户管理@禁用用户
     */
    public function delete($id)
    {
        $entity = $this->checkInfo($id);

        $entity->forbidden_time = time();

        if (!$entity->save()) {
            throw new AdminException('禁用失败');
        }

        return json(['code' => 0, 'message' => 'success']);
    }

    /**
     * @power 权限管理|用户管理@解禁用户
     * @method POST
     */
    public function unforbidden($id)
    {
        $entity = $this->checkInfo($id);

        $entity->forbidden_time = 0;

        if (!$entity->save()) {
            throw new AdminException('解禁失败');
        }
        return json(['code' => 0, 'message' => 'success']);
    }

    private function checkInfo($id)
    {
        $entity = ManageUser::where('id', $id)->find();
        if (!$entity) {
            throw new AdminException('对象不存在');
        }
        if ($entity->isDefault()) {
            throw new AdminException('默认用户不能编辑');
        }

        return $entity;
    }
}