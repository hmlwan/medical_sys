<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
use app\common\entity\UserArticleRecord;
use think\Db;
use think\Request;

class Article extends Admin
{
    /**
     * @power 内容管理|文章管理
     * @rank 5
     */
    public function index(Request $request)
    {
        $entity = \app\common\entity\Article::field('*');
        if ($cate = $request->get('cate')) {
            $entity->where('category', $cate);
            $map['cate'] = $cate;
        }

        $list = $entity->paginate(15, false, [
            'query' => isset($map) ? $map : ''
        ]);

        return $this->render('index', [
            'list' => $list,
            'cate' => \app\common\entity\Article::getAllCate()
        ]);
    }

    /**
     * @power 内容管理|文章管理@添加文章
     */
    public function create()
    {
        return $this->render('edit',[
            'cate' => \app\common\entity\Article::getAllCate()
        ]);
    }

    /**
     * @power 内容管理|文章管理@编辑文章
     */
    public function edit($id)
    {
        $entity = \app\common\entity\Article::where('article_id', $id)->find();
        if (!$entity) {
            $this->error('用户对象不存在');
        }

        return $this->render('edit', [
            'info' => $entity,
            'cate' => \app\common\entity\Article::getAllCate()
        ]);
    }

    /**
     * @power 内容管理|文章管理@添加文章
     */
    public function save(Request $request)
    {
        $res = $this->validate($request->post(), 'app\admin\validate\Article');

        if (true !== $res) {
            $this->error($res);

        }
        if (!empty($_FILES['img']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('img');
            $aa = $uploadModel->upload();
            $data['img'] = $aa;
        }
        $data['category'] = $request->post('category');
        $data['title'] = $request->post('title');
        $data['content'] = $request->post('content');
        $data['status'] = $request->post('status');
        $data['sort'] = $request->post('sort');
        $service = new \app\common\entity\Article();
        $result = $service->addArticle($data);



        if (!$result) {
            $this->error('保存失败');
        }
        //添加记录
        $article_id = $service->getLastInsID();
        $data['article_id'] = $article_id;
        UserArticleRecord::add_reocrd($data);

        $this->success('保存成功','/admin/article/index');
    }

    /**
     * @power 内容管理|文章管理@编辑文章
     */
    public function update(Request $request, $id)
    {

        $res = $this->validate($request->post(), 'app\admin\validate\Article');

        if (true !== $res) {
            $this->error($res);
        }

        if (!empty($_FILES['img']['tmp_name'])) {
            $uploadModel = new \app\common\service\Upload\Service('img');
            $aa = $uploadModel->upload();
            $data['img'] = $aa;
        }
        $data['content'] = $request->post('content');
        if(!$data['content']){
            $data['content'] = $request->post('editor');
        }

        $data['category'] = $request->post('category');
        $data['title'] = $request->post('title');
        $data['status'] = $request->post('status');
        $data['sort'] = $request->post('sort');
        $entity = $this->checkInfo($id);

        $service = new \app\common\entity\Article();
        $result = $service->updateArticle($entity, $data);

        if (!$result) {
            $this->error('保存失败');
        }
        $data['article_id'] = $id;
        UserArticleRecord::add_reocrd($data);
        $this->success('保存成功','/admin/article/index');
    }

    /**
     * @power 内容管理|文章管理@删除文章
     */
    public function delete(Request $request, $id)
    {
        $entity = $this->checkInfo($id);

        if (!$entity->delete()) {
            throw new AdminException('删除失败');
        }

        return json(['code' => 0, 'message' => 'success']);

    }

    private function checkInfo($id)
    {
        $entity = \app\common\entity\Article::where('article_id', $id)->find();
        if (!$entity) {
            throw new AdminException('对象不存在');
        }

        return $entity;
    }


    /**
     * @power 内容管理|反馈列表
     * @method GET
     */
    public function messageList(Request $request)
    {
        $entity = \app\common\entity\Message::field('m.*,u.mobile, u.nick_name')->alias('m');
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
        $list = $entity->leftJoin("user u", 'm.user_id = u.id')
            ->order('m.create_time', 'desc')
            ->paginate(15, false, [
                'query' => isset($map) ? $map : []
            ]);
        return $this->render('messageList', [
            'list' => $list
        ]);
    }

    /**
     * @power 内容管理|反馈列表@删除留言
     * @method GET
     */
    public function deleteMsg(Request $request)
    {
        $entity = \app\common\entity\Message::where("message_id", $request->get("id"))->delete();
        return json(['code' => 0, 'toUrl' => url('/admin/article/messageList')]);
    }

    /**
     * @param Request $request
     * @return mixed
     * 回复消息
     */
    public function replyMsg(Request $request){
        $entity = \app\common\entity\Message::where("message_id", $request->get("message_id"))->find();
        return $this->render('replyMsg',['info'=>$entity]);
    }


    public function reply(Request $request){

        $id = $request->post('message_id');
        $reply = $request->post('reply');
        if(!$id){
            $this->error('留言不存在');
        }
        if(!$reply){
            $this->error('回复内容不能为空');
        }
        $message_m = new \app\common\entity\Message();
        $data = array(
            'reply' => $reply
        );
        $r = $message_m->save($data,['message_id'=>$id]);
        if(!$r){
            $this->error('回复失败');
        }
        $this->success('回复成功','messageList');
    }

}