<?php
namespace app\admin\controller;

use app\admin\exception\AdminException;
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
            return json()->data(['code' => 1, 'message' => $res]);
        }

        $service = new \app\common\entity\Article();
        $result = $service->addArticle($request->post());

        if (!$result) {
            throw new AdminException('保存失败');
        }

        return json(['code' => 0, 'toUrl' => url('/admin/article')]);
    }

    /**
     * @power 内容管理|文章管理@编辑文章
     */
    public function update(Request $request, $id)
    {

        $res = $this->validate($request->post(), 'app\admin\validate\Article');

        if (true !== $res) {
            return json()->data(['code' => 1, 'message' => $res]);
        }


        $entity = $this->checkInfo($id);

        $service = new \app\common\entity\Article();
        $result = $service->updateArticle($entity, $request->post());

        if (!$result) {
            throw new AdminException('保存失败');
        }

        return json(['code' => 0, 'toUrl' => url('/admin/article')]);

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
                'query' => isset($map) ? $map : ''
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
}