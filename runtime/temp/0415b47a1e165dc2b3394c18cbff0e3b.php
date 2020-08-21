<?php /*a:4:{s:75:"D:\WWW\candyworld\application\index\view\welfare\flash_exchange_detail.html";i:1594814979;s:57:"D:\WWW\candyworld\application\index\view\layout\head.html";i:1591527760;s:56:"D:\WWW\candyworld\application\index\view\layout\nav.html";i:1592154544;s:59:"D:\WWW\candyworld\application\index\view\layout\footer.html";i:1591527760;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>兑换详情</title>
    
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
<body style="background-color: #f3f4f6">
<header>
    <div class="header_bt">
        <div class="header_f"><a href="javascript:history.back()" class="header_fh"></a></div>
        <div class="header_c">兑换详情</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<div class="dhxq-page">
    <p class="res title">
        <?php if($info->status==1): ?>
        已处理
        <?php elseif($info->status == 2): ?>
        未处理
        <?php else: ?>
            等待处理
        <?php endif; ?>
    </p>
    <div class="detail_item">
        <ul class="flexbox column">
           <li>
               <p>
                   <span>下单时间</span>
                   <span> <?php echo htmlentities(date("Y-m-d H:i:s",!is_numeric($info->add_time)? strtotime($info->add_time) : $info->add_time)); ?></span>
               </p>
               <p>
                   <span>消耗糖果</span>
                   <span><?php echo htmlentities($info->total_num); ?>糖果</span>
               </p>
               <p>
                   <span>实际到账</span>
                   <span><?php echo htmlentities($info->cny_num); ?> CNY</span>
               </p>
               <p>
                   <span>手续费</span>
                   <span><?php echo htmlentities($info->rate*100); ?>%</span>
               </p>
           </li>
        </ul>
        <ul class="flexbox column">
            <li>
                <p>
                    <span>收款姓名</span>
                    <span><?php echo htmlentities($info->real_name); ?></span>
                </p>
                <p>
                    <span>银行卡号</span>
                    <span><?php echo htmlentities($info->card); ?></span>
                </p>
            </li>
        </ul>
    </div>
    <p class="res">
        <?php if($info->status==2): ?>
            请查看收款账号到账情况
        <?php elseif($info->status==1): ?>
            请仔细检查收款信息真实性，我们已将糖果返还到您账号上请注意查收。
        <?php else: ?>
            我们将在24小时内处理，请留意下支付宝/银行卡
        <?php endif; ?>
       </p>
</div>

</body>
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

