<?php
namespace app\common\entity;

use think\Model;

class Sl extends Model
{
    const STATUS_SHOW = 1;
    const STATUS_HIDDEN = 0;

    /**
     * @var string 对应的数据表名
     */
    protected $table = 'sl';

    protected $autoWriteTimestamp = false;

    public static function getAllCates($articleId)
    {
        return self::where('id', $articleId)->find();
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
        return self::where('id', $articleId)->delete();
    }

    public function getCreateTime()
    {
        return $this->create_time;
    }



    /*
 $data['img'] = $aa;
            }
           
            $data['addtime'] = date('Y-m-d H:i:s',time());
            $data['url'] = $_POST['url'];
            $data['type'] = $_POST['type'];
*/


    public function addArticle($data)
    {
        $entity = new self();
        if ($data['img']) {
              $entity->img = $data['img'];
        }
     
        $entity->addtime = $data['addtime'];
        $entity->url = $data['url'];
        $entity->types = $data['types'];

        return $entity->save();
    }

    public function updateS($entity, $data)
    {
        
        if ( isset($data['img'])) {
              $entity->img = $data['img'];
        }
     
        $entity->addtime = $data['addtime'];
        $entity->url = $data['url'];
        $entity->types = $data['types'];
        return $entity->save();
    }
}