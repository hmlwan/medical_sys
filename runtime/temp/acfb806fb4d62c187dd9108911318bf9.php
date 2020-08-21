<?php /*a:4:{s:94:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/welfare/train_station.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>火车票</title>
    
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
<body style="background-color: #f5f7f6">
<header>
    <div class="header_bt">
        <div class="header_f"><a href="javascript:history.back()" class="header_fh"></a></div>
        <div class="header_c">火车票</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<div class="hcp-page">
    <input type="hidden" name="mui-tab-item" value="4">
    <div class="hcp_head">
        <img src="/static/img/newimg/hcpm.jpg" alt="">
    </div>
        <input type="hidden" value="<?php echo htmlentities($date); ?>" name="date">
        <input type="hidden" value="<?php echo htmlentities($week); ?>" name="week">
        <input type="hidden" value="<?php echo htmlentities($date_str); ?>" name="date_str">
        <div class="hcp_con">
            <div class="hcp_con_top">
               火车票用糖果支付享9折（部分）
            </div>
            <div class="hcp_item flexbox wrap spacebetween">
                <div class="text_left">
                    <p class="cfcs">出发城市</p>
                    <p class="ddcs"><input type="text" class="input_line" name="start" value="北京"></p>
                </div>
                <div class="text_center"><i class="zh_icon"></i></div>
                <div class="text_right">
                    <p  class="cfcs">到达城市</p>
                    <p  class="ddcs"><input type="text" class="input_line text_right" name="end" style="width: 100%" value="上海"></p>
                </div>
            </div>
            <div class="hcp_item over_hidden select_pop">
                <span class="float_l">
                    <em class="rq" id="select_date"><?php echo htmlentities($date); ?></em>
                    <em class="xq select_pop"><?php echo htmlentities($week); ?>出发</em>
                </span>
                <span class="float_r" >
                    <i class="xx_icon"></i>
                </span>
            </div>
            <button class="hcpcx_btn"  id="hcpcx">火车票查询</button>
        </div>
    <div class="hcp_bot">
        <img src="/static/img/newimg/sdxz.jpg" alt="">
    </div>
</div>


<script src="/static/js/laydate/laydate.js"></script>
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
    lay('.select_pop').on('click', function(e){
        laydate.render({
            elem: '#select_date', //指定元素
            format:"MM月dd日",
            calendar: true,
            max:30,
            min:1,
            showBottom: false,
            show: true,
            closeStop: '.',
            theme:"#5fa2fd",
            done: function(date,e,i) {
                console.log(e);
                var date_str = e.month+'/'+e.date+'/'+e.year;
                console.log(date_str);
                var week = getWeek(date_str);
                console.log(week);
                $(".hcp_item .xq").html(week+"出发");
                $("input[name='date']").val(date);
                $("input[name='week']").val(week);
                $("input[name='date_str']").val(date_str);
            }
        });
    });
    document.getElementById('hcpcx').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('train_station_detail'); ?>",
            id: 'hcpcx',
        })
    });
    function getWeek(date) {

//        var date = "07/17/2014";    //此处也可以写成 17/07/2014 一样识别    也可以写成 07-17-2014  但需要正则转换
        var day = new Date(Date.parse(date));   //需要正则转换的则 此处为 ： var day = new Date(Date.parse(date.replace(/-/g, '/')));
        var today = new Array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
        var week = today[day.getDay()];
        return  week;
    }
    $("#hcpcx").click(function () {
        var date =   $("input[name='date']").val();
        var week =   $("input[name='week']").val();
        var start =   $("input[name='start']").val();
        var end =   $("input[name='end']").val();
        var date_str =   $("input[name='date_str']").val();
        if(!date){
            mui.alert("请选择日期", '');
            return false;
        }
        if(!start){
            mui.alert("请填写出发地", '');
            return false;
        }
        if(!end){
            mui.alert("请填写到达地", '');
            return false;
        }
        window.location.href = 'train_station_detail?date='+date+'&start='+start+'&end='+end+'&date_str='+date_str;
    })

</script>

</html>

