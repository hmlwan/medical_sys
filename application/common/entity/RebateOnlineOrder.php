<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2020/4/26
 * Time: 17:38
 */

namespace app\common\entity;


use think\Model;

class RebateOnlineOrder extends Model
{
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 数据库连接DSN配置
        'dsn'         => '',
        // 服务器地址
        'hostname'    => 'rm-hp350qjqzuf6zod07zo.mysql.huhehaote.rds.aliyuncs.com',
          'database'        => 'tbk',
    // 用户名
    'username'        => 'tbk',
    // 密码
    'password'        => '6rdWwUZtuGcYcnNr',
//         'database'        => 'dynapp',
//         // 用户名
//         'username'        => 'root',
//         // 密码
//         'password'        => 'root',
        // 数据库连接端口
        'hostport'    => '3306',
        // 数据库连接参数
        'params'      => [],
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => '',
    ];
    protected $table = 'htian_rebate_log';
    protected $autoWriteTimestamp = false;
    const ORDER_STATUS_PAY = "订单付款";
    const ORDER_STATUS_SETTLEMENT = "订单结算";
    const ORDER_STATUS_EXPIRED = "订单失效";

    public static function getAll($where,$order='create_time desc'){

        return self::field('*')->where($where)->order($order)->select();

    }
    public static function getOneByno($where){
        return self::where($where)->find();
    }
}