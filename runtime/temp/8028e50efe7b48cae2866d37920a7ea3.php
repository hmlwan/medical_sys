<?php /*a:3:{s:87:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/welfare/borrow.html";i:1592203330;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>借的到</title>
    
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
        <div class="header_c">借的到</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<div class="jdd-page">
    <input type="hidden" name="energy" value="<?php echo htmlentities($energy); ?>">
    <div class="jdd_item">
        <p>最高可借</p>
        <p class="price"><em>￥</em>50000</p>
        <p>超长账期，还款灵活</p>
    </div>
    <div class="jdd_ljjq_btn">
        立即借钱
    </div>
</div>

<div class="jdd_bot">
    <p><a href="" class="jkjl">借款记录</a></p>
    <p><a href="">借款服务由其它借贷公司为入口提供</a></p>
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
    $(".jdd_ljjq_btn").click(function () {
        var energy = $("input[name='energy']").val();
        if(energy>100){
            mui.alert('多使用将有几率开通');
        }else{
            mui.alert('算力太少不足以抵押');
        }
    });


</script>
</body>
</html>

