<?php /*a:4:{s:57:"D:\WWW\candyworld\application\admin\view\index\index.html";i:1594429962;s:57:"D:\WWW\candyworld\application\admin\view\layout\main.html";i:1591527760;s:59:"D:\WWW\candyworld\application\admin\view\layout\header.html";i:1591527760;s:57:"D:\WWW\candyworld\application\admin\view\layout\left.html";i:1591527760;}*/ ?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Flat, Clean, Responsive, admin template built with bootstrap 3">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title>后台管理</title>
    <link rel="stylesheet" href="/admin/vendor/offline/theme.css">
    <link rel="stylesheet" href="/admin/vendor/pace/theme.css">

    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/font-awesome.min.css">
    <link rel="stylesheet" href="/admin/css/animate.min.css">

    <link rel="stylesheet" href="/admin/css/panel.css">

    <link rel="stylesheet" href="/admin/css/skins/palette.1.css" id="skin">
    <link rel="stylesheet" href="/admin/css/fonts/style.1.css" id="font">
    <link rel="stylesheet" href="/admin/css/jquery.confirm.css">
    <link rel="stylesheet" href="/admin/css/main.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/admin/vendor/jquery-1.11.1.min.js"></script>
    <script src="/admin/vendor/modernizr.js"></script>
    


</head>

<body>
<div class="app">
    <header class="header header-fixed navbar">
    <div class="brand" style="background-color: #535A7C">
        <a href="javascript:;" class="fa fa-bars off-left visible-xs" data-toggle="off-canvas" data-move="ltr"></a>
        <a href="<?php echo url('admin/index/index'); ?>" class="navbar-brand">
            <i class="fa fa-stop mg-r-sm"></i>
            <span class="heading-font">后台管理系统</span>
        </a>
    </div>

    <ul class="nav navbar-nav navbar-right off-right">
        <li class="quickmenu">
            <a href="javascript:;" data-toggle="dropdown">
                <?php echo htmlentities($manage['name']); ?>
                <i class="caret mg-l-xs hidden-xs no-margin"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right mg-r-xs">
                <li>
                    <a href="<?php echo url('index/updateInfo'); ?>">修改密码</a>
                </li>
                <li>
                    <a onclick="logoutSys(this)" data-href="<?php echo url('index/logout'); ?>">退出</a>
                </li>
            </ul>
        </li>
    </ul>
</header>
<script>
    function logoutSys(event) {
        var url = $(event).attr('data-href');
        $.confirm({
            title: '<strong style="color: #c7254e;font-size: 16px">温馨提示</strong>',
            content: '<div class="text-center" style="border-top:1px solid #eee;padding-top: 20px">你确定要退出系统吗?</div>',
            confirmButton: '确定',
            confirmButtonClass: 'btn btn-info',
            cancelButton: '取消',
            cancelButtonClass: 'btn btn-danger',
            animation: 'scaleY',
            theme: 'material',
            confirm: function () {
                window.location.href = url;
            }
        })
    }
</script>

    <section class="layout" style="padding-top: 50px">

        <aside class="sidebar collapsible canvas-left">
    <div class="scroll-menu">

        <nav class="main-navigation slimscroll" data-height="auto" data-size="4px" data-color="#ddd" data-distance="0">
            <ul id="left-menu">
                <li><a href="<?php echo url('index/index'); ?>"><i class="fa fa-home"></i><span>后台首页</span></a></li>
                <?php foreach($menus as $menu): if($menu->getLevel() == 1): ?>
                        <li class="dropdown">
                            <a href="javascript:;" data-toggle="dropdown">
                                <i class="<?php echo config('app.menu_icon')[$menu->getShrotName()] ?? ''; ?>"></i><span><?php echo htmlentities($menu->getShrotName()); ?></span><i
                                    class="toggle-accordion"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach($menus as $child): if($child->getParentId() == $menu->getId()): ?>

                                        <li <?php echo htmlentities($child->checkPathActive()); if($child->checkPathActive()): ?> class="active" <?php endif; ?>><a
                                                href="<?php echo htmlentities($child->getUrl()); ?>"><span><?php echo htmlentities($child->getShrotName()); ?></span></a>
                                        </li>
                                    <?php endif; endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; endforeach; ?>
            </ul>
        </nav>
    </div>
    <footer>
        <div class="footer-toolbar pull-left">
            <a href="javascript:;" class="toggle-sidebar pull-right hidden-xs">
                <i class="fa fa-angle-left"></i>
            </a>
        </div>
    </footer>
    <script>
        $(function () {
            $("#left-menu").find('.dropdown').each(function () {
                if ($(this).find('li').hasClass('active')) {
                    $(this).addClass('collapse-open open');
                }else{
                    $(this).removeClass('collapse-open open');
                }
            })
        })
    </script>
</aside>

        <section class="main-content" >

            <div class="content-wrap ">
                
