<?php /*a:4:{s:76:"C:\wwwroot\www.dayuli.cn\application\index\view\product\expired_machine.html";i:1592191304;s:64:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\head.html";i:1591527760;s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\footer.html";i:1591527760;s:63:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>云空间</title>
    
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
        <div class="header_c">云空间</div>
        <div class="header_r"><a href="<?php echo url('newcomer_intro'); ?>">说明</a></div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<input type="hidden" name="mui-tab-item" value="2">
<div class="dhkj-page">
    <div class="dhkj_tips">
        <p><i class="laba_icon"></i><span>当云空间到期后，可持有数量将会相对应恢复。</span></p>
    </div>
    <div class="dhkj_header flexbox wrap spacebetween">
        <span><a href="<?php echo url('machine_hire'); ?>">租云空间</a></span>
        <span><a href="<?php echo url('running_machine'); ?>">运行中</a></span>
        <span  class="active_l">已到期</span>
    </div>
    <?php if(count($list)>0): ?>
    <div class="dhkj_content">
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$item): ?>
        <div class="dhkj_item flexbox column expire_bgk">
            <i class="gc">
                <img src="<?php echo htmlentities((isset($item['logo_url']) && ($item['logo_url'] !== '')?$item['logo_url']:'/static/img/newimg/gc1.png')); ?>" alt="">
            </i>
            <div class="first_item">
                <span class="title"><?php echo htmlentities($item['product_name']); ?></span>
                <span>算力：<?php echo htmlentities((isset($item['energy_num']) && ($item['energy_num'] !== '')?$item['energy_num']:'0')); ?>T</span>
            </div>
            <div>
                <span>兑换数量：<?php echo htmlentities((isset($item['candy_num']) && ($item['candy_num'] !== '')?$item['candy_num']:'0')); ?>云链</span>
                <span>可持有：<?php echo htmlentities((isset($item['hold_num']) && ($item['hold_num'] !== '')?$item['hold_num']:'0')); ?>台</span>
            </div>
            <div class="last_item flexbox wrap spacebetween">

                <p class="flexbox column">
                	<em>周期产量：<?php echo htmlentities($item['energy_num']*$item['period']); ?>云链</em>
                    <!--<em>周期产量：<?php echo htmlentities((isset($item['return_candy_num']) && ($item['return_candy_num'] !== '')?$item['return_candy_num']:'0')); ?>云链</em>-->
                    <em>运行周期：<?php echo htmlentities((isset($item['period']) && ($item['period'] !== '')?$item['period']:'0')); ?>天</em>
                </p>
                <p>
                    <span class="op_data">已到期</span>
                </p>
            </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <!--<div class="dhkj_item flexbox column  gc2_bgk">-->
            <!--<i class="gc gc2"></i>-->
            <!--<div class="first_item">-->
                <!--<span class="title">二级空间</span>-->
                <!--<span>贡献值:3G</span>-->
            <!--</div>-->
            <!--<div>-->
                <!--<span>兑换数量：100云链</span>-->
                <!--<span>可持有：1家</span>-->
            <!--</div>-->
            <!--<div class="last_item flexbox wrap spacebetween">-->

                <!--<p class="flexbox column">-->
                    <!--<em>周期产量：120云链</em>-->
                    <!--<em>运行周期：30天</em>-->
                <!--</p>-->
                <!--<p>-->
                    <!--<a href="">立即租用</a>-->
                <!--</p>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="dhkj_item flexbox column  gc3_bgk">-->
            <!--<i class="gc gc3"></i>-->
            <!--<div class="first_item">-->
                <!--<span class="title">三级空间</span>-->
                <!--<span>贡献值:3G</span>-->
            <!--</div>-->
            <!--<div>-->
                <!--<span>兑换数量：100云链</span>-->
                <!--<span>可持有：1家</span>-->
            <!--</div>-->
            <!--<div class="last_item flexbox wrap spacebetween">-->

                <!--<p class="flexbox column">-->
                    <!--<em>周期产量：120云链</em>-->
                    <!--<em>运行周期：30天</em>-->
                <!--</p>-->
                <!--<p>-->
                    <!--<a href="">立即租用</a>-->
                <!--</p>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="dhkj_item flexbox column  gc4_bgk">-->
            <!--<i class="gc gc4"></i>-->
            <!--<div class="first_item">-->
                <!--<span class="title">四级空间</span>-->
                <!--<span>贡献值:3G</span>-->
            <!--</div>-->
            <!--<div>-->
                <!--<span>兑换数量：100云链</span>-->
                <!--<span>可持有：1家</span>-->
            <!--</div>-->
            <!--<div class="last_item flexbox wrap spacebetween">-->

                <!--<p class="flexbox column">-->
                    <!--<em>周期产量：120云链</em>-->
                    <!--<em>运行周期：30天</em>-->
                <!--</p>-->
                <!--<p>-->
                    <!--<a href="">立即租用</a>-->
                <!--</p>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="dhkj_item flexbox column  expire_bgk ">-->
            <!--<i class="gc gc4"></i>-->
            <!--<div class="first_item">-->
                <!--<span class="title">四级空间</span>-->
                <!--<span>贡献值:3G</span>-->
            <!--</div>-->
            <!--<div>-->
                <!--<span>兑换数量：100云链</span>-->
                <!--<span>可持有：1家</span>-->
            <!--</div>-->
            <!--<div class="last_item flexbox wrap spacebetween">-->

                <!--<p class="flexbox column">-->
                    <!--<em>周期产量：120云链</em>-->
                    <!--<em>运行周期：30天</em>-->
                <!--</p>-->
                <!--<p>-->
                    <!--<a href="">已到期</a>-->
                <!--</p>-->
            <!--</div>-->
        <!--</div>-->
    </div>
    <?php else: ?>
        <div class="show_on_content">
            <p><img src="/static/img/newimg/no_data_icon.png" alt=""></p>
            <p class="show_text">暂无内容</p>
        </div>
    <?php endif; ?>
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

</body>
</html>
