<?php /*a:4:{s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\product\index.html";i:1592585528;s:64:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\head.html";i:1591527760;s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\footer.html";i:1591527760;s:63:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>矿场</title>
    
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

<script>
    (function (global) {
        function Counter(options) {
            this.init(options);
        }

        Counter.__DEFAULTS__ = {
            // 展示效果的元素
            el: null,
            // 起始数
            fromNumber: 0,
            // 结束数
            toNumber: 0,
            // 用于支持小数
            enableFloat: false,
            // 持续时间
            duration: 300
        };

        Counter.prototype.init = function (options) {
            this.precision = getPrecision(options.toNumber);
            this.setOptions(options);
            if (typeof this.el === 'string') {
                this.el = document.querySelector(this.el);
            }
            console.log(this.toNumber);

            if (!(this.el instanceof Element)) {
                throw TypeError('el must be a element.');
            }
        }

        Counter.prototype.setOptions = function (options) {
            var defaults = Counter.__DEFAULTS__, opts = options && typeof options === 'object' ? options : {}, k;
            for( k in defaults ) {
                this[k] = defaults[k];
            }
            for ( k in this ) {
                if (k in opts && typeof opts[k] !== 'undefined'
                    && opts[k] !== this[k]
                ) {
                    if ( typeof this[k] === 'number' ) {
                        opts[k] = parseFloat(opts[k]) || this[k];
                    }

                    else if ( typeof this[k] === 'boolean' ) {
                        opts[k] = !!opts[k];
                    }

                    else if ( typeof this[k] === 'object' ) {
                        opts[k] = typeof opts[k] === 'object' ? opts[k] : this[k];
                    }

                    else {
                        opts[k] = this[k].construtor(opts[k]);
                    }

                    if ( this[k] == null || typeof(this[k]) === typeof(opts[k]) ) {
                        this[k] = opts[k];
                    }
                }
            }
        }

        Counter.prototype.start = function () {
            const self = this,
                from = parseFloat( self.fromNumber ),
                to = parseFloat( self.toNumber ),
                enableFloat = self.enableFloat,
                precision = self.precision,
                duration = self.duration,
                el = self.el,
                startTime = Date.now();

            const update = function () {
                var nowTime = Date.now(),
                    ratio = Math.min( 1, (nowTime - startTime) / duration ),
                    result = ratio * (to - from) + from;
                console.log(from, self.toNumber);
                if (enableFloat) {
                    result = result.toFixed(precision);
                } else {
                    result = Math.floor(result);
                }

                if ( ratio == 1 || el.innerHTML == to ) {
                    clearInterval(self.__timerId__);
                }

                el.innerHTML = result;
            };
            if (self.__timerId__) {
                clearInterval(self.__timerId__);
            }
            self.__timerId__ = setInterval(update, 1000 / 60);
        }

        // 获取小数点精度
        function getPrecision(num) {
            var n = num + '', i = n.indexOf('.') + 1;
            n = n.slice( i, n.length);
            return i ? n.length : i;
        }
        // 自定义方法
        Math.getPrecision = getPrecision;
        global.Counter = Counter;
    })(this);
</script>

<body  class="zz-body zz_bj<?php echo htmlentities($bj_k); ?>">

<input type="hidden" name="mui-tab-item" value="2">
<input type="hidden" name="decimal_num" value="<?php echo htmlentities((isset($decimal_num) && ($decimal_num !== '')?$decimal_num:0)); ?>">
<input type="hidden" name="left_second" value="<?php echo htmlentities((isset($left_second) && ($left_second !== '')?$left_second:0)); ?>">
<input type="hidden" name="is_receive" value="<?php echo htmlentities((isset($is_receive) && ($is_receive !== '')?$is_receive:0)); ?>">
<input type="hidden" name="reward_num" value="<?php echo htmlentities((isset($reward_num) && ($reward_num !== '')?$reward_num:0)); ?>">
<div class="zz-page tgzp-page " style="height:0%">
    <div class="zz_header zz_inline_style">
        <div class="zz_header_top flexbox wrap spacebetween">
            <span>持有算力:<?php echo htmlentities($energy); ?>T</span>
            <span>待领取</span>
            <span><a href="<?php echo url('receive_record'); ?>">领取记录</a></span>
        </div>
        <p class="blod condy_sum_num"><?php echo htmlentities((isset($condy_sum_num) && ($condy_sum_num !== '')?$condy_sum_num:'0.00000')); ?></p>
    </div>
    <div class="zz_mid flexbox wrap spacebetween">
        <div class="zz_inline_style wdzc_l">
            <p>我的资产</p>
            <p class="blod"><?php echo htmlentities($magic); ?></p>
        </div>
        <div class="wdzc_r">
            <div class="flexbox column">
                <p class="zyjq_item"><i><?php echo htmlentities((isset($p_num) && ($p_num !== '')?$p_num:'0')); ?></i><a href="<?php echo url('machine_hire'); ?>"><img src="/static/img/newimg/zyjq.png" alt=""></a></p>
            </div>
        </div>
    </div>
    <div class="xsjc_flex">
        <a href="<?php echo url('product/newcomer_intro'); ?>">
            <img src="/static/img/newimg/xsjc.png" alt="">
        </a>
    </div>

    <div class="zz_tg">

    </div>
    <div class="zz_content">
        <em></em>
    </div>
    <div class="zz_bottom zz_inline_style" >
        <div class="zz_bottom_item flexbox wrap spacebetween">
            <div class="zz_bottom_item_l">
                <i class="rate_icon" style="left: <?php echo htmlentities($time_rate); ?>%"><?php echo htmlentities($time_rate); ?>%</i>
                <p class="rate"><i class="rate_bgk" style="width: <?php echo htmlentities($time_rate); ?>%"></i></p>
                <p style="display: flex;">当前累积收益时间：<em class="blod">00:00:00</em></p>
            </div>
            <div class="lqsy"></div>
        </div>
    </div>
    <div class="receive_reward" style="display: none">
        <h2>恭喜你</h2>
        <i class="close_icon"></i>
        <p class="candy">获得<em>0.2</em>云链</p>
        <p><a href="<?php echo htmlentities((isset($adv_info['ad_url']) && ($adv_info['ad_url'] !== '')?$adv_info['ad_url']:'')); ?>" target="_blank"><img src="<?php echo htmlentities((isset($adv_info['ad_logo']) && ($adv_info['ad_logo'] !== '')?$adv_info['ad_logo']:'/static/img/newimg/t1.png')); ?>" alt=""></a></p>
        <p>已发放至 “云链余额”</p>
        <p><i class="go_back"></i></p>
    </div>
    <div class="zhezhao_h "></div>

    <div class="order_tips_pop tx_pop" style="display: none">
        <div class="order_tips_pop_view">
            <p class="title">收益降低<em class="rate"></em>%提醒</p>
            <p>当前收益为<em class="new"></em>云链，再挂<em class="left"></em>个小买单将获得<em class="old"></em>云链</p>
            <p class="btn">
                <span class="confirm float_l lqsy" is_reduce='1'>直接领取</span>
                <span class="cancel float_r">前去挂单</span>
            </p>
        </div>
    </div>
</div>
<style>
    .zz_tg div {
        position: absolute;
        height: 1rem;
        width: 1rem;
        display: inline-block;
    }

    .zz_tg .tran_wrap {
        transition: all 1s cubic-bezier(0.49, -0.29, 0.75, 0.41);
        transform: translate3d(0, 0, 0);
    }

    .zz_tg i {
        display: block;
        width: 85%;
        height: 85%;
        transition: all 1s linear;
        background: url('') no-repeat center/100%;
    }
    @keyframes flow {
        from {
            transform: translateY(.1rem);
        }

        to {
            transform: translateY(-.1rem);
        }
    }

    .zz-page .zz_tg div:nth-child(1) {
        top: 0;
        left: 1.5rem;
    }

    .zz-page .zz_tg .wrapper:nth-child(1) {
        animation: flow 3s ease-in-out infinite alternate;
    }

    .zz-page .zz_tg .wrapper:nth-child(2) {
        animation: flow 3s ease-in-out -.5s infinite alternate;
    }

    .zz-page .zz_tg .wrapper:nth-child(3) {
        animation: flow 3s ease-in-out -1s infinite alternate;
    }

    .zz-page .zz_tg .wrapper:nth-child(4) {
        animation: flow 3s ease-in-out -1.5s infinite alternate;
    }

    .zz-page .zz_tg .wrapper:nth-child(5) {
        animation: flow 3s ease-in-out -2s infinite alternate;
    }

    .zz-page .zz_tg .wrapper:nth-child(6) {
        animation: flow 3s ease-in-out -2.5s infinite alternate;
    }
    .zz-page .zz_tg .wrapper:nth-child(7) {
        animation: flow 3s ease-in-out -3s infinite alternate;
    }
    .zz_bottom_item .blod {
        display: flex;
        line-height: 16px;
        height: 16px;
        overflow: hidden;
        margin-top: 3px;
        font-size: .26rem;
    }
</style>


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
    $(".tx_pop .cancel").click(function () {
        window.location.href = '/index/deal/buy_list';
    });

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }
    Array.prototype.shuffle = function () {
        var array = this;
        var m = array.length,
            t, i;
        while (m) {
            i = Math.floor(Math.random() * m--);
            t = array[m];
            array[m] = array[i];
            array[i] = t;
        }
        return array;
    }
    // 初始化云链位置和数量
    var allPosArr = [
        { top: 0, left: '1.5rem' },
        { top: '2.2rem', left: '.5rem' },
        { top: '.6rem', left: '2.5rem' },
        { top: '1.2rem', right: 0 },
        { top: '2.8rem', right: 0 },
        { top: '2rem', left: '3.4rem' },
        { top: '3rem', left: '2.8rem' },
        { top: '.86rem', left: '.5rem' },
        { top: '1.8rem', left: '1.8rem' },
        { top: '3rem', left: '1.6rem' }
    ]
    var domStr = ''
    let pickedPos = allPosArr.shuffle().slice(0, getRandomInt(4,8 ));
    pickedPos.forEach(function (item, index) {
        var divStyleStr = item.left ? ('left:' + item.left) : ('right:' + item.right);
        divStyleStr += ';top:' + item.top + ';'
        var iStyleStr = 'background-image: url(/static/img/newimg/qipao.png);';
        iStyleStr += 'transform:rotate(' + getRandomInt(0, 145) + 'deg);'
        domStr += '<div class="wrapper" style="' + divStyleStr + '"><i style="' + iStyleStr + '"></i></div>'
    })
    $('.zz_tg').append(domStr)
    // 云链飞入左上方
    var endPoint = $('.wdzc_l')[0].getBoundingClientRect()
    var candyPos = []
    var candys = $('.zz_tg div')
    for (var i = 0; i < candys.length; i++) {
        candyPos.push(candys[i].getBoundingClientRect())
    }
    $('.lqsy').on('click', function () {
//        $(".zhezhao_h").removeClass('zhezhao');
//        $(".tx_pop").hide();
//        var is_reduce = $(this).attr('is_reduce');
        $.post("<?php echo url('do_receive'); ?>",{},function(res){
            var data = res.data;
            if(res.code == 0){
                var is_alert = 0;
                for (let j = 0; j < candys.length; j++) {
                    let $candy = $(candys[j])
                    let yDistance = endPoint.top - candyPos[j].top
                    let xDistance = endPoint.left - candyPos[j].left
                    $candy.removeClass('wrapper').addClass('tran_wrap');
                    //  console.log('after', $candy)

                    setTimeout(function () {
                        $candy.css({ transform: 'translate3d(' + xDistance + 'px, 0, 0)' })
                        $candy.find('i').css({ transform: 'translateY(' + yDistance + 'px)' })

                        setTimeout(() => {
                            candys.hide();

                    }, 1000);
                    }, 10);
                    if(j == (candys.length) -1){
                        is_alert = 1;
                    }
                }
                if(is_alert == 1){
                    setTimeout(function () {

                        $(".receive_reward .candy em").html(data.condy_reward_num);
                        $(".receive_reward").show();
                        $(".zhezhao_h").attr('class','zhezhao_h zhezhao');
                    }, 1200);
                }
            }
//            else if(res.code == 2){
//                $(".tx_pop .rate").text(data.market_income_rate);
//                $(".tx_pop .new").text(data.condy_reward_num);
//                $(".tx_pop .old").text(data.old_condy_reward_num);
//                $(".tx_pop .left").text(data.left_nums);
//                $(".zhezhao_h").addClass('zhezhao');
//                $(".tx_pop").show();
//            }
            else {
                mui.alert(res.message, function () {
                    if(res.toUrl){
                        window.location.href=res.toUrl;
                    }
                });
                return false;
            }
        });
    });
    $(".close_icon,.go_back").click(function () {
        $(".receive_reward").hide();
        $(".zhezhao_h").removeClass('zhezhao');
        window.location.reload();
    });
    $(function () {
        var count = $("input[name='left_second']").val();
        var decimal_num = $("input[name='decimal_num']").val();
        var is_receive = $("input[name='is_receive']").val();
        var reward_num = $("input[name='reward_num']").val();
        console.log("count:"+count);
        console.log("decimal_num:"+decimal_num);

        console.log("is_receive:"+is_receive);
        console.log("reward_num:"+reward_num);
        var count_str = count;

        // 获取一字符在另一字符串中出现的次数
        // 参数
        //  source    搜索源字符串
        //  search    被搜索的字符串

        var timer = setInterval(function() {
            const num = document.querySelector('.num'),
                begin = document.querySelector('.begin'),
                showBox = document.querySelector('.condy_sum_num');
            var condy_sum_num = parseFloat($(".condy_sum_num").text());
            if (count <= 0) {
                // 清除定时器
                clearInterval(timer);
                return;
            }
            // 3.6 条件处理
            var increase_num = parseFloat(reward_num*2).toFixed(5);
            console.log("increase_num:"+increase_num);
            condy_sum_num = parseFloat((parseInt(condy_sum_num*10000000)+parseInt(increase_num*10000000))/10000000).toFixed(5);
            console.log("condy_sum_num:"+condy_sum_num);

//            $(".mem_jb_sum_num").text(mem_jb_sum_num).fadeIn(1000);
            console.log("innerHTML:"+showBox.innerHTML);
//            const run = function() {
            new Counter({
                el: showBox,
                fromNumber: showBox.innerHTML,
                toNumber: condy_sum_num || 0,
                enableFloat: true,
                duration: 1500
            }).start();
//            };
//            if(count == count_str){
//                $(".mem_jb_sum_num").fadeOut(1000,function () {
//                    $(".mem_jb_sum_num").show();
//                });
//            }
//            $(".mem_jb_sum_num").fadeOut(1000);
            count = count-2;
            console.log("count:"+count);
        }, 2000);
    });
    //时间滚动
    function addZero(num) {
        return num < 10 ? ('0' + +num) : +num;
    }

    function scrollTime($time, time) {
        var during_time = $("input[name='left_second']").val();
        var baseArr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        var baseLiStr = ''
        baseArr.forEach(function(ele) {
            baseLiStr += '<li>' + ele + '</li>'
        })
        var lineHeight = $time.height();
        var domStr = '';
        for (var i = 0; i < time.length; i++) {
            if (time[i] == ':') {
                domStr += '<ul>:</ul>';
                continue
            }
            var scrollHeight = baseArr.indexOf(time[i]) * lineHeight;
            domStr += '<ul style="transition: transform .3s ease-in;transform: translate3d(0, -'+ scrollHeight +'px, 0)">' + baseLiStr + '</ul>'
        }
        $time.html(domStr)

        let timeArr = time.split(':')
        var hour = +timeArr[0]
        var minute = +timeArr [1]
        var second = +timeArr[2]
        var timer = setInterval(function() {
            if(during_time<=0){
                clearInterval(timer);
                return;
            }
            if (second + 1 >= 60) {
                second = -1
                if (minute + 1 >= 60) {
                    minute = -1;
                    hour++
                }
                minute++
            }
            second++
            var timeStr = addZero(hour) + ':' + addZero(minute) + ':' + addZero(second);
            if (timeStr.length > time.length) {
                $time.prepend('<ul style="transition: transform .3s ease-in;transform: translate3d(0, 0, 0)">' + baseLiStr + '</ul>')
            }
            for(var j = timeStr.length - 1; j >= 0; j--) {
                if (timeStr[j] !== time[j]) {
                    var scrollHeight = baseArr.indexOf(timeStr[j]) * lineHeight;
                    $time.find('ul').eq(j)[0].style.transform = 'translate3d(0, -' + scrollHeight + 'px , 0)'
                }
            }
            time = timeStr;
            during_time--;
        }, 1000)

    }
    var $time = $('.zz_bottom_item .blod');
    // 只需要修改这里就行
//    scrollTime($time, '99:58:58')
    scrollTime($time,<?php echo htmlentities($show_during_second_str); ?>);
</script>

</body>
</html>
