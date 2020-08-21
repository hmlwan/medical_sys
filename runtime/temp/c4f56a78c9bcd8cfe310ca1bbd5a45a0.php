<?php /*a:4:{s:56:"D:\WWW\candyworld\application\admin\view\user\index.html";i:1594429962;s:57:"D:\WWW\candyworld\application\admin\view\layout\main.html";i:1591527760;s:59:"D:\WWW\candyworld\application\admin\view\layout\header.html";i:1591527760;s:57:"D:\WWW\candyworld\application\admin\view\layout\left.html";i:1591527760;}*/ ?>
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
        <h4>会员列表</h4>
    </header>
    <div class="panel-body" style="padding-bottom: 50px">
        <form class="form-horizontal" action="<?php echo url('admin/user/index'); ?>">
            <div class="form-group">
                <div class="col-xs-1 no-pd-r">
                    <select name="level" class="form-control">
                        <option  value="0">全部等级</option>
                        <option <?php if(app('request')->get('level') == 1): ?> selected <?php endif; ?> value="1">v1</option>
                        <option <?php if(app('request')->get('level') == 2): ?> selected <?php endif; ?> value="2">v2</option>
                        <option <?php if(app('request')->get('level') == 3): ?> selected <?php endif; ?> value="3">v3</option>
                        <option <?php if(app('request')->get('level') == 4): ?> selected <?php endif; ?> value="4">v4</option>
                        <option <?php if(app('request')->get('level') == 5): ?> selected <?php endif; ?> value="5">v5</option>
                    </select>
                </div>
                <div class="col-xs-1 no-pd-r">
                    <select name="type" class="form-control">
                        <option <?php if(app('request')->get('type') == 'mobile'): ?> selected <?php endif; ?> value="mobile">电话号码</option>
                        <option <?php if(app('request')->get('type') == 'nick_name'): ?> selected <?php endif; ?> value="nick_name">昵称</option>
                    </select>
                </div>
                <div class="col-xs-3 no-pd-l">
                    <input type="text" value="<?php echo htmlentities(app('request')->get('keyword')); ?>" name="keyword" class="form-control" placeholder="请输入关键词搜索">
                </div>
                <button type="submit" class="btn btn-color">搜索</button>
                <a type="button" href="/admin/user/create" class="btn btn-info">添加会员</a>
            </div>
        </form>
        <table class="table table-bordered table-striped no-margin">
            <thead>
            <tr>
            	  <th class="text-center">ID</th>
                <th class="text-center">手机号</th>
                <th class="text-center">邀请码</th>
                <th class="text-center">上级手机号</th>
                <th class="text-center">上上级手机号</th>
                <th class="text-center">团队信息</th>
                <th class="text-center">糖果数量</th>
                <th class="text-center">使用信息</th>
                <th class="text-center">登录状态</th>
                <th class="text-center">交易状态</th>
                <th class="text-center">是否认证</th>
                <th class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($list)): foreach($list as $entity): ?>
                <tr>
                	<td style="vertical-align: middle" class="text-center">
                        <?php echo htmlentities($entity->id); ?>
                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        <?php echo htmlentities($entity->mobile); ?>
                    </td>
                    <td style="vertical-align: middle" class="text-center"><?php echo htmlentities($entity->invite_code); ?></td>
                    <td style="vertical-align: middle" class="text-center">
                       <?php if(isset($entity->p_mobile)): ?>
                            <?php echo htmlentities($entity->p_mobile); else: ?>
                            无
                        <?php endif; ?>
                    </td>
                     <td style="vertical-align: middle" class="text-center">

                         <?php if(isset($entity->pp_mobile)): ?>
                            <?php echo htmlentities($entity->pp_mobile); else: ?>无<?php endif; ?>
                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        人数：<?php if(($entity->one_sub_nums)>0): ?><?php echo htmlentities($entity->one_sub_nums); else: ?>0<?php endif; ?>|<?php if(($entity->one_sub_nums)>0): ?><?php echo htmlentities($entity->two_sub_nums); else: ?>0<?php endif; ?> 人 <br>
                        团队贡献值：<?php if(($entity->contribution_nums)>0): ?><?php echo htmlentities($entity->contribution_nums); else: ?>0.00<?php endif; ?> <br>
                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        <?php echo htmlentities($entity->magic); ?><br>
                        <a href="<?php echo url('user/magicList',['keyword'=>$entity->mobile]); ?>" class="btn btn-xs btn-success">明细</a>
                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        <?php if($entity->login_ip): ?>
                            <?php echo htmlentities(date("Y-m-d H:i:s",!is_numeric($entity->login_time)? strtotime($entity->login_time) : $entity->login_time)); ?><br>
                            <?php echo htmlentities($entity->login_ip); else: ?>
                            <?php echo htmlentities(date("Y-m-d H:i:s",!is_numeric($entity->register_time)? strtotime($entity->register_time) : $entity->register_time)); ?><br>
                            <?php echo htmlentities($entity->register_ip); endif; ?>

                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        <?php if($entity->status == -1): ?>
                        <a class="btn btn-xs btn-danger">禁用</a>
                        <!-- <span>禁用时间：<?php echo htmlentities($entity->getForbiddenTime()); ?></span> -->
                        <?php else: ?>
                        <a class="btn btn-xs btn-success">正常</a>
                        <?php endif; ?>
                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        <?php if($entity->order_status == -1): ?>
                            <a class="btn btn-xs btn-danger">封禁</a>
                        <?php else: ?>
                            <a class="btn btn-xs btn-success">正常</a>
                        <?php endif; ?>
                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        <?php if($entity->is_certification == -1): ?>
                        <a class="btn btn-xs btn-danger">未认证/认证失败</a>
                        <?php elseif($entity->is_certification == 2): ?>
                        <a class="btn btn-xs btn-danger">认证失败</a>
                        <?php else: ?>
                        <a class="btn btn-xs btn-success">已认证</a>
                        <?php endif; ?>
                    </td>
                    <td style="vertical-align: middle" class="text-center">
                        <a href="<?php echo url('user/edit',['id'=>$entity->id]); ?>" class="btn btn-xs btn-info">编辑</a>
                            <?php if($entity->status == -1): ?>
                                <a data-url="<?php echo url('user/unforbidden',['id'=>$entity->id]); ?>" onclick="main.ajaxDelete(this)" data-msg="确定要解禁此会员吗？" class="btn btn-xs btn-success">解禁</a>
                            <?php else: ?>
                                <a data-url="<?php echo url('user/delete',['id'=>$entity->id]); ?>" onclick="main.ajaxDelete(this)" data-msg="确定要禁用此会员吗？" class="btn btn-xs btn-danger">禁用</a>
                            <?php endif; if($entity->order_status == -1): ?>
                            <a data-url="<?php echo url('user/unforbiddentrade',['id'=>$entity->id]); ?>" onclick="main.ajaxDelete(this)" data-msg="确定要解禁此会员交易吗？" class="btn btn-xs btn-success">解禁交易</a>
                            <?php else: ?>
                            <a data-url="<?php echo url('user/deletetrade',['id'=>$entity->id]); ?>" onclick="main.ajaxDelete(this)" data-msg="确定要禁用此会员交易吗？" class="btn btn-xs btn-danger">禁用交易</a>
                            <?php endif; ?>

                            <a data-url="<?php echo url('user/deleteuser',['id'=>$entity->id]); ?>" onclick="main.ajaxDelete(this)" data-msg="确定要删除此会员吗？" class="btn btn-xs btn-danger">删除</a>

                            <?php if($entity->is_certification != 1): ?>
                                <a href="<?php echo url('user/certification',['id'=>$entity->id]); ?>" class="btn btn-xs btn-success">去认证</a>
                            <?php else: ?>
                                <a href="<?php echo url('user/certification',['id'=>$entity->id]); ?>" class="btn btn-xs btn-success">认证信息</a>
                            <?php endif; ?>
                        <a  href="<?php echo url('user/recharge',['id'=>$entity->id]); ?>" class="btn btn-xs btn-info">充值</a>
                        <a  href="<?php echo url('user/rechargemagic',['id'=>$entity->id]); ?>" class="btn btn-xs btn-info">赠送空间</a>
                        <!--<?php if($entity->level < 5): ?>-->
                        <!--    <a onclick="upgrade(this)" data-level="<?php echo htmlentities($entity->level); ?>" data-id="<?php echo htmlentities($entity->id); ?>" class="btn btn-xs btn-info">手动升级</a>-->
                        <!--<?php endif; ?>-->
                    </td>
                </tr>
                <?php endforeach; else: ?>
            <tr class="text-center">
                <td colspan="9">暂无数据</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <div class="page">
            <?php echo htmlspecialchars_decode($list->render()); ?>
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
    function upgrade(e){
        var id = $(e).attr('data-id');
        var level = parseInt($(e).attr('data-level'));
        var content = '<div class="text-center" style="border-top:1px solid #eee;padding-top: 20px">' +
                '<form class="form-horizontal" id="user-level" method="post" onsubmit="return false" role="form">' +
                '<div class="form-group"> <label class="col-sm-4 control-label">等级</label> <div class="col-sm-7"><select class="form-control" id="level">';
        for(var i = level + 1; i<=5; i++){
            content += '<option value="'+i+'">lv'+i+'</option>';
        }
            content += '</select></div></div><div class="form-group"> <label class="col-sm-4 control-label">送奖励</label><div class="col-sm-7"><select class="form-control" id="is_reward">' +
                '<option value="1">赠送</option><option value="0">不赠送</option></select></div></div></form></div>';

        $.confirm({
            title: '<strong style="color: #c7254e;font-size: 16px">会员升级</strong>',
            content: content,
            confirmButton: '确定',
            confirmButtonClass: 'btn btn-info',
            cancelButton: '取消',
            cancelButtonClass: 'btn btn-danger',
            animation: 'scaleY',
            theme: 'material',
            confirm: function () {
                var levels = $("#user-level").find("#level").val();
                var is_reward = $("#user-level").find("#is_reward").val();
                $.ajax({
                    url: "<?php echo url('user/level'); ?>",
                    method: 'POST',
                    data:{
                        user_id:id,
                        level:levels,
                        is_reward:is_reward
                    },
                    dataType: 'json',
                    success: function (response) {
                        if(response.code == 1){
                            main.waringAlert(response.message)
                        }else{
                            if (response.toUrl) {
                                window.location.href = response.toUrl;
                                return false;
                            }else{
                                window.location.reload();
                                return false;
                            }
                        }
                    },
                })
            }

        });
    }
</script>


</body>
</html>