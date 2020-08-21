<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\Product as productModel;
use think\Db;
use think\Request;

class Product extends Admin
{
    /**
     * @power 产品管理|空间列表
     * @rank 3
     */
    public function index(Request $request)
    {
        return $this->render('index', [
            'list' => productModel::paginate(15)
        ]);
    }

    /**
     * @power 产品管理|空间列表@上架空间
     * @method POST
     */
    public function downShelve($id)
    {
        $entity = $this->checkInfo($id);
        $entity->status = 1;

        if (!$entity->save()) {
            throw new AdminException('上架失败');
        }
        return json(['code' => 0, 'message' => 'success']);
    }

    /**
     * @power 产品管理|空间列表@编辑空间
     */
    public function edit(Request $request)
    {

        $id = $request->get('id');
        if($id){
            $entity = productModel::where('id', $id)->find();
            if (!$entity) {
                $this->error('对象不存在');
            }
        }

        return $this->render('edit', [
            'info' => $entity,
        ]);
    }

    /**
     * @power 产品管理|空间列表@下架空间
     * @method POST
     */
    public function shelve($id)
    {
        $entity = $this->checkInfo($id);
        $entity->status = 0;

        if (!$entity->save()) {
            throw new AdminException('下架失败');
        }
        return json(['code' => 0, 'message' => 'success']);
    }

    private function checkInfo($id)
    {
        $entity = productModel::where('id', $id)->find();
        if (!$entity) {
            throw new AdminException('对象不存在');
        }

        return $entity;
    }

    /**
     * @power 产品管理|空间列表@编辑空间
     */
    public function update(Request $request)
    {

        $id = $request->post('id');
        $data = [];
        if (!empty($_FILES['logo_url']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('logo_url');
            $aa = $uploadModel->upload();
            $data['logo_url'] = $aa;
        }
        $data['product_name'] =$request->post('product_name');
        $data['candy_num'] =$request->post('candy_num');
        $data['period'] =$request->post('period');
        $data['energy_num'] =$request->post('energy_num');
        $data['hold_num'] =$request->post('hold_num');
        $data['return_candy_num'] =$request->post('return_candy_num');
        $data['bg_color'] =$request->post('bg_color');
        $data['level_one_return_candy'] =$request->post('level_one_return_candy');
        $data['level_two_return_candy'] =$request->post('level_two_return_candy');
        if($id){
            $r = productModel::where('id', $id)->update($data);
        }else{
            $r = productModel::create($data);
        }
        if($r){
            $this->success('成功', 'product/index');
        }else{
            $this->error('失败');

        }
    }
}