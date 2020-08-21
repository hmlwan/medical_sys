<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\Config;
use app\common\entity\Invite;
use app\common\entity\User as userModel;
use app\common\entity\UserInviteCode;
use app\common\entity\UserMagicLog;
use app\common\entity\UserProduct;
use app\common\entity\UserStatistics;
use app\common\entity\UserStatisticsLog;
use app\common\service\Users\Identity;
use app\common\entity\Product as productModel;
use think\Db;
use think\Request;


class User extends Admin
{
    /**
     * @power 会员管理|会员列表
     * @rank 1
     */
    public function index(Request $request)
    {
        $entity = userModel::field('u.*,c.invite_code,s.one_sub_nums,s.two_sub_nums,s.total_sub_nums,s.contribution_nums')->alias('u');
        if ($level = $request->get('level')) {
            $entity->where('u.level', $level);
            $map['level'] = $level;
        }
        if ($keyword = $request->get('keyword')) {
            $type = $request->get('type');
            switch ($type) {
                case 'mobile':
                    $entity->where('u.mobile', $keyword);
                    break;
                case 'nick_name':
                    $entity->where('u.nick_name', $keyword);
                    break;
            }
            $map['type'] = $type;
            $map['keyword'] = $keyword;
        }
        $codeTable = (new UserInviteCode())->getTable();
        $list = $entity->leftJoin("$codeTable c", 'u.id = c.user_id')
            ->leftJoin("user_statistics s", 'u.id = s.user_id')
            ->order('u.register_time', 'desc')
            ->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);
        foreach ($list as &$value){
            if($value['pid']){
                $p_info = userModel::where('id',$value['pid'])->find();
                $value['p_mobile'] =  $p_info['mobile']?$p_info['mobile']:'';
                if($p_info['pid']){
                    $pp_info = userModel::where('id',$p_info['pid'])->find();
                    $value['pp_mobile'] =  $pp_info['mobile']?$pp_info['mobile']:'';
                }
            }
        }

