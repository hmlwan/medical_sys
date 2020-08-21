<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
use think\Request;

class Config extends Admin
{
    /**
     * @power 系统配置|网站配置
     * @rank 1
     */
    public function index()
    {
        return $this->render('index', [
            'list' => \app\common\entity\Config::where('type', 1)->order('sort asc,id asc')->select()
        ]);
    }

    /**
     * @power 系统配置|参数配置
     * @method GET
     */
    public function show()
    {
        return $this->render('show', [
            'list' => \app\common\entity\Config::where('type', 2)->order('sort asc,id asc')->select()
        ]);
    }

    /**
     * @power 系统配置|交易配置
     * @method GET
     */
    public function market()
    {
        return $this->render('market', [
            'list' => \app\common\entity\Config::where('type', 3)->order('sort asc,id asc')->select()
        ]);
    }


     public function sl(){
       
        $service = new \app\common\entity\Sl();
        $data = $service->select();
         return $this->render('sl', [
            'list' => \app\common\entity\Sl::select()
        ]);
     }
    public function sladds(){
          $id = $_REQUEST['id'];
          $entity = \app\common\entity\Sl::where('id', $id)->find();
        if (!$entity) {
            $this->error('用户对象不存在');
        }

        return $this->render('sladds', [
            'info' => $entity
        ]);

        
     }

     public function sladdss()
    {

        if (!empty($_FILES['img']['tmp_name'])) {
                  $uploadModel = new \app\common\service\Upload\Service('img');
                  $aa = $uploadModel->upload();
                  $data['img'] = $aa;
        }
           
        $data['addtime'] = date('Y-m-d H:i:s',time());
        $data['url'] = $_POST['url'];
        $data['types'] = $_POST['types'];

        $service = new \app\common\entity\Sl();
        $entity = $this->checkInfo($_POST['id']);
        $result = $service->updateS($entity, $data);

        $this->success('修改成功', 'config/sl');

    }


    public function deletes(){


        $entity = $this->checkInfo($_REQUEST['id']);

        if (!$entity->delete()) {
            throw new AdminException('删除失败');
        }

        return json(['code' => 0, 'message' => 'success']);

    }


     private function checkInfo($id)
    {
        $entity = \app\common\entity\Sl::where('id', $id)->find();
        if (!$entity) {
            throw new AdminException('对象不存在');
        }

        return $entity;
    }



     public function sladd(){
    
            return $this->render('sladd');
        
     }

     public function adds(){


         
            if (!empty($_FILES['img']['tmp_name'])) {
                  $uploadModel = new \app\common\service\Upload\Service('img');
                  $aa = $uploadModel->upload();
                  $data['img'] = $aa;
            }
           
            $data['addtime'] = date('Y-m-d H:i:s',time());
            $data['url'] = $_POST['url'];
            $data['types'] = $_POST['types'];

          

            $service = new \app\common\entity\Sl();
            $result = $service->addArticle($data);

            $this->success('新增成功', 'config/sl');
            
          
     }

    /**
     * @power 系统配置|网站配置@修改配置
     */
    public function save(Request $request)
    {
        $key = $request->post('key');
        $value = $request->post('value');
        $config = \app\common\entity\Config::where('key', $key)->find();
        if (!$config) {
            throw new AdminException('操作错误');
        }
        $config->value = $value;
        if ($config->save() === false) {
            throw new AdminException('修改失败');
        }
        return ['code' => 0, 'message' => '配置成功'];
    }

    /**
     * @power 系统配置|日志列表
     * @method GET
     */
    public function logList(Request $request)
    {
        $entity = \app\common\entity\Log::field('*');
        if ($type = $request->get('type')) {
            $entity->where('type', $type);
            $map['type'] = $type;
        }
        $list = $entity->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);
        return $this->render('logList', [
            'list' => $list
        ]);
    }




}