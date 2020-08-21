<?php /*a:1:{s:69:"C:\wwwroot\www.dayuli.cn\application\index\view\publics\register.html";i:1592303530;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>免费注册</title>
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
    <link type="text/css" rel="stylesheet" href="/static/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/static/css/reset.css" />

    <script src="/static/js/jquery1.10.2.min.js"></script>
    <script src="/static/js/mui.min.js"></script>
    <script src="/static/js/mui.loading.js"></script>
    <script src="/static/js/main.js"></script>
</head>
<body>
<header>
    <div class="header_bt">
        <div class="header_f"><a href="javascript:history.back()" class="header_fh"></a></div>
        <div class="header_c blod" style="font-size: .35rem; color:#05b793">云链空间</div>
        <div class="header_r blod" id="login" style="color: #05b793">登录</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<div class="zc-page">
    <span class="title">手机注册</span>
</div>
<form id="register_form" action="<?php echo url('publics/doRegister'); ?>" method="post" onsubmit="return false">

    <div class="zjmm-page zcdl-page">
        <ul class="flexbox column">
            <li class="qh">
                <em>+86</em>
                <input type="text" class="input_line required" name="mobile"   id="mobile" value="" placeholder="手机号">
            </li>
            <li>
                <input type="password" class="input_line required"  name="password"  style="width:87%" placeholder="请输入登录密码">
                <i class="eye" onclick="openeye($(this))"></i>
            </li>
            <li>
                <input type="password" class="input_line required"    name="safe_password"  style="width:87%" placeholder="请输入资金密码">
                <i class="eye" onclick="openeye($(this))"></i>
            </li>
            <li>
                <input type="text" class="input_line" name="code"  style="width:71%"  placeholder="手机验证码">
                <button class="send-code" data-url="<?php echo url('publics/send',array('token'=>$token)); ?>" data-form="register_form" data-mobile="mobile" onclick="sendCode(this)"
                      id="send-code">发送验证码</button>
            </li>
            <li>
                <input type="text" class="input_line required"  name="invite_code" <?php if($code): ?>readonly<?php endif; ?> value="<?php echo htmlentities($code); ?>" style="width:100%"  placeholder="邀请码">
            </li>
        </ul>
    </div>
    <div class="zjmm_btn zc-page_btn">
        <button class="button_btn " data-form="register_form" onclick="ajaxPost(this)">注册</button>
    </div>
</form>
</body>
</html>
<script>
    mui.init();
    document.getElementById('login').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('publics/download.html'); ?>",
            id: 'login',
        })
    })
</script>
