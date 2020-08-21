<?php /*a:4:{s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/welfare/pay.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>划转糖果</title>
    
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
        <div class="header_c">划转糖果</div>
        <div class="header_r"><a href="<?php echo url('collect_record'); ?>" class="header_fr"></a></div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<input type="hidden" name="mui-tab-item" value="4">
<div class="hztg-page">
    <form id="pay-form" action="<?php echo url('welfare/do_pay'); ?>" method="post" onsubmit="return false">
        <input type="hidden" name="pay_rate" value="<?php echo htmlentities($pay_rate); ?>">
        <input type="hidden" name="pay_min_num" value="<?php echo htmlentities($pay_min_num); ?>">
        <ul class="flexbox column">
            <li>
                <input type="text" class="input_line" name="pay_mobile" style="width: 75%;" placeholder="对方手机号">
                <i class="show_xm"></i>
            </li>
            <!--<li>-->
                <!--<input type="text" class="input_line" placeholder="确认手机号">-->
            <!--</li>-->
            <li>
                <input autocomplete="off" type="text" name="pay_magic" class="input_line" placeholder="输入糖果数量">
            </li>
            <li class="no_show_line">
                <input autocomplete="off" type="text" name="pay_rate_num" class="input_line" value="手续费" readonly>
            </li>
            <li>
                <input type="password" autocomplete="off" name="trad_password" class="input_line" placeholder="资金密码">
            </li>
        </ul>
        <div class="hztg_bot">
            <button data-form="pay-form" onclick="ajaxPost(this)">确定</button>
            <p class="tips">请确认对方手机号输入是否正确，以防划转不到账</p>
    </div>
    </form>
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

<script>

    $("input[name='pay_magic']").blur(function () {
        var pay_magic = $(this).val();
        var pay_rate = $("input[name='pay_rate']").val();
        var pay_min_num = $("input[name='pay_min_num']").val();
        if(pay_magic<pay_min_num){
            mui.alert("最低划转金额"+pay_min_num);
        }
        var sxf = Number(pay_magic * pay_rate);
        sxf = sxf.toFixed(2);
        $("input[name='pay_rate_num']").val(sxf);
    });
    $("input[name='pay_mobile']").blur(function () {
        var pay_mobile = $(this).val();
        if(!pay_mobile){
            mui.alert("请输入收款手机号");
            return false;
        }
        $.post("<?php echo url('get_user_name'); ?>", {pay_mobile: pay_mobile}, function (d) {
            console.log(d);
            if(d.code == 0){
                $(".show_xm").text(d.data);
            }else{
                mui.alert(d.message);
                $(".show_xm").text('');
                return false;
            }
        });
    })

</script>

</body>
</html>