<div class="row">
    <div class="col-lg-6 col-sm-6" style="padding-left: 0">
        <section class="panel ">
            <header class="panel-heading">
                <h4>会员等级分布图</h4>
            </header>
            <div class="panel-body">
                <div id="user" style="width: 100%;height: 200px">

                </div>
            </div>
        </section>
    </div>
    <div class="col-lg-6 col-sm-6" style="padding-right: 0">
        <section class="panel">
            <header class="panel-heading">
                <h4>空间分布图</h4>
            </header>
            <div class="panel-body">
                <div id="magic" style="width: 100%;height: 200px">

                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3" style="padding-left: 0">
        <section class="panel ">
            <header class="panel-heading">
                <h4>会员数量</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($user['total']) ? htmlentities($user['total']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3">
        <section class="panel">
            <header class="panel-heading">
                <h4>今日会员注册数量</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($user['today']) ? htmlentities($user['today']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3">
        <section class="panel ">
            <header class="panel-heading">
                <h4>已认证会员数量</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($user['auth']) ? htmlentities($user['auth']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3" style="padding-right: 0">
        <section class="panel">
            <header class="panel-heading">
                <h4>未认证会员数量</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($user['no_auth']) ? htmlentities($user['no_auth']) :  0; ?></h3>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3" style="padding-left: 0">
        <section class="panel ">
            <header class="panel-heading">
                <h4>累计交易数量(等待匹配)</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['match']) ? htmlentities($order['match']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3">
        <section class="panel">
            <header class="panel-heading">
                <h4>累计交易数量(等待付款)</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['pay']) ? htmlentities($order['pay']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3">
        <section class="panel ">
            <header class="panel-heading">
                <h4>累计交易数量(等待收款)</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['confirm']) ? htmlentities($order['confirm']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3" style="padding-right: 0">
        <section class="panel">
            <header class="panel-heading">
                <h4>累计交易数量(已完成)</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['finish']) ? htmlentities($order['finish']) :  0; ?></h3>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3" style="padding-left: 0">
        <section class="panel ">
            <header class="panel-heading">
                <h4>全网所有糖果总和</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($user['total_magic']) ? htmlentities($user['total_magic']) :  0.00; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3">
        <section class="panel">
            <header class="panel-heading">
                <h4>全网所有算力总和</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($user['total_energy']) ? htmlentities($user['total_energy']) :  0.00; ?></h3>
            </div>
        </section>
    </div>
</div>
<!--<div class="row">
    <div class="col-lg-3 col-md-3" style="padding-left: 0">
        <section class="panel ">
            <header class="panel-heading">
                <h4>平台总交易手续费</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['match']) ? htmlentities($order['match']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3">
        <section class="panel">
            <header class="panel-heading">
                <h4>平台总虚拟币</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['pay']) ? htmlentities($order['pay']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3">
        <section class="panel ">
            <header class="panel-heading">
                <h4>平台总开采率</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['confirm']) ? htmlentities($order['confirm']) :  0; ?></h3>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-md-3" style="padding-right: 0">
        <section class="panel">
            <header class="panel-heading">
                <h4>平台总开采</h4>
            </header>
            <div class="panel-body">
                <h3><?php echo !empty($order['finish']) ? htmlentities($order['finish']) :  0; ?></h3>
            </div>
        </section>
    </div>
</div>-->


            </div>

            <div class="site-overlay"></div>
        </section>

    </section>
</div>

<script src="/admin/bootstrap/js/bootstrap.js"></script>
<script src="/admin/vendor/jquery.easing.min.js"></script>
<script src="/admin/vendor/jquery.placeholder.js"></script>
<script src="/admin/vendor/fastclick.js"></script>
<script src="/admin/vendor/jquery.slimscroll.js"></script>
<script src="/admin/vendor/offline/offline.min.js"></script>
<script src="/admin/vendor/pace/pace.min.js"></script>
<script src="/admin/js/off-canvas.js"></script>
<script src="/admin/js/jquery.confirm.js"></script>
<script src="/admin/js/main.js"></script>
<script src="/admin/js/panel.js"></script>


<script src="/admin/js/echarts.min.js"></script>
<script>
    var userChart = echarts.init(document.getElementById('user'));
    var user = {
        title: {
            text: '会员等级分布图',
            x: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['Lv1', 'Lv2', 'Lv3', 'Lv4', 'Lv5']
        },
        series: [
            {
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: [
                    {value: '<?php echo !empty($userLevel[1]) ? htmlentities($userLevel[1]) :  0; ?>', name: 'Lv1'},
                    {value: '<?php echo !empty($userLevel[2]) ? htmlentities($userLevel[2]) :  0; ?>', name: 'Lv2'},
                    {value: '<?php echo !empty($userLevel[3]) ? htmlentities($userLevel[3]) :  0; ?>', name: 'Lv3'},
                    {value: '<?php echo !empty($userLevel[4]) ? htmlentities($userLevel[4]) :  0; ?>', name: 'Lv4'},
                    {value: '<?php echo !empty($userLevel[5]) ? htmlentities($userLevel[5]) :  0; ?>', name: 'Lv5'}
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };
    userChart.setOption(user);
</script>
<script>
    var magicChart = echarts.init(document.getElementById('magic'));
    var magic = {
        title: {
            text: '空间分布图',
            x: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['运行中', '已过期']
        },
        series: [
            {
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: [
                    {value: "<?php echo !empty($magic['running']) ? htmlentities($magic['running']) :  0; ?>", name: '运行中'},
                    {value: "<?php echo !empty($magic['stop']) ? htmlentities($magic['stop']) :  0; ?>", name: '已过期'}
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };
    magicChart.setOption(magic);
</script>
<script>
    /*var orderChart = echarts.init(document.getElementById('order'));
     orders = {
     color: ['#3398DB'],
     tooltip : {
     trigger: 'axis',
     axisPointer : {            // 坐标轴指示器，坐标轴触发有效
     type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
     }
     },
     grid: {
     left: '3%',
     right: '4%',
     bottom: '3%',
     containLabel: true
     },
     xAxis : [
     {
     type : 'category',
     data : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun','Sun'],
     axisTick: {
     alignWithLabel: true
     }
     }
     ],
     yAxis : [
     {
     type : 'value'
     }
     ],
     series : [
     {
     name:'直接访问',
     type:'bar',
     barWidth: '60%',
     data:[10, 52, 200, 334, 390, 330, 220,10, 52, 200, 334, 390, 330, 220,10]
     }
     ]
     };
     orderChart.setOption(orders);*/
</script>

</body>
</html>