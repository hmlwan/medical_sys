<?php /*a:1:{s:66:"C:\wwwroot\www.dayuli.cn\application\index\view\publics\cert2.html";i:1592551030;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>实名认证</title>
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
<body class="cert-body" style="background-color: #f0f0f0">
<header>
    <div class="header_bt">
        <div class="header_f"></div>
        <div class="header_c">实名认证</div>
    </div>
    <div style="height: .74rem;clear: both;"></div>
</header>
<form  id="cert_form" action="<?php echo url('publics/cert_api'); ?>" method="post" onsubmit="return false">
    <input type="hidden" name="type" value="2">
    <div class="cert-page">
        <ul class="flexbox column">
            <li>
                <span>姓名</span>
                <span>
                    <input type="text" name="real_name" placeholder="请填写姓名">
                </span>
            </li>
            <li>
                <span>身份证</span>
                <span>
                    <input type="text" name="card_id" placeholder="请填写身份证">
                </span>
            </li>
            <li>
                <span>银行卡号</span>
                <span>
                    <input type="text" name="card"  placeholder="请填写银行卡号">
                </span>
            </li>
            <li>
                <span>手机号码</span>
                <span>
                    <input type="text" name="mobile" readonly value="<?php echo htmlentities($mobile); ?>" >
                </span>
            </li>
        </ul>
        <p class="tips">验证开卡人银行卡号、身份证号码、姓名、手机号是否一致。
            <!--<em id="change">更换验证方式</em>-->
        </p>
    </div>
    <button class="th_btn"  data-form="cert_form" onclick="ajaxPost(this)">提交</button>
</form>
</body>
<script src="/static/js/mui.min.js"></script>
<script src="/static/js/mui.loading.js"></script>
<script src="/static/js/main.js"></script>
<script>
    mui.init();
    document.getElementById('change').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('/index/publics/cert'); ?>",
            id: 'change',
        })
    });
</script>

</html>

