<?php /*a:4:{s:75:"C:\wwwroot\www.dayuli.cn\application\index\view\product\newcomer_intro.html";i:1593479788;s:64:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\head.html";i:1591527760;s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\footer.html";i:1591527760;s:63:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>挖矿教程</title>
    
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
<body>
<header>
    <div class="header_bt">
        <div class="header_f"><a href="javascript:history.back()" class="header_fh"></a></div>
        <div class="header_c">挖矿教程</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<input type="hidden" name="mui-tab-item" value="2">
<div class="xsjc-page">
    <!--<div class="xsjc_header">-->
    <!--    <ul class="flexbox wrap">-->
    <!--        <li>-->
    <!--            <span>10亿</span>-->
    <!--            <span>云链总量<i class="jc_1"></i></span>-->
    <!--        </li>-->
    <!--        <em></em>-->
    <!--        <li>-->
    <!--            <span>737.57万</span>-->
    <!--            <span>当前已产出<i class="jc_2"></i></span>-->
    <!--        </li>-->
    <!--        <em></em>-->
    <!--        <li>-->
    <!--            <span>737.57万</span>-->
    <!--            <span>当前已销毁<i class="jc_3"></i></span>-->
    <!--        </li>-->
    <!--    </ul>-->
    <!--</div>-->
    <div class="xsjc_content">
    	
    	
    	
    	 <h2> 【1.云链是什么?】</h2>
       <p> 传统的互联网产品,公司获得收益和你没有关系,你只是在贡献,却没有享受收益。云链整体设计区别于当前这些产品,云链可用于参加租云空间获得算力挖矿,你拥有的算力越多,接下来你享受的挖矿收益就会越多。</p>
                   <h2>【2.云链的用处?】</h2>
        <p>云链是基于用户使用产品产生的奖励,只可以用于內部的消费与兑换。</p>
          <h2> 【3.挖矿的结算】</h2>
       <p> 1-300T算力则需要1个100-500的小买单才能领取收益，300T以上可以不用挂单即可领取收益.</p>
       <h2> 【4.挖矿的结算】</h2>
       <p> 挖矿是按秒来计算收益的，你可以随时领取收益。云链目前只能通过APP内挖矿获取,没有其他获取途径。云链总量10亿,为了让后注册的用户不会觉得有很大落差感，前一半矿池里的5亿云链不会随着用户数量增加,用云空间挖出云链速度随之减慢。当总量每挖出一半，难度将上升一倍,收益将会递减.</p>
 <h2>【5.挖矿需要注意的地方】</h2>
<p>收益最多累积24小时，请务必每24小时内领取一次收益。领取收益间隔时间必须1小时以上且累积收益不低于0.1云链。
        <h2>【6.云链APP的盈利模式是什么?】</h2>
        <p>云链APP首创电商+广告双轮驱动商业模式,2019年中国国内电商+广告市场合计4000亿市场,云链APP拥有业内最高的盈利模式。2020年云链APP电商和广告收入都将回
            馈给用户,用于市场开拓。</p>
    </div>
</div>

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
