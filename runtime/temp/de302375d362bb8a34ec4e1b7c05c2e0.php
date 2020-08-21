<?php /*a:4:{s:85:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/rebate/index.html";i:1592199744;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>我的订单</title>
    
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
<body style="background-color: #f6f6f6">
<header>
    <div class="header_bt">
        <div class="header_f"><a href="<?php echo url('welfare/index'); ?>" class="header_fh"></a></div>
        <div class="header_c">我的订单</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<div class="tbfl-page">
    <input type="hidden" name="mui-tab-item" value="4">
    <div class="tag_line">
        <ul class="flexbox wrap spacearound">
            <a href="javascript:;" >
                <li  class="active">绑定订单</li>
            </a>
            <a href="<?php echo url('deal'); ?>">
                <li>进行中</li>
            </a>
            <a href="<?php echo url('no_receive'); ?>">
                <li>待领取</li>
            </a>
            <a href="<?php echo url('alreadly_receive'); ?>">
                <li>已领取</li>
            </a>
            <a href="<?php echo url('expired'); ?>">
                <li>已无效</li>
            </a>
        </ul>
    </div>

    <div class="mid"><img src="/static/img/newimg/banner23.png" alt=""></div>
    <form id="rebate-form" action="<?php echo url('do_index'); ?>" method="post" onsubmit="return false">

        <div class="ljbd flexbox column">
            <h2>订单编号绑定</h2>
            <p class="tips">tips:下单后请尽快提交淘宝订单编号绑定收益</p>
            <p>
                <input type="text" name="order_no" placeholder="输入订单编号" class="input_line">
            </p>
            <div class="ljbd_btn">
                <button data-form="rebate-form" onclick="ajaxPost(this)">立即绑定</button>
            </div>
        </div>
    </form>

    <div class="zhjc">
        <p class="title"><em></em>订单编号寻找教程<em></em></p>
        <p class="img"><img src="/static/img/newimg/zh9.png" alt=""></p>
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