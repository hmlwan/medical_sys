<?php
namespace app\index\controller;

use app\common\entity\User;
use think\Request;

class Index extends Base
{
    public function index()
    {

        //获取公告
        $article = new \app\index\model\Article();
        $articleList = $article->getArticleList(1);
        $service = new \app\common\entity\Sl();
        $sl = $service->where('types',1)->select();
        return $this->fetch('index', ["list" => $articleList,"sl"=>$sl]);
    }

    public function task()
    {
        //获取公告
        $service = new \app\common\entity\task();
        $data = $service->select();
        $service = new \app\common\entity\Sl();
        $sl = $service->where('types',1)->select();
        foreach ($data as $k=> $v) {
            
            $data[$k]['imgs'] = str_replace('\\', '/', $v['img']);
        }
        return $this->fetch('task', ["list" => $data,"sl"=>$sl]);
    }
    public function tasks(){

        return $this->fetch('tasks');
    }

    /**
     * 公告详情
     */
    public function articleinfo(Request $requset)
    {
        $articleId = $requset->get("articleId");
        if (!(int)$articleId) {
            $this->error("公告不存在！！");
        }
        $articleinfo = \app\common\entity\Article::where('article_id', $articleId)->find();
        if (!$articleinfo) {
            $this->error("公告不存在！！");
        }
        return $this->fetch("articleinfo", ['articleinfo' => $articleinfo]);
    }

    /**
     * 排行榜
     */
    public function phb()
    {
        //获取开采率排行榜 前20名
        $list = User::field('nick_name,avatar,product_rate')->order('product_rate', 'desc')->limit(20)
            ->select();
        return $this->fetch('phb', [
            'list' => $list
        ]);
    }

}
