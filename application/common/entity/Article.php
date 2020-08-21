<?php
namespace app\common\entity;

use think\Model;

class Article extends Model
{
    const STATUS_SHOW = 1;
    const STATUS_HIDDEN = 0;

    /**
     * @var string 对应的数据表名
     */
    protected $table = 'article';

    protected $autoWriteTimestamp = false;

    public static function getAllCate()
    {
        return [
            '1' => '公告',
            '2' => '新手解答'
        ];
    }

    public function isShow()
    {
        return $this->status == self::STATUS_SHOW ? true : false;
    }

    public function getCate()
    {
        $allCate = self::getAllCate();
        return $allCate[$this->category] ?? '';
    }

    public static function deleteByArticleId($articleId)
    {
        return self::where('article_id', $articleId)->delete();
    }

    public function getCreateTime()
    {
        return $this->create_time;
    }

    public function addArticle($data)
    {
        $entity = new self();
        if($data['img']){
            $entity->img = $data['img'];
        }
        $entity->category = $data['category'];
        $entity->title = $data['title'];
        $entity->content = $data['content'];
        $entity->status = $data['status'];
        $entity->create_time = time();
        $entity->sort = $data['sort'] ?? 0;

        return $entity->save();
    }

    public function updateArticle(Article $article, $data)
    {
        if(isset($data['img']) && $data['img']){
            $article->img = $data['img'];
        }

        $article->category = $data['category'];
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->status = $data['status'];

        return $article->save();
    }
}