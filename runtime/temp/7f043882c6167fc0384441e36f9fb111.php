<?php /*a:4:{s:59:"D:\WWW\candyworld\application\index\view\welfare\index.html";i:1594549598;s:57:"D:\WWW\candyworld\application\index\view\layout\head.html";i:1591527760;s:59:"D:\WWW\candyworld\application\index\view\layout\footer.html";i:1591527760;s:56:"D:\WWW\candyworld\application\index\view\layout\nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>应用</title>
    
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
<script type="text/javascript" src="/static/js/fontSize.js"></script>
<link href="/static/css/mui.css" rel="stylesheet"/>
<link rel="stylesheet" href="/static/css/mui.loading.css"/>
<link type="text/css" rel="stylesheet" href="/static/css/reset.css" />
<link type="text/css" rel="stylesheet" href="/static/css/style.css" />
<link type="text/css" rel="stylesheet" href="/static/js/layui/css/layui.css" />
<link type="text/css" rel="stylesheet" href="/static/js/laydate/theme/default/laydate.css" />
<link type="text/css" rel="stylesheet" href="/static/css/swiper.min.css" />

<script src="/static/js/jquery1.10.2.min.js"></script>

</head>
<body class="tgzp-page">
<div class="fl-page">
    <div class="swiper-container fl_header">
        <div class="swiper-wrapper">
            <?php foreach($sl_list as $sl_item): ?>
                <div class="swiper-slide">
                    <a href="<?php echo htmlentities($sl_item->url); ?>" target="_blank"><img src="<?php echo htmlentities((isset($sl_item->img) && ($sl_item->img !== '')?$sl_item->img:'/static/img/newimg/fl_bgk1.jpg')); ?>"/></a>
                </div>
            <?php endforeach; ?>
        </div>
        <!--<div class="swiper-pagination"></div>-->
    </div>
    <input type="hidden" name="mui-tab-item" value="4">
    <div class="fl_content">
        <ul class="flexbox wrap">
                <li>
                    <span class="item" onclick="sub()" is_sign= "0" >
                        <img src="/static/img/newimg/task.png" alt="">
                        <span>每日签到</span>
                        <span class="subtitle">最高得88糖果</span>
                    </span>
                </li>
            <li>
                <a  class="item" href="<?php echo url('rebate/index'); ?>">
                    <img src="/static/img/newimg/gouwu.png" alt="">
                    <span>淘宝返利</span>
                    <span class="subtitle">全网最高返利</span>
                </a>
            </li>
            <li>
                <a  class="item" href="<?php echo url('candy_turntable'); ?>">
                    <img src="/static/img/newimg/zhuanpan.gif" alt="">
                    <span>糖果转盘</span>
                    <span class="subtitle">超级大奖等你拿</span>
                </a>
            </li>
            <li>
                <a  class="item" href="<?php echo url('flash_exchange'); ?>">
                    <img src="/static/img/newimg/duihuan.png" alt="">
                    <span>闪兑</span>
                    <span class="subtitle">极速兑换USDT</span>
                </a>
            </li>
            <li>
                <a  class="item" href="<?php echo url('borrow'); ?>">
                    <img src="/static/img/newimg/daikuan.png" alt="">
                    <span>借的到</span>
                    <span class="subtitle">大额小贷</span>
                </a>
            </li>
            <li>
                <a  class="item" href="<?php echo url('flash_pay_card'); ?>">
                    <img src="/static/img/newimg/shanfuka.png" alt="">
                    <span>闪付卡</span>
                    <span class="subtitle">免息闪付</span>
                </a>
            </li>
            <li>
                <a  class="item" href="<?php echo url('collect'); ?>">
                    <img src="/static/img/newimg/shoukuan.png" alt="">
                    <span>收款</span>
                    <span class="subtitle">实时收款</span>
                </a>
            </li>
            <li>
                <a  class="item" href="<?php echo url('pay'); ?>">
                    <img src="/static/img/newimg/fukuan.png" alt="">
                    <span>付款</span>
                    <span class="subtitle">实时付款</span>
                </a>
            </li>
            <!--<li>-->
            <!--    <a href="<?php echo url('candy_luckdraw'); ?>">-->
            <!--        <img src="/static/img/newimg/zysc.png" alt="">-->
            <!--        <span>自营商城</span>-->
            <!--        <span class="subtitle">只卖优质商品</span>-->
            <!--    </a>-->
            <!--</li>-->
            <li>
                <a  class="item" href="https://m.tb.cn/h.Vom4N8a">
                    <img src="/static/img/newimg/elm.png" alt="">
                    <span>饿了么</span>
                    <span class="subtitle">先领券后吃喝</span>
                </a>
            </li>
            <li>
                <a  class="item" href="<?php echo url('train_station'); ?>">
                    <img src="/static/img/newimg/hcp.png" alt="">
                    <span>火车票</span>
                    <span class="subtitle">快捷购买</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="receive_reward" style="display: none;">
    <h2>恭喜你</h2>
    <i class="close_icon"></i>
    <p class="candy">获得<em>0.2</em>糖果</p>
    <p><a href="<?php echo htmlentities((isset($adv_info['ad_url']) && ($adv_info['ad_url'] !== '')?$adv_info['ad_url']:'')); ?>" target="_blank"><img src="<?php echo htmlentities((isset($adv_info['ad_logo']) && ($adv_info['ad_logo'] !== '')?$adv_info['ad_logo']:'/static/img/newimg/t1.png')); ?>" alt=""></a></p>
    <p>已发放至 “糖果余额”</p>
    <p><i class="go_back"></i></p>
</div>
<div class="zhezhao_h"></div>

<script src="/static/js/clipboard.min.js"></script>
<script src="/static/js/layui/layui.js"></script>


<script src="/static/js/mui.min.js"></script>
<script src="/static/js/mui.loading.js"></script>
<script src="/static/js/main.js"></script>
<script src="/static/js/swiper-4.2.0.min.js"></script>


<script type="text/javascript">
	mui.init()
//	mui.previewImage();
	mui('body').on('tap','a',function(){
		window.top.location.href=this.href;
	});
//	mui('.mui-scroll-wrapper').scroll({
//		deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
//	});
	mui('body').on('tap','.my-tips',function(){
		mui.alert($(this).attr('data-msg'))
	})
	$(function () {
		$(".mui-item").each(function (i) {
            var i = i+1;
            var cur_active_class = "footer_active_f"+i;
            $(this).removeClass(cur_active_class);
            var footer_active = $("input[name='mui-tab-item']").val();
            if(i == footer_active){
                $(this).addClass(cur_active_class);
            }
		})
	})
</script>
	
<div style="height: .9rem;"></div>
<div class="footer_main">
    <ul>
        <a href="<?php echo url('shop/index','','',true); ?>">
            <li class="footer_f1 mui-item">
                购物
            </li>
        </a>
        <a href="<?php echo url('product/index','','',true); ?>">
            <li class="footer_f2 mui-item">
                矿场
            </li>
        </a>

        <a href="<?php echo url('deal/index','','',true); ?>">
            <li class="footer_f3 mui-item">
                <!--<?php if($is_finish_trade == 1): ?>-->
                <!--    <i class="red_icon"></i>-->
                <!--<?php endif; ?>-->
            交易
            </li>
        </a>
        <a href="<?php echo url('welfare/index','','',true); ?>">
            <li class="footer_f4 mui-item">
                应用
            </li>
        </a>
        <a href="<?php echo url('member/index','','',true); ?>">
            <li class="footer_f5 mui-item">
                我的
            </li>
        </a>
    </ul>
</div>

</body>
</html>
<script>
    var mySwiper = new Swiper('.fl_header',{
        pagination: {
            el: '.swiper-pagination',
        },
        autoplay:true,
    })
    $(".close_icon,.go_back").click(function () {
        $(".receive_reward").hide();
        $(".zhezhao_h").removeClass('zhezhao');
    });
    function sub() {
        var self = $(this);
        var is_sign = self.attr('is_sign');
        if(is_sign == '1'){
            return;
        }
        self.attr('is_sign','1');
        $.post("<?php echo url('welfare/do_task'); ?>",{
        },function(d){
            var data = d.data,
                code = d.code;
            self.attr('is_sign',"0");
            if(code == 0) {
                var reward_num = data.reward_num;
                $(".receive_reward .candy em").html(reward_num);
                $(".receive_reward").show();
                $(".zhezhao_h").attr('class','zhezhao_h zhezhao');
            }else{

                mui.alert(d.message,'');
                return false;
            }
        });
    }
</script>
