<?php /*a:4:{s:91:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/invite/invite_star.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>邀请扶持</title>
    
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
<body style="background-color: #ecf2fe" class="tgzp-page">
<header>
    <div class="header_bt">
        <div class="header_f"><a href="<?php echo url('member/index'); ?>" class="header_fh"></a></div>
        <div class="header_c">邀请扶持</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<input type="hidden" name="mui-tab-item" value="5">

<div class="yqfc-page">
    <div class="yqfc_header flexbox wrap spacebetween">
        <div class="yqfc_header_item">
            <p>团队人数</p>
            <p><?php echo htmlentities($invite_certs_num); ?></p>
            <p>团队总人数</p>
        </div>
        <div class="yqfc_header_item">
            <p>团队总贡献</p>
            <p><?php echo htmlentities($energy_num); ?></p>
            <p>团队贡献总数</p>
        </div>
    </div>
    <div class="yqfc_title">
       <span>一 星级奖励 一</span>
    </div>
    <div class="yqfc_content flexbox column">
        <?php if(is_array($star_conf_list) || $star_conf_list instanceof \think\Collection || $star_conf_list instanceof \think\Paginator): if( count($star_conf_list)==0 ) : echo "" ;else: foreach($star_conf_list as $key=>$item): ?>
        <!--<form action="<?php echo url('invite/receive'); ?>"  id="receive<?php echo htmlentities($key); ?>" method="post" onsubmit="return false">-->
            <!--<input type="hidden" name="star_id" value="<?php echo htmlentities($item->id); ?>">-->
        <?php if($item->is_receive != '1'): ?>
            <div class="yqfc_content_item flexbox wrap">
                <div class="yqfc_content_item_l xj-content_l_bgk_1"
                     style="color:<?php echo htmlentities((isset($item->receive_bg_color) && ($item->receive_bg_color !== '')?$item->receive_bg_color:'#009b95')); ?>;background:  radial-gradient(circle at top right, transparent 5px, <?php echo htmlentities((isset($item->detail_bg_color) && ($item->detail_bg_color !== '')?$item->detail_bg_color:'#bee6ee')); ?> 0) top right,
    radial-gradient(circle at bottom right, transparent 5px,<?php echo htmlentities((isset($item->detail_bg_color) && ($item->detail_bg_color !== '')?$item->detail_bg_color:'#bee6ee')); ?> 0) bottom right;background-size: 100% 60%;background-repeat: no-repeat;">
                    <p class="title"><?php echo htmlentities($item->star_name); ?>+<?php echo htmlentities((isset($item->reward_num) && ($item->reward_num !== '')?$item->reward_num:'0')); ?>糖果</p>
                    <p class="info">要求：自身算力不低于<?php echo htmlentities(round($item->energy,0)); ?>T<br/>团队实名<?php echo htmlentities((isset($item->cert_num) && ($item->cert_num !== '')?$item->cert_num:'0')); ?>人+团队贡献值<?php echo htmlentities($item->energy_num); ?>T</p>
                </div>
                <div class="yqfc_content_item_r <?php if($item->is_receive == '1'): ?>already_get<?php endif; ?> xj-content_r_bgk_1" style="background: radial-gradient(circle at top left, transparent 5px, <?php echo htmlentities((isset($item->receive_bg_color) && ($item->receive_bg_color !== '')?$item->receive_bg_color:'#bee6ee')); ?>  0) top left,
                radial-gradient(circle at bottom left, transparent 5px, <?php echo htmlentities((isset($item->receive_bg_color) && ($item->receive_bg_color !== '')?$item->receive_bg_color:'#bee6ee')); ?>  0) bottom left; background-size: 100% 60%;background-repeat: no-repeat;">
                    <?php if($item->is_receive == '1'): ?>
                        <span onclick="mui.alert('已经领取');">已经领取</span>
                    <?php elseif($item->is_receive == '2'): ?>
                        <span data-form="receive<?php echo htmlentities($key); ?>" onclick="ajaxReceive('<?php echo htmlentities($item->id); ?>')" style="color:#FFFFFF">立即领取</span>
                    <?php else: ?>
                        <span onclick="mui.alert('未达成就');">未达要求</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <!--</form>-->
        <?php endforeach; endif; else: echo "" ;endif; ?>

        <!--<div class="yqfc_content_item flexbox wrap">-->
            <!--<div class="yqfc_content_item_l xj-content_l_bgk_1">-->
                <!--<p class="title">一星扶持</p>-->
                <!--<p class="info">团队5人+团队20总算力达标奖励50糖果</p>-->
            <!--</div>-->
            <!--<div class="yqfc_content_item_r already_get xj-content_r_bgk_1">-->
                <!--立即领取-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="yqfc_content_item flexbox wrap">-->
            <!--<div class="yqfc_content_item_l xj-content_l_bgk_2">-->
                <!--<p class="title">二星扶持</p>-->
                <!--<p class="info">团队10人+团队100总算力达标奖励100糖果</p>-->
            <!--</div>-->
            <!--<div class="yqfc_content_item_r  xj-content_r_bgk_2">-->
                <!--已经领取-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="yqfc_content_item flexbox wrap">-->
            <!--<div class="yqfc_content_item_l xj-content_l_bgk_3">-->
                <!--<p class="title">三星扶持</p>-->
                <!--<p class="info">团队30人+团队500总算力达标奖励500糖果</p>-->
            <!--</div>-->
            <!--<div class="yqfc_content_item_r  xj-content_r_bgk_3">-->
                <!--为达成就-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="yqfc_content_item flexbox wrap">-->
            <!--<div class="yqfc_content_item_l xj-content_l_bgk_4">-->
                <!--<p class="title">四星扶持</p>-->
                <!--<p class="info">团队10人+团队100总算力达标奖励100糖果</p>-->
            <!--</div><div class="yqfc_content_item_r  xj-content_r_bgk_4">-->
                <!--已经领取-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="yqfc_content_item flexbox wrap">-->
            <!--<div class="yqfc_content_item_l xj-content_l_bgk_5">-->
                <!--<p class="title">五星扶持</p>-->
                <!--<p class="info">团队10人+团队100总算力达标奖励100糖果</p>-->
            <!--</div>-->
            <!--<div class="yqfc_content_item_r  xj-content_r_bgk_5">-->
                <!--已经领取-->
            <!--</div>-->
        <!--</div>-->
    </div>

</div>
<div class="receive_reward" style="display: none">
    <h2>恭喜你</h2>
    <i class="close_icon"></i>
    <p class="candy">获得<em>0.2</em>糖果</p>
    <p><img src="/static/img/newimg/t1.png" alt=""></p>
    <p>已发放至 “糖果余额”</p>
    <p><i class="go_back"></i></p>
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
    $(".close_icon,.go_back").click(function () {
        $(".receive_reward").hide();
        $(".zhezhao_h").removeClass('zhezhao');
        window.location.reload();
    });
    function ajaxReceive(star_id) {
        if(!star_id){
            mui.alert("奖励不存在");
            return false;
        }
        mui.showLoading("处理中..", "div");
        $.ajax({
            url:"receive",
            type:"post",
            data:{
                star_id:star_id
            },
            dataType:'json',
            success:function(data){
                mui.hideLoading();
                $(".zhezhao_h").removeClass('zhezhao');
                $(".receive_reward").hide();
                if(data.code == 0)
                {
                    var reward_num = data.data;
                    $(".receive_reward .candy em").html(reward_num);
                    $(".receive_reward").show();
                    $(".zhezhao_h").addClass('zhezhao');
                }else{
                    mui.alert(data.message);
                    return false;
                }
            }
        });
    }

</script>
</body>
</html>

