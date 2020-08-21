<?php /*a:4:{s:59:"D:\WWW\candyworld\application\index\view\deal\buy_list.html";i:1594646319;s:57:"D:\WWW\candyworld\application\index\view\layout\head.html";i:1591527760;s:59:"D:\WWW\candyworld\application\index\view\layout\footer.html";i:1591527760;s:56:"D:\WWW\candyworld\application\index\view\layout\nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>我要买</title>
    
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
<body style="background-color: #f1f1f1">
<div class="wym-page">
    <div class="wym_header">
        <input type="hidden" name="mui-tab-item" value="3">
        <div class="wym_header_top flexbox wrap space-between relative">
            <p><a href="javascript:location.reload();"><i class="refresh_icon"></i></a></p>
            <p class="flexbox wrap spacearound">
                <span id="buy_list">我要卖</span>
                <span  class="active">我要买</span>
            </p>
            <p class="flexbox wrap spacearound">
                <?php if($is_finish_trade == 1): ?>
                <!--<i class="red_icon"></i>-->
                <?php endif; ?>
                <a href="<?php echo url('order/index'); ?>" class="flexbox column">
                    <i class="dd_icon"></i>
                    <span>订单</span>
                </a>
                <a href="<?php echo url('/index/member/set'); ?>" class="flexbox column">
                    <i class="sz_icon"></i>
                    <span>设置</span>
                </a>
            </p>
        </div>
        <div class="wym_header_mid flexbox wrap spacebetween">
           <span class="span_line">
                 <span class="scroll">
                    <!--<em>交易完成总数量:<?php echo htmlentities($finish_order_sum_num); ?></em>-->
                    <em>等待匹配:<?php echo htmlentities($default_order_sum_num); ?></em>
                </span>
            </span>
            <span class="span_line"><input type="text" class="input_line" name="mobile" placeholder="是否符合出售条件"> <i class="ss_icon" id="search" ></i></span>
        </div>

    </div>
    <?php if($web_switch_market != 1): ?>
        <div class="show_on_content">
            <p><img src="/static/img/newimg/sd2.png" alt=""></p>
            <p class="show_text blod" style="color: #000;font-size: .3rem">市场关闭</p>
        </div>
    <?php else: ?>
        <form id="buy-form" action="<?php echo url('buy'); ?>" method="post" onsubmit="return false">
        	<div style="padding-top: 1.65rem;"></div>
            <input type="hidden" name="price" value="<?php echo htmlentities($prices['prices']['current']); ?>">
            <div class="wym_xq">
                    <ul class="flexbox column">
                        <li>
                            <span>申请数量</span>
                            <span><input type="text" name="number" class="input_line" placeholder="请输入数量(<?php echo htmlentities($market_min); ?>-<?php echo htmlentities($market_max); ?>)"></span>
                        </li>
                        <li>
                            <span>单价折合</span>
                            <!--<span><?php echo htmlentities($prices['prices']['current']); ?> CNY</span>-->
                               <span>1 CNY</span>
                        </li>
                    </ul>
                    <div class="wym_btn">
                        <button data-form="buy-form" onclick="ajaxPost(this)">确认申请</button>
                    </div>
                </div>
        </form>
        <?php if(count($order)>0): foreach($order as $key=>$item): ?>
                <form id="cancel-form<?php echo htmlentities($key); ?>" action="<?php echo url('order/cancel'); ?>" method="post" onsubmit="return false">
                    <input type="hidden" name="order_id" value="<?php echo htmlentities($item->id); ?>">
                    <input type="hidden" name="type" value="buy">
                    <div class="wym_cx">
                        <ul class="flexbox column">
                            <li>
                                <span class="title">主动买单</span>
                                <span><?php echo htmlentities(date("m-d H:i",!is_numeric($item->create_time)? strtotime($item->create_time) : $item->create_time)); ?></span>
                            </li>
                            <li>
                                <span>数量：<?php echo htmlentities($item->number); ?>糖果</span>
                                <span>总价</span>
                            </li>
                            <li>
                                <span>单价：<?php echo htmlentities($item->number); ?> CNY</span>
                                <span class="red_color"><?php echo htmlentities($item->number); ?> CNY</span>
                            </li>
                            <li class="zfb_li">
                                <span><i class="zfb_icon"></i></span>
                                <span><em data-form="cancel-form<?php echo htmlentities($key); ?>" onclick="ajaxPost(this)">撤单</em></span>
                            </li>
                        </ul>
                    </div>
                </form>
            <?php endforeach; endif; endif; ?>
</div>

<div class="order_tips_pop jf_pop" style="display: none">
    <div class="order_tips_pop_view">
        <p class="title">解封提示</p>
        <p>您有匹配成功的买入订单未及时付款，需缴纳<?php echo htmlentities($trade_fine_num); ?>糖果作为罚金才能开放交易</p>
        <p class="btn">
            <span class="confirm float_l">确定</span>
            <span class="cancel float_r">取消</span>
        </p>
    </div>
</div>
<div class="order_tips_pop ye_pop" style="display: none">
    <div class="order_tips_pop_view">
        <p class="title">余额不足</p>
        <p>您的账户余额不足<?php echo htmlentities($trade_fine_num); ?>糖果不能解封，您可以联系上级或者其他好友给你转账</p>
        <p class="btn text_center">
            <span>确定</span>
        </p>
    </div>
</div>
<div class="order_tips_pop ch_pop" style="display: none">
    <div class="order_tips_pop_view">
        <p class="title">解封成功</p>
        <p>您已经解封成功了，多次匹配恶意不付款将会永久封停不可解封，请知晓</p>
        <p class="btn text_center">
            <span>确定</span>
        </p>
    </div>
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

<script>
    document.getElementById('buy_list').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('index'); ?>",
            id: 'buy_list',
        })
    })



    var is_order_status = "<?php echo htmlentities($order_status); ?>";
    if(is_order_status == -1){
        $(".zhezhao_h").addClass('zhezhao');
        $(".jf_pop").show();
    }
    $(".jf_pop .cancel,.ye_pop .text_center").click(function () {
        window.location.href = '/index/member/index';
    });
    $(".ch_pop .text_center").click(function () {
        $(".order_tips_pop").hide();
        $(".zhezhao_h").removeClass('zhezhao');
        window.location.reload();
    });
    /*解封*/
    $(".jf_pop .confirm").click(function () {

        mui.showLoading("处理中..", "div");
        $.ajax({
            url:"/index/order/unblock",
            type:"post",
            data:{
            },
            dataType:'json',
            success:function(data){
                mui.hideLoading();
                $(".zhezhao_h").removeClass('zhezhao');
                $(".jf_pop").show();
                if(data.code == 0)
                {
                    $(".zhezhao_h").addClass('zhezhao');
                    $(".ch_pop").show();
                }else if(data.code == 2){
                    $(".zhezhao_h").addClass('zhezhao');
                    $(".ye_pop").show();
                }else{
                    mui.alert(data.message);
                    return false;
                }
            }
        })
    });

    $("#search").click(function () {
        var mobile = $("input[name='mobile']").val();
        window.location.href = 'index?mobile='+mobile;
        return false;
    });
    scroll();
    var $wrapper = $(".span_line"),
            $ul = $wrapper.find('.scroll'),
            $li = $wrapper.find('em');
    var srollDistance = $li.height() * $li.length;
    console.log(srollDistance);
    var cloneFirst = $ul.children(':nth-child(1)').clone();
    $ul.append(cloneFirst)
    function scroll() {
        var timer = setInterval(function() {
            var scrollDis = +$ul[0].offsetTop - $li.height();
            console.log(scrollDis);
            $ul.animate({
                top: scrollDis + 'px'
            }, function() {
                if (Math.abs(scrollDis) >= srollDistance) {
                    $ul.css('top', '0px')
                }
            })
        }, 3000)
    }
</script>
</body>
</html>
