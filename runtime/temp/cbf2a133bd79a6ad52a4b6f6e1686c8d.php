<?php /*a:3:{s:88:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/welfare/collect.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>糖果收款</title>
    
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
        <div class="header_c">糖果收款</div>
        <div class="header_r"><a href="<?php echo url('collect_record'); ?>" class="header_fr"></a></div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<div class="tgsk-page">
    <p class="title">以下地址仅能接受糖果转账</p>
    <p>
        <img src="<?php echo url('qrcode_img'); ?>" alt="">
    </p>
    <p class="phone copy1"><?php echo htmlentities($phone); ?></p>
    <p class="copy_skh">
        <span data-clipboard-text="<?php echo htmlentities($phone); ?>" data-clipboard-target=".copy1" class="copy_btn">复制收款号</span>
    </p>

</div>
<div class="bot_tips">
    <p>1.请勿向上述地址转账除糖果之外的资产,否则任何非糖果以外产将不可找回;</p>
    <p>2.转账是即时到账;</p>
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
	
<script>
    var clipboard = new Clipboard('.copy_btn');
    clipboard.on('success', function(e) {
        mui.alert('复制成功', '');
        e.clearSelection();
    });
</script>
</body>

</html>

