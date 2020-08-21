<?php /*a:4:{s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/shop/index.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>购物</title>
    
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
<body class="tgzp-page">
<!--http://tbk.tgwapi.com/index.php/m-->
<input type="hidden" name="mui-tab-item" value="1">
<div class="shop-page" style="height: 15rem">
    <iframe src="https://tbk.dayuli.cn/index.php/m" frameBorder="0" width="100%"  height="100%"></iframe>
</div>
<input type="hidden" name="cur_reward_rate" value="<?php echo htmlentities($task_list['cur_reward_rate']); ?>">
<input type="hidden" name="cur_complete_num" value="<?php echo htmlentities($task_list['cur_reward_rate']); ?>">
<div class="receive_reward" style="display: none;">
    <h2>恭喜你</h2>
    <i class="close_icon"></i>
    <p class="candy">获得<em>0.2</em>糖果</p>
    <p><img src="/static/img/newimg/qipao.png" alt=""></p>
    <p>已发放至 “糖果余额”</p>
    <p><i class="go_back"></i></p>
</div>
<div class="sign_page" is_sign="0" style="display: <?php if($task_list['is_receive'] == '1'): ?>inline_block<?php else: ?>none<?php endif; ?>" onclick="sub(<?php echo htmlentities($task_list['is_receive']); ?>)">
    <p><img src="/static/img/newimg/tgg.gif" alt=""></p>
</div>
<div class="zhezhao_h <?php if($task_list['is_receive'] == '1'): ?>zhezhao<?php endif; ?>"></div>
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
<script>
    $(".close_icon,.go_back").click(function () {
        $(".receive_reward").hide();
        $(".zhezhao_h").removeClass('zhezhao');
    });
    function sub(task_id) {
        var self = $(this);
        var cur_reward_rate = $("input[name='cur_reward_rate']").val();
        var cur_complete_num = $("input[name='cur_complete_num']").val();
        var is_sign = self.attr('is_sign');
        if(is_sign == 1){
            return;
        }
        self.attr('is_sign',1);
        $.post("<?php echo url('welfare/do_task'); ?>",{
            task_id:task_id,
            cur_reward_rate:cur_reward_rate,
            cur_complete_num:cur_complete_num,
        },function(d){

            var data = d.data,
                code = d.code;
            if(code == 0) {
                var reward_num = data.reward_num;
                $(".receive_reward .candy em").html(reward_num);
                $(".receive_reward").show();
                $(".zhezhao_h").attr('class','zhezhao_h zhezhao');
                $(".sign_page").hide();
            }else{
                self.attr('is_sign',0);
                mui.alert(d.message,'');
                return false;
            }
        });
    }
</script>

</html>