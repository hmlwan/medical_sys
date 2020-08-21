<?php /*a:4:{s:85:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/invite/index.html";i:1591527760;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>邀请返佣</title>
    
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
<body style="background-color: #f5fbfb">
<header>
    <div class="header_bt" >
        <div class="header_f"><a href="<?php echo url('member/index'); ?>" class="header_fh"></a></div>
        <div class="header_c blod">邀请返佣</div>
        <div class="header_r" ><a href="<?php echo url('index/Invite/rule'); ?>">规则</a></div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<input type="hidden" name="mui-tab-item" value="5">

<div class="yqfy-page">
    <div class="yqfy_header" onclick="window.open('/index/invite/invite_record.html','_self')">
        <div class="flexbox wrap">
            <div class="flexbox column">
                <p>团队总人数</p>
                <p><?php echo htmlentities((isset($invite_levels_num) && ($invite_levels_num !== '')?$invite_levels_num:'0')); ?></p>
            </div>
            <div class="flexbox column">
              
                    <p>今日总佣金</p>
                    <p><?php echo htmlentities($td_commission_num); ?><i></i></p>
               
            </div>
        </div>
        <p class="tips">直邀或间邀好友完成实名、租云空间都将获得佣金</p>
    </div>
    <div class="yqfy_mid">
        <img src="/static/img/newimg/kk.png" alt="">
    </div>
    <div class="yqfy_con">
        <ul class="flexbox wrap">
            <li>
                <span>邀请链接</span>
                <span  id="copy1"><?php echo htmlentities($invite_url); ?></span>
                <span class="copy_btn" data-clipboard-text="<?php echo htmlentities($invite_url); ?>" data-clipboard-target="#copy1">复制</span>
            </li>
            <li>
                <span>邀请码</span>
                <span class="copy1"><?php echo htmlentities($invite_code); ?></span>
                <span data-clipboard-target=".copy1" data-clipboard-text="<?php echo htmlentities($invite_code); ?>" class="copy_btn">复制</span>
            </li>
        </ul>
    </div>
    <a href="/index/member/spread"><div class="yqfy_title">
        生成专属海报 邀请好友
    </div></a>
    <div class="yqfy_bottom">
        <img src="/static/img/newimg/yqsm.png" alt="">
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


<script>
    var clipboard = new Clipboard('.copy_btn');
    clipboard.on('success', function(e) {
        mui.alert('复制成功', '');
        e.clearSelection();
    });

</script>
</body>
</html>

