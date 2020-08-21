<?php /*a:3:{s:59:"D:\WWW\candyworld\application\index\view\member\spread.html";i:1594466586;s:57:"D:\WWW\candyworld\application\index\view\layout\head.html";i:1591527760;s:59:"D:\WWW\candyworld\application\index\view\layout\footer.html";i:1591527760;}*/ ?>
<!doctype html>
<html>
<head>
    <title>招募</title>
    
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
<header>
    <div class="header_bt" style="background-color: seagreen">
        <div class="header_f"><a href="<?php echo url('index/invite/index'); ?>" class="header_fh"></a></div>
        <div class="header_c" style="color: #fff">分享</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<body class="spread-body">


<div class="swiper-container spread-page">
    <div class="swiper-wrapper">
        <?php foreach($bg_img as $item): ?>
        <div class="swiper-slide">
            <img src="<?php echo htmlentities($item->img); ?>" class="bg_qrcode_img" alt="">
            <img src="<?php echo url('qrcode_img'); ?>" class="spread_qrcode_img" alt="">
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
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
	
</body>
<script>
    var mySwiper = new Swiper('.spread-page',{
        pagination: {
            el: '.swiper-pagination',
        },
        autoplay:true,
    })
</script>
</html>