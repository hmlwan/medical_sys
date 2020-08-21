<?php
namespace app\common\entity;

use think\Db;
use think\Model;

class task extends Model
{
    const STATUS_SHOW = 1;
    const STATUS_HIDDEN = 0;

    /**
     * @var string 对应的数据表名
     */
    protected $table = 'task_daily';

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
     
        $entity->url = $data['url'];
        $entity->task_name = $data['task_name'];
        $entity->reward_remark = $data['reward_remark'];
        $entity->reward_rate = $data['reward_rate'];
        $entity->complete_num = $data['complete_num'];

        return $entity->save();
    }

    public function updateS($entity, $data)
    {
        
        if ( isset($data['img'])) {
              $entity->img = $data['img'];
        }

        $entity->url = $data['url'];
        $entity->task_name = $data['task_name'];
        $entity->reward_remark = $data['reward_remark'];
        $entity->reward_rate = $data['reward_rate'];
        $entity->complete_num = $data['complete_num'];
        return $entity->save();
    }
    public function getAllTask($where,$order='id asc'){
        $entity = new self();
        return $entity->where($where)->order($order)->select();

    }
    public function getOneTask($id){
        $entity = new self();
        return $entity->get($id);
    }

    /**
     * @param $where
     * @param string $order
     * @return array|\PDOStatement|string|\think\Collection
     * 获取任务记录
     */
    public function getTaskRecord($where,$order='add_time desc'){
        return Db::table('task_record')->where($where)->order($order)->select();
    }
    public function getOneTaskRecord($where){
        return Db::table('task_record')->where($where)->find();
    }

    public function saveTaskRecord($data){
        return Db::table('task_record')->insert($data);

    }

    /**
     * @param $user_id
     * @param $star_id
     * 获取抽奖数值
     */
    public function getLuckdrawNum($user_id,$task_id){
        $task_record_w['user_id'] =  $user_id;
        $task_record_w['task_id'] =  $task_id;

        $task_record = $this->getTaskRecord($task_record_w,'add_time asc');
        $count = count($task_record);

        $luckdraw_detail = Db::table('task_daily_detail')
            ->where('task_daily_id',$task_id)
            ->order('id asc')
            ->select();

        $luckdraw_count = $luckdraw_detail?count($luckdraw_detail):0;

        $get_num_k = $count + 1;

        if($count / $luckdraw_count >=1){
            $get_num_k = ($count % $luckdraw_count) + 1;
        }
        $cur_num_info = $luckdraw_detail[$get_num_k-1];

        return $cur_num_info;
    }

}