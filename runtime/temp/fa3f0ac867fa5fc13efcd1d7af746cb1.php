<?php /*a:4:{s:63:"C:\wwwroot\www.dayuli.cn\application\index\view\member\set.html";i:1591527760;s:64:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\head.html";i:1591527760;s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\footer.html";i:1591527760;s:63:"C:\wwwroot\www.dayuli.cn\application\index\view\layout\nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>个人信息</title>
    
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
<body style="background-color: #f7f7f7">
<header>
    <div class="header_bt">
        <div class="header_f"><a href="<?php echo url('index'); ?>" class="header_fh"></a></div>
        <div class="header_c">个人信息</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<div class="grxx-page">
    <input type="hidden" name="mui-tab-item" value="5">
    <?php if($list->avatar): ?>
        <img src="<?php echo htmlentities($list->avatar); ?>" id="avatar" alt="">
    <?php else: ?>
        <img src="/static/img/newimg/touxiang.png" id="avatar" alt="">
    <?php endif; ?>
    <input type="file" id="file" name="image" style="display: none;">
    <div class="grxx_content">
        <ul class="flexbox column">

            <a href="javascript:;">
                <li>
                    <span>手机号</span>
                    <span><?php echo htmlentities($list->show_mobile); ?></span>
                </li>
            </a>
            <a href="/index/member/certification">
                <li>
                    <span>姓名</span>
                    <span>
                        <?php if($list->is_certification == 1): ?>
                            已认证
                        <?php elseif($list->is_certification == -1): ?>
                            未认证
                        <?php elseif($list->is_certification == 2): ?>
                            认证失败
                        <?php endif; ?>
                        <i class="arrow_r"></i>
                    </span>
                </li>
            </a>
            <a href="/index/member/password">
                <li>
                    <span>登录密码</span>
                    <span>修改<i class="arrow_r"></i></span>
                </li>
            </a>


            <a href="/index/member/safepassword_view">
                <li>
                <span>资金密码</span>
                <span>修改<i class="arrow_r"></i></span>
                </li>
            </a>

            <a href="/index/member/zfb">
                <li>
                    <span>支付宝</span>
                    <span><?php echo htmlentities((isset($list->zfb) && ($list->zfb !== '')?$list->zfb:"绑定")); ?><i class="arrow_r"></i></span>
                </li>
            </a>
        </ul>
    </div>
</div>
<div class="grxx_btn">
    <button class="button_btn" onclick="logout()">退出登录</button>
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
    function logout(){
        window.location.href = "<?php echo url('member/logout'); ?>";
    }
    $("#avatar").click(function(){
        $("#file").click();
    });
    $("#file").change(function(){
        var $this = $(this);
        var file = this.files[0];
        if(file.length == 0)
        {
            mui.alert("请选择要上传的图片");
            return false;
        }
        var data = new FormData();
        data.append('image',file);
        // console.log(data);return false;
        mui.showLoading("正在上传头像...");
        $.ajax({
            url:"/index/upload/uploadEditor",
            type:"post",
            data:data,
            processData:false,
            contentType:false,
            dataType:'json',
            success:function(data){
                var url = data.data[0];
                if(data.errno == 0)
                {
                    mui.showLoading("头像上传成功，正在保存...");
                    $.ajax({
                        url:"/index/member/updateUser",
                        type:"post",
                        data:{'avatar' : data.data[0]},
                        dataType:'json',
                        success:function(data){
                            mui.alert(data.message);
                            if(data.code == 0)
                            {
                                mui.hideLoading();
                                $("#avatar").attr("src", url);
                            }
                        }
                    })
                }
                else
                {
                    mui.alert(data.fail);
                }
            }
        })
    })
</script>
</body>
</html>

