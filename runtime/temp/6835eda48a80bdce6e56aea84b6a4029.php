<?php /*a:3:{s:96:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/welfare/candy_turntable.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>糖果转盘</title>
    
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
<body class="tgzp_body">
    <a href="<?php echo url('index'); ?>"  class="arrow_l"></a>
    <div class="tgzp-page">
        <div class="tgzp_header">
            <i class="tgzp_l"></i>
            <i class="tgzp_r" id="tgzp_r"></i>
            <p><i class="psq_icon"></i></p>
            <p><i class="zj_icon"></i></p>
            <p>
                <i class="tips_icon">恭喜<?php echo htmlentities($rand_phone); ?>抽中<?php echo htmlentities($phone_rand_reward); ?></i>
            </p>
            <p>
                <span  class="dbbb_icon">我的糖果<i class="tgs_bkg"><?php echo htmlentities($candy_num); ?></i></span>
            </p>
        </div>
        <div class="tgzp_content">
            <input type="hidden" name="is_luck" value="0">
            <input type="hidden" name="do_times" value="<?php echo htmlentities($do_times); ?>">
            <p><span  class="left_cj">今日可剩余抽奖次数：<em><?php echo htmlentities($left_nums); ?></em></span></p>
            <ul class="flexbox wrap spacebetween">
                <?php foreach($list as $item): if($item['type'] == '1'): ?>
                        <li class="no_style">

                        </li>
                    <?php elseif($item['type'] == '2'): ?>
                        <li class="an_btn flexbox column" is_start="0" id="begin">
                            <span style="margin-top: 0.6rem;"><?php echo htmlentities($turntable_luckdraw_consume_candy); ?>糖果</span>
                            <span>抽大奖</span>
                        </li>
                    <?php else: ?>
                        <li>
                            <input type="hidden" name="id" value="<?php echo htmlentities($item['id']); ?>">
                            <img src="<?php echo htmlentities((isset($item['img']) && ($item['img'] !== '')?$item['img']:'/static/img/newimg/t1.png')); ?>" alt="">
                            <p><?php echo htmlentities($item['des']); ?></p>
                        </li>
                    <?php endif; endforeach; ?>
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t1.png" alt="">-->
                    <!--<p>6糖果</p>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t2.png" alt="">-->
                    <!--<p>3糖果</p>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>2糖果</p>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>8糖果</p>-->
                <!--</li>-->

                <!--<li>-->
                    <!--<img src="/static/img/newimg/t2.png" alt="">-->
                    <!--<p>3糖果</p>-->
                <!--</li>-->
                <!--<li class="no_style">-->

                <!--</li>-->
                <!--<li class="no_style">-->

                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>2糖果</p>-->
                <!--</li>-->

                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>2糖果</p>-->
                <!--</li>-->
                <!--<li class="an_btn">-->

                <!--</li>-->
                <!--<li class="no_style ">-->

                <!--</li>-->
                <!--<li class="no_style">-->

                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>8糖果</p>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>8糖果</p>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>8糖果</p>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>8糖果</p>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="/static/img/newimg/t3.png" alt="">-->
                    <!--<p>8糖果</p>-->
                <!--</li>-->
            </ul>
            <p class="wdjp"><a href="">刷新运气</a></p>
        </div>
        <input type="hidden" name="is_over_energy" value="<?php echo htmlentities($is_over_energy); ?>" >
        <input type="hidden" name="candy_num" value="<?php echo htmlentities($candy_num); ?>" >
        <input type="hidden" name="turntable_luckdraw_switch" value="<?php echo htmlentities($turntable_luckdraw_switch); ?>" >
        <div class="receive_reward" style="display: none">
            <h2>恭喜你</h2>
            <i class="close_icon"></i>
            <p class="candy">获得<em>0.2</em>糖果</p>
            <p><img src="/static/img/newimg/t1.png" alt=""></p>
            <p>已发放至 “糖果余额”</p>
            <p><i class="go_reward"></i></p>
        </div>
    </div>
    <div class="zhezhao_h"></div>
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
	
</html>
<script>
    $(".close_icon,.go_reward").click(function () {
        $(".receive_reward").hide();
        $(".zhezhao_h").removeClass('zhezhao');
        window.location.reload();
    });

    $(function () {
        // 3. 监听按钮的点击
        var showIndex = 0;
        var currentGunIndex = 0;
        var config_is_luck = 0;
        $('#begin').click(function(){
            var self = $(this),
                    is_start = self.attr('is_start'),
                    lcukdraw_left_num =  $(".tgzp_content .left_cj em").html(),
                    is_over_energy =  $("input[name='is_over_energy']").val(),
                    turntable_luckdraw_switch =  $("input[name='turntable_luckdraw_switch']").val(),
                    candy_num =  $("input[name='candy_num']").val(),
                    do_times =  parseInt($("input[name='do_times']").val());

            var cur_timestamp = parseInt(Math.round(new Date().getTime()/100));
            if(is_start == 1){
                return;
            }
            self.attr('is_start',1);
//            console.log(cur_timestamp);
            if(do_times>0){
                if((cur_timestamp-do_times)<10){
                    self.attr('is_start',0);
                    mui.alert('请勿频繁操作','');
                    return false;
                }
            }else{
                $("input[name='do_times']").val(cur_timestamp)
            }
            if(turntable_luckdraw_switch == 0){
                self.attr('is_start',0);
                mui.alert('抽奖已关闭','');
                return false;
            }
            if(candy_num  <= 0){
                self.attr('is_start',0);
                mui.alert('糖果不足','');
                return false;
            }
            if(lcukdraw_left_num<=0){
                self.attr('is_start',0);
                mui.alert('今日抽奖用完啦','');
                return false;
            }
            if(is_over_energy == 0){
                self.attr('is_start',0);
                mui.alert('算力不足','');
                return false;
            }
            // 3.1 清除定时器
            // 3.2 控制滚动的圈数
            var count = Math.floor(Math.random() * 12)+12;
            var timer = null;
            var sub_data = null;

            var currentId = '';
            var turntable_num = '0.0';
            var cndy_img = '/static/img/newimg/t1.png';
            var cur_candy_num = '0';
            var left_num = '0';
            var turntable_id = 0;
            var is_stop = 0;
//            var count = 10;
            $("input[name='is_luck']").val(0);

            // 3.3 指定滚动的路径
            var showIndexs = [0, 1, 2, 3, 7,12, 16,15,14,13,8,4];
            // 3.4 根据路径进行滚动
            timer = setInterval(function() {
               if(count < 0){
//                    var is_luck = $("input[name='is_luck']").val();
                    console.log("config_is_luck:"+config_is_luck);
                    $.post("<?php echo url('do_luckdraw'); ?>",{is_luck:config_is_luck},function(d){
                        console.log(d);
                        var data = d.data,
                                code = d.code;
                        console.log( 'count1:'+count);
                        config_is_luck = 1;
                            if(code == 0){
                                turntable_id = data.turntable_id;
                                turntable_num = data.turntable_num;
                                cndy_img = data.cndy_img;
                                cndy_img = cndy_img?cndy_img:"/static/img/newimg/t1.png";
                                cur_candy_num = data.cur_candy_num;
                                left_num = data.left_num;

                                for(var i in showIndexs){
                                    var k = showIndexs[i];

                                    var id = $('.tgzp_content ul li').eq(k).find("input[name='id']").val();
                                    console.log(id);
                                    if(turntable_id == id){
                                        currentId = turntable_id;
                                        $("input[name='is_luck']").val(1);
                                        config_is_luck = 1;
                                        return false;
                                    }
                                }
//                                clearInterval(timer);
                                return false;
                            }else if(code == 2){
                                currentId = turntable_id;
                                return false;
                            }
                            else if(code ==1){
                                mui.alert(d.message,'');
                                is_stop = 1;
                                clearInterval(timer);
                                return false;
                            }

                    });
                }
                console.log('currentId:'+currentId);
                console.log('is_stop:'+is_stop);
                if(is_stop == 1){
                    self.attr('is_start',0);
                    config_is_luck = 0;
                    clearInterval(timer);
                    return false;
                }
                // 3.5 判断
                if (config_is_luck== 1 && currentId > 0) {
                    if( $('.tgzp_content ul li').eq(showIndex).find("input[name='id']").val() == currentId){
                        $('.tgzp_content ul li').eq(currentGunIndex).addClass('current').siblings().removeClass('current');

                        $(".receive_reward .candy em").html(turntable_num);
                        $(".receive_reward img").attr('src',cndy_img);
                        $(".tgzp_content .left_cj em").html(left_num?left_num:0);
                        $(".tgzp-page .tgs_bkg").html(cur_candy_num?cur_candy_num:0);
                        $(".receive_reward").show();
                        $(".zhezhao_h").attr('class','zhezhao_h zhezhao');
                        self.attr('is_start',0);
                        config_is_luck = 0;
                        // 清除定时器
                        clearInterval(timer);
                        return;
                    }else{
                        $('.tgzp_content ul li').eq(showIndex).addClass('current').siblings().removeClass('current');
                    }
                }
                console.log( 'count:'+count);
                // 3.6 条件处理
                count--;
                // 滚动循环 1, 2, 3, 4, 5, 6, 7, 0, 1, 2, ...
                currentGunIndex = (currentGunIndex + 1) % showIndexs.length;
                console.log('currentGunIndex:'+currentGunIndex);

                // 根据滚动的下标 找到 当前盒子的下标
                showIndex = showIndexs[currentGunIndex];
                // 3.7 让当前的盒子被选中
                $('.tgzp_content ul li').eq(showIndex).addClass('current').siblings().removeClass('current');

                // 3.8 控制启动按钮的旋转
//                $('#begin').css({
//                    transform: 'rotate(' + (currentGunIndex - 1) * 45 + 'deg)'
//                })
            }, 300);
        })

    });


    document.getElementById('tgzp_r').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('luckdraw_rule'); ?>",
            id: 'tgzp_r',
        })
    })

</script>
