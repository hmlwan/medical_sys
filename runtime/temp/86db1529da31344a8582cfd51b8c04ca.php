<?php /*a:3:{s:95:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/welfare/flash_exchange.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>闪兑</title>
    
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
        <div class="header_c">聚合闪兑</div>
        <div class="header_r"><a href="javascript:mui.alert('暂无兑换记录', '');"  class="header_fr"></a></div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>

<div class="sdui-page">
    <div class="sdui_header">
        <i class="jh_icon"></i>
        <div class="sdui_item flexbox wrap">
            <input type="hidden" name="magic" value="<?php echo htmlentities($magic); ?>">
            <input type="hidden" name="flash_exchange_rate" value="<?php echo htmlentities($flash_exchange_rate); ?>">
            <input type="hidden" name="flash_exchange_bl" value="<?php echo htmlentities($flash_exchange_bl); ?>">
            <input type="hidden" name="flash_exchange_min_num" value="<?php echo htmlentities($flash_exchange_min_num); ?>">
            <input type="hidden" name="actual_magic" value="">
            <div class="sdui_item_l">
                <p>
                    <span class="title">兑出</span>
                    <span><?php echo htmlentities($magic); ?>糖果</span>
                    <span class="all">全部</span>
                </p>
                <p>
                    <input type="text" name="input_magic" class="input_line" placeholder="输入糖果数量">
                </p>
            </div>
            <i class="vertical_line"></i>
            <div class="sdui_item_r flexbox column">
                <i class="t1_icon"></i>
                <span class="">糖果</span>
            </div>
        </div>
        <div class="sdui_item flexbox wrap">
            <div class="sdui_item_l">
                <p>
                    <span class="title">兑入</span>
                </p>
                <p>
                    <input type="text" readonly  name="input_dr_num" class="input_line" placeholder="--">
                </p>
            </div>
            <i class="vertical_line"></i>
            <div class="sdui_item_r flexbox column">
                <i class="tguo_icon"></i>
                <span class="usdt_icon">USDT</span>
            </div>
        </div>
    </div>
    <div class="input_usdt">
        <input type="text" class="input_line" placeholder="请输入USDT地址（错误地址将不可到账）">
    </div>
    <div class="sxf_item">
        <p>
            <span>手续费</span>
            <span><em class="sxf_rate">--</em> 糖果</span>
        </p>
        <p>
            <span>实际到账数量</span>
            <span><em class="mini_num">0.000</em> USDT </span>
        </p>
    </div>
</div>
<div class="sdui_btn" onclick="sdui_btn()">
    兑换
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
	
<script>
    $(".all").click(function () {
        var magic = $("input[name='magic']").val();
        input_data(magic);
    });
    $("input[name='input_magic']").blur(function () {
        var magic = $(this).val();
        input_data(magic);
    });
    function sdui_btn() {
        var input_magic = $("input[name='input_magic']").val()
        var flash_exchange_min_num = $("input[name='flash_exchange_min_num']").val();
        if(input_magic < flash_exchange_min_num){
            mui.alert('最低'+flash_exchange_min_num+'usdt起兑换', '');
        }else{
            mui.alert('兑换额度暂时已用完', '');
        }
    }
    function input_data(magic) {

        magic = Number(magic).toFixed(3);
        var flash_exchange_rate = $("input[name='flash_exchange_rate']").val();
        var flash_exchange_bl = $("input[name='flash_exchange_bl']").val();
        var dr_magic = magic/flash_exchange_bl;
        dr_magic = Number(dr_magic).toFixed(3)
        $("input[name='input_magic']").val(magic);
        $("input[name='input_dr_num']").val(dr_magic);
        var sxf = Number(magic * flash_exchange_rate);
        console.log(sxf);
        sxf = sxf.toFixed(3);
        $('.sdui-page .sxf_rate').text(sxf);
        var actual_magic = magic - sxf;
        actual_magic = actual_magic/flash_exchange_bl;
        actual_magic = Number(actual_magic).toFixed(3);
        $('.sdui-page .mini_num').text(actual_magic);
    }

</script>
</html>

