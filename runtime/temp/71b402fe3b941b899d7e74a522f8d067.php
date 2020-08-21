<?php /*a:4:{s:63:"C:\wwwroot\www.dayuli.cn\application\index\view\deal\index.html";i:1594340148;s:64:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\head.html";i:1591527760;s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\footer.html";i:1591527760;s:63:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>求购列表</title>
    
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
<div class="wym-page">
    <input type="hidden" name="mui-tab-item" value="3">
    <div class="wym_header">
        <div class="wym_header_top flexbox wrap space-between relative">
            <p><a href="javascript:location.reload();"><i class="refresh_icon"></i></a></p>
            <p class="flexbox wrap spacearound">
                <span class="active">我要卖</span>
               <span id="buy">我要买</span>
            </p>
            <p class="flexbox wrap spacearound">
                <?php if($is_finish_trade == 1): ?>
                <!--<i class="red_icon"></i>-->
                <?php endif; ?>
                <a href="<?php echo url('order/index'); ?>" class="flexbox column">
                    <i class="dd_icon"></i>
                    <span>订单</span>
                </a>
                <a href="/index/index/articleinfo?articleId=6" class="flexbox column">
                    <i class="sz_icon"></i>
                    <span>说明</span>
                </a>
            </p>
        </div>
        <div class="wym_header_mid flexbox wrap spacebetween">
            <!--<span style="display: none"></span>-->
            <span class="span_line">
                 <span class="scroll">
                    <!--<em>交易完成总数量:<?php echo htmlentities($finish_order_sum_num); ?></em>-->
                    <em>等待匹配:<?php echo htmlentities($default_order_sum_num); ?></em>
                </span>
            </span>
            <span class="span_line"><input type="text" class="input_line" value="<?php echo htmlentities(app('request')->get('mobile')); ?>"  name="mobile" placeholder="搜索买家的手机号"> <i class="ss_icon" id="search"></i></span>
        </div>
    </div>
    <?php if($web_switch_market  != 1): ?>
        <div class="show_on_content">
            <p><img src="/static/img/newimg/sd2.png" alt=""></p>
            <p class="show_text blod" style="color: #000;font-size: .3rem">暂停交易，等待10号新版本更新。</p>
        </div>
    <?php elseif($is_stop_deal  == 1): ?>
        <div class="show_on_content">
            <p><img src="/static/img/newimg/sd2.png" alt=""></p>
            <p class="show_text blod" style="color: #000;font-size: .3rem">休市中</p>
            <p class="show_text" style="font-size: .23rem">交易时间:<?php echo htmlentities($web_start_time); ?>-<?php echo htmlentities($web_end_time); ?></p>
        </div>
    <?php else: if(count($list) > 0): ?>
        <div class="wym_content flow_load">
            <?php foreach($list as $item): endforeach; ?>
        </div>
        <?php else: ?>
            <div class="show_on_content">
                <p><img src="/static/img/newimg/no_data_icon.png" alt=""></p>
                <p class="show_text">暂无内容</p>
            </div>
        <?php endif; endif; ?>
</div>
<div class="order_tips_pop cs_pop" style="display: none">
    <input type="hidden" name="order_id" value="">
    <div class="order_tips_pop_view">
        <p class="title">卖出提示</p>
        <!--<p>是否出售 <em class="number">0</em>云链换取<em class="china_price">0.00</em>元</p>-->
              <p>是否出售 <em class="number">0</em>云链(出售手续费为18%)</p>
        <p class="btn text_center">
            <span class="confirm float_l">确定</span>
            <span class="cancel float_r">取消</span>
        </p>
    </div>
</div>
<div class="order_tips_pop jf_pop" style="display: none">
    <div class="order_tips_pop_view">
        <p class="title">解封提示</p>
        <p>您有匹配成功的买入订单未及时付款，需缴纳<?php echo htmlentities($trade_fine_num); ?>云链作为罚金才能开放交易</p>
        <p class="btn">
            <span class="confirm float_l">确定</span>
            <span class="cancel float_r">取消</span>
        </p>
    </div>
</div>
<div class="order_tips_pop ye_pop" style="display: none">
    <div class="order_tips_pop_view">
        <p class="title">余额不足</p>
        <p>您的账户余额不足<?php echo htmlentities($trade_fine_num); ?>云链不能解封，您可以在问题反馈页面联系客服解封，仅只有一次机会。</p>
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
    lazy_loading('index?mobile=<?php echo htmlentities(app('request')->get('mobile')); ?>');
    document.getElementById('buy').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('buy_list'); ?>",
            id: 'buy',

        })
    });

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
    //出售
    $(".cs_pop .confirm").click(function () {
        var order_id = $("input[name='order_id']").val();
        mui.showLoading("处理中..", "div");
        $.ajax({
            url:"sale",
            type:"post",
            data:{
                order_id:order_id
            },
            dataType:'json',
            success:function(data){
                mui.hideLoading();
                $(".zhezhao_h").removeClass('zhezhao');
                $(".cs_pop").hide();
                if(data.code == 0)
                {
                    mui.alert(data.message,function () {
                        window.location.href = '/index/order/index';
                    });
                }else{
                    if(data.toUrl){
                        mui.alert(data.message,function () {
                            window.location.href = data.toUrl;
                        });
                    }else{
                        mui.alert(data.message);
                        return false;
                    }

                }
            }
        })
    });
    //取消
    $(".cs_pop .cancel").click(function () {
        $(".order_tips_pop").hide();
        $(".zhezhao_h").removeClass('zhezhao');
    });
    function ajaxSale(order_id,total_number,china_price) {
        if(!order_id){
            mui.alert("订单不存在");
            return false;
        }
        $("input[name='order_id']").val(order_id);
        $(".cs_pop .number").text(total_number);
        // $(".cs_pop .china_price").text(china_price);
        $(".cs_pop").show();
        $(".zhezhao_h").addClass('zhezhao');


    }
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