        return $this->render('index', [
            'list' => $list
        ]);
    }

    /**
     * @power 会员管理|充值明细
     * @method GET
     */
    public function magicList(Request $request)
    {
        $entity = UserMagicLog::alias('um')->field('um.*,u.mobile');
        if ($keyword = $request->get('keyword')) {
            $entity->where('u.mobile', $keyword);
            $map['keyword'] = $keyword;
        }


        if ($type = $request->get('type')) {
            $entity->where('um.types', $type);
            $map['type'] = $type;

        }
        $entity->where('um.magic','>',"0");
        $userTable = (new \app\common\entity\User())->getTable();

        $list = $entity->leftJoin("{$userTable} u", 'um.user_id = u.id')
            ->order('um.create_time', 'desc')
            ->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);

        return $this->render('magic', [
            'list' => $list
        ]);
    }
    /**
     * @power 会员管理|贡献明细
     * @method GET
     */
    public function contribution(Request $request)
    {
        $entity = UserMagicLog::alias('um')->field('um.*,u.mobile');
        if ($keyword = $request->get('keyword')) {
            $entity->where('u.mobile', $keyword);
            $map['keyword'] = $keyword;
        }

        $entity->where('um.types', 4);

        $entity->where('um.magic','>',"0");
        $userTable = (new \app\common\entity\User())->getTable();

        $list = $entity->leftJoin("{$userTable} u", 'um.user_id = u.id')
            ->order('um.create_time', 'desc')
            ->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);

        return $this->render('contribution', [
            'list' => $list
        ]);
    }
    /**
     * @power 会员管理|数据明细
     * @method GET
     */
    public function statistics(Request $request)
    {
        $entity = UserStatisticsLog::alias('us')->field('us.*,u.mobile');
        if ($keyword = $request->get('keyword')) {
            $entity->where('u.mobile', $keyword);
            $map['keyword'] = $keyword;
        }

        $entity->where('us.magic','>',"0");
        $userTable = (new \app\common\entity\User())->getTable();

        $list = $entity->leftJoin("{$userTable} u", 'us.user_id = u.id')
            ->order('us.create_time', 'desc')
            ->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);

        return $this->render('statistics', [
            'list' => $list
        ]);
    }
    /**
     * @power 会员管理|空间明细
     * @method GET
     */
    public function magicboxlist(Request $request)
    {
        $entity = Db::table('product_record')->alias('up')
            ->field('up.*,u.mobile,p.product_name,p.candy_num,p.return_candy_num,p.energy_num');

        if ($keyword = $request->get('keyword')) {
            $entity->where('u.mobile', $keyword);
            $map['keyword'] = $keyword;
        }

        if ($types = $request->get('type')) {
            $entity->where('up.types', $types);
            $map['types'] = $types;
        }

        if ($product_id = $request->get('product_id')) {
            $entity->where('up.product_id', $product_id);
            $map['product_id'] = $product_id;
        }

        if ($status = $request->get('status')) {
            if ($status == 2) {
                $entity->where('up.is_receive', 0);
            } elseif ($status == 1) {
                $entity->where('up.is_receive', $status);
            }
            $map['is_receive'] = $status;
        }

        $list = $entity->leftJoin("user u", 'up.user_id = u.id')
            ->leftJoin("product p", 'up.product_id = p.id')
            ->order('up.add_time', 'desc')
            ->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);
        $page = $list->render();
        $list = ( $list->toArray())['data'];
        $product_list = \app\common\entity\Product::getProductConf(array('status'=>1));
        // dump($list);die;
        foreach ($list as &$value){
            $value['period_time'] = $value['add_time'] + $value['period']* 24*3600;
        }
        // dump($productList);die;
        return $this->render('magicboxlist', [
            'list' => $list,'page'=>$page,'product_list'=>$product_list
        ]);
    }

    /**
     * @power 会员管理|会员列表@添加会员
     */
    public function create()
    {
        return $this->render('edit');
    }

    /**
     * @power 会员管理|会员列表@编辑会员
     */
    public function edit($id)
    {
        $entity = userModel::where('id', $id)->find();
        if (!$entity) {
            $this->error('用户对象不存在');
        }

        return $this->render('edit', [
            'info' => $entity,
        ]);
    }

    /**
     * @power 会员管理|会员列表@充值晶石
     * @method GET
     */
    public function recharge($id)
    {
        $entity = userModel::where('id', $id)->find();
        if (!$entity) {
            $this->error('用户对象不存在');
        }

        return $this->render('recharge', [
            'info' => $entity,
        ]);
    }

    /**
     * @power 会员管理|会员列表@充值晶石
     * @method POST
     */
    public function saveRecharge($id, Request $request)
    {
        $magic = $request->post('magic');
        if (!preg_match('/^[0-9]+.?[0-9]*$/', $magic)) {
            throw new AdminException('输入的数量必须为正整数或者小数');
        }

        $remark = $request->post("remark");
        $entity = $this->checkInfo($id);

        $model = new UserMagicLog();
        $result = $model->addInfo($id, $remark, $magic, $entity->magic, $entity->magic+$magic, 17);
        
        $entity = userModel::where('id', $id)->find();
        $entity->magic = $entity->magic+$magic;
        $entity->save();
        if (!$result) {
            throw new AdminException('充值失败');
        }
        return ['code' => 0, 'message' => '充值成功'];
    }

    /**
     * @power 会员管理|会员列表@充值空间
     * @method GET
     */
    public function rechargemagic($id)
    {
        $entity = userModel::where('id', $id)->find();
        if (!$entity) {
            $this->error('用户对象不存在');
        }

        //得到所有的空间
        $productList = productModel::select(); 

        return $this->render('rechargemagic', [
            'info' => $entity,
            'productList' => $productList
        ]);
    }

    /**
     * @power 会员管理|会员列表@充值晶石
     * @method POST
     */
    public function saveMagic(Request $request)
    {
        // $magic = $request->post('magic');
        // if (!preg_match('/^[0-9]+?[0-9]*$/', $magic)) {
        //     throw new AdminException('输入的数量必须为正整数');
        // }

        $id = $request->post('id');
        $entity = $this->checkInfo($id);
        if(!$entity){
            throw new AdminException('用户不存在');
        }
        $product_id = $request->post('product_id');

        $model = new UserProduct();
        $result = $model->addInfo($id, $product_id, 1);
        if (!$result) {
            throw new AdminException('充值失败');
        }
        

        return ['code' => 0, 'message' => '充值成功'];
    }

    /**
     * @power 会员管理|会员列表@添加会员
     */
    public function save(Request $request)
    {
        $result = $this->validate($request->post(), 'app\admin\validate\UserForm');

        if (true !== $result) {
            return json()->data(['code' => 1, 'message' => $result]);
        }

        $service = new \app\common\service\Users\Service();
        if ($service->checkMobile($request->post('mobile'))) {
            throw new AdminException('电话号码已被注册,请重新填写');
        }

        Db::startTrans();
        try {
            $userId = $service->addUser($request->post());

            if (!$userId) {
                throw new \Exception('保存失败');
            }

            $inviteCode = new UserInviteCode();
            if (!$inviteCode->saveCode($userId)) {
                throw new \Exception('保存失败');
            }

            Db::commit();
            return json(['code' => 0, 'toUrl' => url('/admin/user')]);

        } catch (\Exception $e) {
            Db::rollback();
            throw new AdminException($e->getMessage());
        }


    }

    /**
     * @power 会员管理|会员列表@编辑会员
     */
    public function update(Request $request, $id)
    {
        $entity = $this->checkInfo($id);

        $result = $this->validate($request->post(), 'app\admin\validate\UserEditForm');

        if (true !== $result) {
            return json()->data(['code' => 1, 'message' => $result]);
        }

        $service = new  \app\common\service\Users\Service();
        $result = $service->updateUser($entity, $request->post());

        if (!$result) {
            throw new AdminException('保存失败');
        }

        return json(['code' => 0, 'toUrl' => url('/admin/user')]);

    }

    /**
     * @power 会员管理|会员列表@禁用会员
     */
    public function delete($id)
    {
        $entity = $this->checkInfo($id);

        $entity->forbidden_time = time();
        $entity->status = \app\common\entity\User::STATUS_FORBIDDED;

        if (!$entity->save()) {
            throw new AdminException('禁用失败');
        }

        return json(['code' => 0, 'message' => 'success']);
    }
    /**
     * @power 会员管理|会员列表@禁用会员交易
     */
    public function deletetrade($id)
    {
        $entity = $this->checkInfo($id);

        $entity->order_status = \app\common\entity\User::STATUS_ORDER_FORBIDDED;

        if (!$entity->save()) {
            throw new AdminException('禁用失败');
        }

        return json(['code' => 0, 'message' => 'success']);
    }
    /**
     * @power 会员管理|会员列表@删除会员
     */
    public function deleteuser($id)
    {
        $id = (int)$id;
        if(!$id)
        {
            return json(['code' => 1, 'message' => '会员id不存在']);
        }
        $entity = $this->checkInfo($id);

        if (!$entity) {
            throw new AdminException('会员不存在');
        }

        Db::startTrans();
        try {
            //删除会员
            $deleteuser = Db::table('user')->where('id', $id)->delete();
            if(!$deleteuser)
            {
                throw new AdminException('会员删除失败');
            }
            //删除会员邀请码
            $deletecode = Db::table('user_invite_code')->where('user_id', $id)->delete();
            if(!$deletecode)
            {
                throw new AdminException('会员邀请码删除失败');
            }
            //删除会员晶石记录
            $ismagic = Db::table('user_magic_log')->where('user_id', $id)->find();
            $deletemagic = Db::table('user_magic_log')->where('user_id', $id)->delete();
            if(!$deletemagic && $ismagic)
            {
                throw new AdminException('会员晶石记录删除失败');
            }
            //删除会员产品
            $deleteproduct = Db::table('user_product')->where('user_id', $id)->delete();
            if(!$deleteproduct)
            {
                throw new AdminException('会员产品删除失败');
            }
            //删除会员订单
            $deleteorder = Db::table('orders')->where('user_id', $id)->delete();
            $isorder = Db::table('orders')->where('user_id', $id)->find();
            if(!$deleteorder && $isorder)
            {
                throw new AdminException('会员订单删除失败');
            }
            //删除会员订单关联数据
            $deleteordercomment = Db::table('order_comment')->where('user_id', $id)->delete();
            $isordercomment= Db::table('order_comment')->where('user_id', $id)->find();
            if(!$deleteordercomment && $isordercomment)
            {
                throw new AdminException('会员订单关联数据删除失败');
            }

            Db::commit();
            return json(['code' => 0, 'toUrl' => url('/admin/user')]);

        } catch (\Exception $e) {
            Db::rollback();
            throw new AdminException($e->getMessage());
        }

        return json(['code' => 0, 'message' => 'success']);
    }

    /**
     * @power 会员管理|会员列表@解禁会员
     * @method POST
     */
    public function unforbidden($id)
    {
        $entity = $this->checkInfo($id);

        $entity->forbidden_time = 0;
        $entity->status = \app\common\entity\User::STATUS_DEFAULT;

        if (!$entity->save()) {
            throw new AdminException('解禁失败');
        }
        return json(['code' => 0, 'message' => 'success']);
    }
    /**
     * @power 会员管理|会员列表@解禁会员交易
     * @method POST
     */
    public function unforbiddentrade($id)
    {
        $entity = $this->checkInfo($id);


        $entity->order_status = \app\common\entity\User::STATUS_ORDER_DEFAULT;

        if (!$entity->save()) {
            throw new AdminException('解禁失败');
        }
        return json(['code' => 0, 'message' => 'success']);
    }
    /**
     * @power 会员管理|会员列表@认证会员
     * @method GET
     */
    public function certification($id)
    {
        $entity = userModel::where('id', $id)->find();
        if (!$entity) {
            $this->error('用户对象不存在');
        }

        return $this->render('certification', [
            'info' => $entity,
        ]);
    }

    /**
     * @power 会员管理|会员列表@认证会员
     * @method POST
     */
    public function certificationPass(Request $request, $id, $status)
    {
        //获取缓存用户详细信息
        $identity = new Identity();
        $userInfo = $identity->getUserInfo($id);

        $entity = $this->checkInfo($id);
        if (!$status) {
            return json(['code' => 0, 'message' => '状态不对']);
        }
        $certification_fail = $request->post("certification_fail");
        if($status == 1){
            $entity->is_certification = $status;
            $entity->certification_fail = $certification_fail;

            if (!$entity->save()) {
                throw new AdminException('认证失败');
            }
            //实名奖励
            $register_send_product_switch = \app\common\entity\Config::getValue('register_send_product_switch');
            $register_send_product = \app\common\entity\Config::getValue('register_send_product');

            if($register_send_product_switch == 1){
                $w[] = ['id','=',$register_send_product];
                $prod_info = \app\common\entity\Product::getOneProduct($w)->toArray();

                if($prod_info){
                    $prod_data = array(
                        'user_id' => $userInfo->user_id,
                        'product_id' => $register_send_product,
                        'period' => $prod_info['period'],
                        'is_receive' => 0,
                        'candy_num' =>  $prod_info['candy_num'],
                        'return_candy_num' =>  $prod_info['return_candy_num'],
                        'energy_num' =>  $prod_info['energy_num'],
                        'types' =>  3,
                    );

                    \app\common\entity\Product::addProductRecord($prod_data);
                    //增加算力
                    \app\common\entity\User::setIncEnergy($userInfo->user_id,$prod_info['energy_num']);
                }
            }
            $this->addCertInviteRecord($entity);
            $identity->delCache($id);
        }else{

            $r = \app\common\entity\User::where(array('id'=>$userInfo->user_id))->update(array(
                'is_certification'=>$status,
                'certification_fail'=>$certification_fail,
            ));
            if(!$r){
                throw new AdminException('认证失败');
            }
        }
        return json(['code' => 0, 'message' => '操作成功','toUrl'=>url('index')]);
    }
    /*下线实名*/
    public function addCertInviteRecord($user){

        $user_m = new \app\common\entity\User();
        $invite_m = new \app\common\entity\Invite();
        $parent_info_1 = $user_m->getParentInfo($user->pid);
        if($parent_info_1 && $parent_info_1['is_certification'] == 1){
            $num1 = Config::getValue('level_1_cert_reward');
            $where1['user_id'] =   $parent_info_1['id'];
            $where1['sub_user_id'] =   $user->id;
            $entity1 = $invite_m::where($where1)->find();
            $data1 = array(
                'user_id'=>$parent_info_1['id'],
                'sub_user_id'=>$user->id,
                'content' => '一级下线实名奖励'.$num1.'云链',
                'num' => $num1,
                'type'=>1,
                'level'=>1,
                'is_cert'=>2,
            );
            if($entity1){
                $invite_m->update($data1,$where1);
            }else{
                $invite_m->saveRecord($data1);
            }
            //收益记录
            $magic_data1 = array(
                'magic' => $num1,
                'remark' => '一级下线实名奖励'.$num1.'云链',
                'type'=>3,
            );
            $magic_log1 = UserMagicLog::changeUserMagic($parent_info_1['id'],$magic_data1,1);

            $parent_info_2 = $user_m->getParentInfo($parent_info_1['pid']);
            if($parent_info_2 && $parent_info_1['is_certification'] == 1){
                $num2 = Config::getValue('level_2_cert_reward');;
                $data2 = array(
                    'user_id'=>$parent_info_2['id'],
                    'sub_user_id'=>$user->id,
                    'content' => '二级下线实名奖励'.$num2.'云链',
                    'num' => $num2,
                    'type'=>1,
                    'level'=>2,
                    'is_cert'=>2,
                );
                $where2['user_id'] =   $parent_info_2['id'];
                $where2['sub_user_id'] =   $user->id;
                $entity2 = Invite::where($where2)->find();
                if($entity2){
                    $invite_m->update($data2,$where2);
                }else{
                    $invite_m->saveRecord($data2);
                }
                //收益记录
                $magic_data2 = array(
                    'magic' => $num2,
                    'remark' => '二级下线实名奖励'.$num2.'云链',
                    'type'=>3,
                );
                $magic_log2 = UserMagicLog::changeUserMagic($parent_info_2['id'],$magic_data2,1);
            }
        }
    }
    /**
     * @power 会员管理|会员列表@手动升级
     * @method POST
     */
    public function level(Request $request)
    {
        if ($request->isPost()) {
            $userId = intval($request->post('user_id'));
            $level = intval($request->post('level'));
            $isReward = intval($request->post('is_reward'));

            $user = \app\common\entity\User::where('id', $userId)->find();
            if (!$user) {
                throw new AdminException('会员不存在');
            }
            if ($user->level == $level) {
                throw new AdminException('会员已是lv' . $level);
            }
            //直接升级
            $user->level = $level;
            if (!$user->save()) {
                throw new AdminException('升级失败');
            }
            //升级处理
            if ($isReward) {
                //赠送奖励
                $model = new \app\common\service\Level\Service();
                $reward = $model->getReward($level);
                $model->sendUserProduct($reward['product_id'], $reward['number'], $user->id);

            }
            return json(['code' => 0, 'message' => '升级成功']);
        }
    }


    private function checkInfo($id)
    {
        $entity = userModel::where('id', $id)->find();
        if (!$entity) {
            throw new AdminException('对象不存在');
        }

        return $entity;
    }
}