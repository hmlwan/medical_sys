
### 定时任务

#### 会员升级（每一个小时执行一次）

0 */1 * * *  /usr/local/php/bin/php /www/wwwroot/test.coincms.cn/think user-upgrade

#### 魔盒收益 （凌晨0点1分执行）

1 0 * * * /usr/local/php/bin/php /www/wwwroot/test.coincms.cn/think product-income

#### 全网分红 （凌晨2点执行）

0 2 * * * /usr/local/php/bin/php /www/wwwroot/test.coincms.cn/think share