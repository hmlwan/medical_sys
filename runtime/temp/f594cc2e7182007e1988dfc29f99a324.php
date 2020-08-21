<?php /*a:1:{s:60:"D:\WWW\medical_sys\application\index\view\publics\login.html";i:1593316560;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>登录</title>
    <script type="text/javascript" src="/static/js/fontSize.js"></script>
    <!--网页关键词-->
    <meta name="keywords" content="" />
    <!--网页描述-->
    <meta name="description" content="" />
    <!--适配当前屏幕-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--禁止自动识别电话号码-->
    <meta name="format-detection" content="telephone=no" />
    <!--禁止自动识别邮箱-->
    <meta content="email=no" name="format-detection" />
    <!--iphone中safari私有meta标签,
        允许全屏模式浏览,隐藏浏览器导航栏-->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!--iphone中safari顶端的状态条的样式black(黑色)-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!--如果用户装了谷歌浏览器，用ie浏览时使用谷歌内核-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!--css文件-->
    <link href="/static/css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/static/css/mui.loading.css"/>
    <link type="text/css" rel="stylesheet" href="/static/css/reset.css" />
    <link type="text/css" rel="stylesheet" href="/static/css/style.css" />
    <script src="/static/js/jquery1.10.2.min.js"></script>

</head>
<body>
<header>
    <!--<div class="header_bt">-->
    <!--    <div class="header_f"><a href="javascript:history.back()" class="header_fh"></a></div>-->
    <!--    <div class="header_c blod" style="font-size: .32rem;">登录</div>-->
    <!--    <div class="header_r blod" id="register" style="color: #05b793">注册</div>-->
    <!--</div>-->
    <div class="header_img"></div>
    <div style="height: 4.2rem;clear: both;"></div>
</header>
<!--<div class="zc-page">-->
<!--    <span class="title">手机号</span>-->
<!--</div>-->
<form id="login-form" action="<?php echo url('publics/login'); ?>" method="post" onsubmit="return false">
    <div class="zjmm-page zcdl-page">
        <input type="hidden" name="is_check" value="1" >
        <ul class="flexbox column">
            <li class="qh">
                <em>+86</em>
                <input type="text" name="account"  class="input_line" value="" placeholder="手机号">
            </li>
            <li style="margin-bottom: 0.2rem;">
                <input type="password" class="input_line" name="password"  placeholder="请输入密码">
                <i class="eye" onclick="openeye($(this))"></i>
            </li>
        </ul>
         <p class="forget_pwd" id="change-password">忘记密码?</p>
    </div>
    <div class="zjmm_btn zc-page_btn">
        <button class="button_btn" data-form="login-form" onclick="ajaxLogin(this)" >登录</button>
           <!--<p class="forget_pwd" id="register"><em style="color:#000">还没注册？</em>立即注册</p>-->
    </div>
</form>
<div class="aqyz-page" style="display: none">
    <form id="logincheck-form" action="<?php echo url('publics/login'); ?>" method="post" onsubmit="return false">
        <input type="hidden" name="is_check" value="0" >
        <input type="hidden" name="account"  class="input_line" value="" >
        <input type="hidden" name="password"  class="input_line" value="" >
        <div class="aqyz-view">
            <h2>安全验证</h2>
            <i class="close_icon"></i>
            <p class="tips">登录需要进行安全验证</p>
            <h2 class="mobile">181****7856</h2>
            <div class="aqyz_con">
                <input type="text" name="code" class="input_line" placeholder="短信验证码">
                <span>已发送</span>
            </div>
            <button class="yz_btn" data-form="logincheck-form" onclick="ajaxLogin(this)">确定</button>
        </div>
    </form>
</div>
</body>
<div class="zhezhao_h"></div>
<script src="/static/js/mui.min.js"></script>
<script src="/static/js/mui.loading.js"></script>
<script src="/static/js/main.js"></script>
<script>

    $(".close_icon").click(function () {
        $(".aqyz-page").hide();
        $(".zhezhao_h").removeClass('zhezhao');
    });
    function ajaxLogin(target) {
        var form = $(target).attr('data-form');
        var sub_data = $("#" + form).serialize();
        var show_account = $("input[name='account']",$("#" + form)).val();
        var show_password = $("input[name='password']",$("#" + form)).val();
        var url = $("#" + form).attr('action');
        mui.showLoading("处理中..", "div");
        $.post(url, sub_data, function (data) {
            mui.hideLoading();
            if (data.code == 0) {
                if (data.toUrl) {
                    mui.alert(data.message, '', function () {
                        window.location.href = data.toUrl;
                    })
                } else {
                    mui.alert(data.message, '');
                }
            }else if(data.code == 2){ //安全验证
//                login_send_code(show_account,show_password,data.data);
                $("input[name='account']",$("#logincheck-form")).val(show_account);
                $("input[name='password']",$("#logincheck-form")).val(show_password);
                $(".aqyz-page .mobile").html(data.data);
                $(".aqyz-page").show();
                $(".zhezhao_h").addClass('zhezhao');

            } else {
                if (data.toUrl) {
                    mui.alert(data.message, '', function () {
                        window.location.href = data.toUrl;
                    })
                } else {
                    mui.alert(data.message, '');
                }
            }
        }, 'json')
    }
    function login_send_code(mobile,password,show_mobile) {
        $.post("<?php echo url('publics/sendChange'); ?>", {mobile: mobile}, function (data) {
           console.log(data);
            if (data.code == 0) {
                $("input[name='account']",$("#logincheck-form")).val(mobile);
                $("input[name='password']",$("#logincheck-form")).val(password);
                $(".aqyz-page .mobile").html(show_mobile);
                $(".aqyz-page").show();
                $(".zhezhao_h").addClass('zhezhao');
            }else{
                mui.alert(data.message, '');
            }
        }, 'json')
    }
    mui.init();
    document.getElementById('change-password').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('publics/change'); ?>",
            id: 'change-password',
        })
    })
    document.getElementById('register').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('publics/register2'); ?>",
            id: 'register',
        })
    })
</script>
</html>

