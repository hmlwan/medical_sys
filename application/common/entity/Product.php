<?php
namespace app\common\entity;

use think\Db;
use think\Model;

class Product extends Model
{
    /**
     * @var string 对应的数据表名
     */
    protected $table = 'product';

    protected $autoWriteTimestamp = true;

    public function getProductName()
    {
        return $this->product_name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function getInfoById($id)
    {
        return self::where(['id' => $id])->find();
    }

    //计算开采率
    public function getRate()
    {
        return bcdiv((bcadd($this->rate_min, $this->rate_max, 5)), 2, 5);
    }
    public function getOneProduct($where){
        return self::where($where)->find();
    }
    public function getProductConf($where,$order='sort asc'){
        $list = self::where($where)->order($order)->select();
        foreach ($list as &$value){
            $value['show_logo_img'] = rawurldecode(urlencode(urldecode($value['logo_url'])));
        }
        return $list;
    }


    /**
     * @param $where
     * @param string $order
     * @return array|\PDOStatement|string|\think\Collection
     *  租用记录
     */
    public function getProductRecord($where,$order='add_time desc'){
        $list =  Db::table('product_record')->where($where)->order($order)->select();
        foreach ($list as $k=>&$value){
            $map['id'] = $value['product_id'];
            $product_info = self::getOneProduct($map);
            if(!$product_info){
                unset($list[$k]);
            }else{
                $value['product_name'] = $product_info['product_name'];
                $value['logo_url'] = $product_info['logo_url'];
                $value['energy_num'] = $product_info['energy_num'];
                $value['bg_color'] = $product_info['bg_color'];
                $value['candy_num'] = $product_info['candy_num'];
                $value['hold_num'] = $product_info['hold_num'];
                $value['return_candy_num'] = $product_info['return_candy_num'];
            }
        }
        return $list;
    }
    public function getOneProductRecord($id){
        return Db::table('product_record')->where('id',$id)->find();
    }
    public function addProductRecord($data){
        $data['add_time'] = time();

        $r =  Db::table('product_record')->insert($data);
        return $r;
    }
    public function saveProductRecord($where,$data){
        $data['receive_time'] = time();
        $r =  Db::table('product_record')->where($where)->update($data);
        return $r;
    }
}