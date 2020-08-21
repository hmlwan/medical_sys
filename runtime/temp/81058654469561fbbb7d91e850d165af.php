<?php /*a:3:{s:59:"D:\WWW\candyworld\application\index\view\member\assets.html";i:1594735019;s:57:"D:\WWW\candyworld\application\index\view\layout\head.html";i:1591527760;s:59:"D:\WWW\candyworld\application\index\view\layout\footer.html";i:1591527760;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>公告</title>
    
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
<body class="assets-body" style="background-color: #f4f4f4">
<header>
    <div class="header_bt">
        <div class="header_f"><a href="<?php echo url('index'); ?>" class="header_fwh"></a></div>
        <div class="header_c">资产</div>
        <div class="header_r"><a href="<?php echo url('assets_exp'); ?>" class="">说明</a></div>
    </div>
    <div style="height: 2rem;clear: both;"></div>
</header>
<div class="assets_head">
    <p>总资产</p>
    <p>≈ <?php echo htmlentities($market_money_num); ?> <em>CNY</em></p>
</div>
<div class="assets_page">
    <div class="assets_item">
        <p class="title">
            <span class="float_l"><i></i>糖果</span>
            <span class="float_r"><?php echo htmlentities($user_info->show_magic); ?></span>
        </p>
        <table>
            <thead class="flexbox column">
                <tr>
                    <th><?php echo htmlentities($user_info->show_magic); ?></th>
                    <th>0</th>
                    <th>0</th>
                </tr>
                <tr>
                    <td>可用</td>
                    <td>抵押</td>
                    <td>锁仓</td>
                </tr>
            </thead>
        </table>
    </div>
    <div class="assets_item">
        <p class="title">
            <span class="float_l"><i></i>可售</span>
            <span class="float_r"><?php echo htmlentities($ks); ?></span>
        </p>
        <table>
            <thead class="flexbox column">
            <tr>
                <th><?php echo htmlentities(round($user_statistics->buy_nums,0)); ?></th>
                <th><?php echo htmlentities(round($user_statistics->sale_nums,0)); ?></th>
                <th><?php echo htmlentities(round($user_statistics->buy_machine_num,0)); ?></th>
            </tr>
            <tr>
                <td>流入金</td>
                <td>流出金</td>
                <td>消费金</td>
            </tr>
            </thead>
        </table>
    </div>
    <p class="tips">市场买入糖果可以增加流入金额度</p>
</div>



</body>
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
	
</html>

