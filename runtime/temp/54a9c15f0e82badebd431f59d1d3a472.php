<?php /*a:4:{s:86:"/Applications/MAMP/htdocs/project/candyworld/application/admin/view/article/index.html";i:1592563336;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/admin/view/layout/main.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/admin/view/layout/header.html";i:1591527760;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/admin/view/layout/left.html";i:1591527760;}*/ ?>
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
                
<section class="panel">
    <header class="panel-heading">
        <h4>公告列表</h4>
    </header>
    <div class="panel-body" style="padding-bottom: 50px">
        <form class="form-horizontal" action="<?php echo url('article/index'); ?>">
            <div class="form-group">
                <div class="col-xs-3">
                    <select name="type" class="form-control">
                        <option value="0">全部分类</option>
                        <?php foreach($cate as $key=>$item): ?>
                            <option <?php if(app('request')->get('type') == $key): ?>selected<?php endif; ?> value="<?php echo htmlentities($key); ?>"><?php echo htmlentities($item); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-color">搜索</button>
                <a type="button" href="<?php echo url('/admin/article/create'); ?>" class="btn btn-info">添加文章</a>
            </div>
        </form>
        <table class="table table-bordered table-striped no-margin">
            <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">标题</th>
                <th class="text-center">类型</th>
                <th class="text-center">缩略图</th>
                <th class="text-center">内容</th>
                <th class="text-center">状态</th>
                <th class="text-center">创建时间</th>
                <th class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($list)): foreach($list as $entity): ?>
            <tr>
                <td class="text-center"><?php echo htmlentities($entity->article_id); ?></td>
                <td class="text-center"><?php echo htmlentities($entity->title); ?></td>
                <td class="text-center"><?php echo htmlentities($entity->getCate()); ?></td>
                <td class="text-center">
                    <img src="<?php echo htmlentities($entity->img); ?>" style="width: 80px;height: 80px" alt="">
                </td>
                <td class="text-center">
                    <a onclick="showContent(this)" data-content="<?php echo htmlentities($entity->content); ?>" class="btn btn-xs btn-primary">查看</a>
                </td>
                <td class="text-center">
                    <?php if($entity->isShow()): ?>
                        <a class="btn btn-xs btn-primary">已发布</a>
                    <?php else: ?>
                        <a class="btn btn-xs btn-warning">未发布</a>
                    <?php endif; ?>

                </td>
                <td class="text-center"><?php echo htmlentities(date("Y-m-d H:i:s",!is_numeric($entity->getCreateTime())? strtotime($entity->getCreateTime()) : $entity->getCreateTime())); ?></td>
                <td class="text-center">
                    <a href="<?php echo url('article/edit',['id'=>$entity->article_id]); ?>" class="btn btn-xs btn-info">编辑</a>
                    <a data-url="<?php echo url('article/delete',['id'=>$entity->article_id]); ?>" onclick="main.ajaxDelete(this)"
                       data-msg="确定要删除该公告吗？" class="btn btn-xs btn-danger">删除</a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr class="text-center">
                <td colspan="5">暂无数据</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <div class="page">
            <?php echo $list->render(); ?>

        </div>
    </div>
</section>

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


    <script>
        function showContent(e){
            $.dialog({
                title: '文章内容',
                content: $(e).attr('data-content')
            });
        }
    </script>

</body>
</html>