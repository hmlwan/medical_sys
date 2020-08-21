<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;

use app\common\service\Users\Cache;

use think\Model;

class UserArticleRecord extends Model
{
    protected $table = 'user_article_record';
    protected $autoWriteTimestamp = false;


    public function add_reocrd($data){

        if($data['status'] == 1){
            $model = new Cache();
            $users = $model->getAllUsers();

            $add_data = array(
                'article_id'=>$data['article_id'],
                'cate_id'=>$data['category'],
                'is_read'=>0,
                'add_time'=>time(),
            );
            foreach ($users as $u_id){
                $id = $u_id['id'];
                $add_data['user_id'] = $id;
                $add_w[] = ['user_id','=',$id];
                $add_w[] = ['article_id','=',$data['article_id']];
                if(self::is_exist($add_w)){
                    self::where($add_w)->update(array('is_read'=>0,'add_time'=>time()));
                }else{
                    self::insert($add_data);
                }

            }
        }
    }
    public function is_exist($where){
        $r = self::where($where)->find();
        if(!$r){
            return false;
        }
        return true;
    }

}