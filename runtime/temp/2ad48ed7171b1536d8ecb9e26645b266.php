<?php /*a:3:{s:73:"C:\wwwroot\www.dayuli.cn\application\index\view\invite\invite_record.html";i:1593478750;s:64:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\head.html";i:1591527760;s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\footer.html";i:1591527760;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>邀请明细</title>
    
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
<body  class="wdtd-body" style="background-color: #f4f4f4">
<header>
    <div class="header_bt">
        <div class="header_f"><a href="<?php echo url('index'); ?>" class="header_fh"></a></div>
        <div class="header_c">我的团队</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>

<div class="wdtd-page">
    <div class="wdtd-view wdtd_top" id="commission_record">
        <span>累计收益：<em><?php echo htmlentities($commission_num); ?> </em>云链</span>
        <i class="right"></i>
    </div>
    <div class="wdtd-view wdtd_nav">
        <ul class="flexbox wrap">
            <li class="<?php if(app('request')->get('type') == ''): ?>active<?php endif; ?>" id="type">
                <span><?php echo htmlentities($invite_one); ?></span>
                <span>直推人数</span><span><em></em></span>
            </li>
            <i class="line"></i>
            <li id="type1" class="<?php if(app('request')->get('type') == '1'): ?>active<?php endif; ?>">
                <span><?php echo htmlentities($invite_two); ?></span>
                <span>间推人数</span>
                <span><em></em></span>
            </li>
            <!--<i class="line"></i>-->
            <!--<li id="type2" class="<?php if(app('request')->get('type') == '2'): ?>active<?php endif; ?>">-->
                <!--<span><?php echo htmlentities($invite_no_cert); ?></span>-->
                <!--<span>未实名人数</span>-->
                <!--<span><em></em></span>-->
            <!--</li>-->
            <i class="line"></i>
            <li>
                <span><?php echo htmlentities(round($team_energy,0)); ?></span>
                <span>团队贡献值</span>
                <span><em></em></span>
            </li>
        </ul>
    </div>
    <!--<div class="wdtd-view wdtd_search">-->
    <!--    <p><i class="ss_icon"></i><input type="text" class="input_line" placeholder="搜索/筛选"></p>-->
    <!--</div>-->
    <?php if(count($list)>0): ?>
    <div class="wdtd_con">
        <ul class="flexbox column flow_load">

            <!--<li class="wdtd-view">-->
                <!--<p class="first_line">-->
                    <!--<img src="/static/img/newimg/tx1.png" alt="">-->
                    <!--<span>18764654817</span>-->
                    <!--<i class="rz_icon"></i>-->
                <!--</p>-->
                <!--<p class="second_line">-->
                    <!--<span>算力数量：<em>52</em><i class="flash_icon"></i></span>-->
                    <!--<span>为你返利：<em>52.24</em><i class="tg_icon"></i></span>-->
                <!--</p>-->
            <!--</li>-->
            <!--<li class="wdtd-view">-->
                <!--<p  class="first_line">-->
                    <!--<img src="/static/img/newimg/tx2.png" alt="">-->
                    <!--<span>18764654817</span>-->
                    <!--<i class="wrz_icon"></i>-->
                <!--</p>-->
                <!--<p class="second_line">-->
                    <!--<span>算力数量：<em>52</em><i class="flash_icon"></i></span>-->
                    <!--<span>为你返利：<em>52.24</em><i class="tg_icon"></i></span>-->
                <!--</p>-->
            <!--</li>-->
        </ul>
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
	

<script>
    lazy_loading('invite_record?type=<?php echo htmlentities(app('request')->get('type')); ?>');

    document.getElementById('type').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('invite_record'); ?>",
            id: 'type',
        })
    })
    document.getElementById('type1').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('invite_record?type=1'); ?>",
            id: 'type1',
        })
    })
//    document.getElementById('type2').addEventListener('tap', function () {
//        mui.openWindow({
//            url: "<?php echo url('invite_record?type=2'); ?>",
//            id: 'type2',
//        })
//    })
    document.getElementById('commission_record').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('commission_record'); ?>",
            id: 'commission_record',
        })
    })
</script>
</body>
</html>