<?php /*a:4:{s:90:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/member/memberinfo.html";i:1594429962;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;s:83:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/nav.html";i:1592154544;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title>我的</title>
    
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
<body class="tgzp-page" style="background-color: #f7f7f7">

<div  class="wd-page">
    <input type="hidden" name="mui-tab-item" value="5">
    <div class="wd-header">
       <div class="header_l flexbox wrap">
       	<input type="hidden" name="mui-tab-item" value="5">
           <?php if($list->avatar): ?>
           <img class="header" src="<?php echo htmlentities($list->avatar); ?>"  id="avatar" >
           <?php else: ?>
           <img class="header" src="/static/img/newimg/touxiang.png"  id="avatar"  >
           <?php endif; ?>
              <input type="file" id="file" name="image" style="display: none;">
           <div class="flexbox column wd_info spacearound">
               <span><?php echo htmlentities($list->show_mobile); ?></span>
               <?php if($list->invite_code): ?>
                <span>邀请码:<?php echo htmlentities($list->invite_code); ?> <i class="copy_r" data-clipboard-text="<?php echo htmlentities($list->invite_code); ?>"></i></span>
               <?php endif; ?>
           </div>
       </div>
       <!--<i class="arrow_r"  id="wd_header_url" style="margin-right: .1rem;"></i>-->
    </div>
    <div class="wd-middle">
        <div class="middle-l">
            <p>总资产(糖果)</p>
            <p><?php echo htmlentities($list->show_magic); ?> <em> ≈ ¥<?php echo htmlentities($list->show_magic); ?></em></p>
        </div>
    </div>
    <div class="wd-content">
       <ul class="flexbox column">
           <a href="<?php echo url('index/invite/index','','',true); ?>">
               <li>
                   <div>
                       <img src="/static/img/newimg/wd_icon1.png" alt="">
                       <span>邀请好友</span>
                   </div>
                   <i class="arrow_r"></i>
               </li>
           </a>
           <a href="<?php echo url('index/invite/invite_star','','',true); ?>">
               <li>
                   <div>
                       <img src="/static/img/newimg/wd_icon2.png" alt="">
                       <span>邀请扶持</span>
                   </div>
                   <i class="arrow_r"></i>
               </li>
           </a>
           <a href="<?php echo url('index/member/magicloglist','','',true); ?>">
               <li>
                   <div>
                       <img src="/static/img/newimg/wd_icon3.png" alt="">
                       <span>收益总计</span>
                   </div>
                   <i class="arrow_r"></i>
               </li>
           </a>
           <a href="<?php echo url('index/member/post?category=2','','',true); ?>">
               <li>

                   <div>
                       <img src="/static/img/newimg/wd_icon4.png" alt="">
                       <span>攻略教程
                       <?php if($no_read_post1 == 1): ?>
                       <em></em>
                       <?php endif; ?>
                           </span>
                   </div>
                   <i class="arrow_r"></i>

               </li>
           </a>
           <a href="<?php echo url('index/member/post?category=1','','',true); ?>">
               <li>
                   <div>
                       <img src="/static/img/newimg/wd_icon5.png" alt="">
                       <span>官方公告
                       <?php if($no_read_post == 1): ?>
                       <em></em>
                       <?php endif; ?>
                           </span>
                   </div>
                   <i class="arrow_r"></i>
               </li>
           </a>
           <a href="<?php echo url('index/member/message','','',true); ?>">
               <li>
                   <div>
                       <img src="/static/img/newimg/wd_icon6.png" alt="">
                       <span>问题反馈
                       <?php if(count($is_read_list)>0): ?>
                           <em></em>
                       <?php endif; ?>
                       </span>
                   </div>
                   <i class="arrow_r"></i>
               </li>
           </a>
           <a href="<?php echo url('index/member/set','','',true); ?>">
               <li>
                   <div>
                       <img src="/static/img/newimg/wd_icon7.png" alt="">
                       <span>个人设置</span>
                   </div>
                   <i class="arrow_r"></i>
               </li>
           </a>
           <?php if($parent_info->mobile): ?>
               <li>
                   <div>
                       <img src="/static/img/newimg/wx.png" style="width: .36rem;height: .36rem" alt="">
                       <span>上级微信</span>&nbsp;
                       <span class="copy1" style="font-size: .26rem"><?php echo htmlentities($parent_info->mobile); ?></span>
                   </div>
                   <i class="copy_r"  data-clipboard-text="<?php echo htmlentities($parent_info->mobile); ?>"></i>
               </li>
           <?php endif; ?>
       </ul>
    </div>
</div>
<div class="is_cert" id="is_cert" style="display: <?php if($list->is_certification != 1): ?>inline_block <?php else: ?>none <?php endif; ?>">
    <img src="/static/img/newimg/sm_icon.png" alt="">
</div>
<div class="receive_reward" style="display: <?php if($is_jump_ad != 1): ?>inline_block<?php else: ?>none<?php endif; ?>">
    <h2>待领取</h2>
    <i class="close_icon"></i>
    <p class="candy">获得<em><?php echo htmlentities((isset($condy_sum_num) && ($condy_sum_num !== '')?$condy_sum_num:"0.00")); ?></em>糖果</p>
    <p><a href="<?php echo htmlentities((isset($adv_info['ad_url']) && ($adv_info['ad_url'] !== '')?$adv_info['ad_url']:'')); ?>" target="_blank"><img src="<?php echo htmlentities((isset($adv_info['ad_logo']) && ($adv_info['ad_logo'] !== '')?$adv_info['ad_logo']:'/static/img/newimg/t1.png')); ?>" alt=""></a></p>
    <p>已发放至 “糖果余额”</p>
    <p><i class="go_back"></i></p>
</div>

<div class="<?php if(($list->is_certification != 1) || $is_jump_ad != 1): ?>zhezhao<?php endif; ?>"></div>
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
    var clipboard = new Clipboard('.copy_r');
    clipboard.on('success', function(e) {
        console.log(e);
        mui.alert('复制成功', '');
        e.clearSelection();
    });
    document.getElementById('is_cert').addEventListener('tap', function () {
        mui.openWindow({
            url: "<?php echo url('/index/publics/cert'); ?>",
            id: 'is_cert',
        })
    });
    $(".close_icon,.go_back").click(function () {
        $(".receive_reward").hide();
        $(".zhezhao_h").removeClass('zhezhao');
        window.location.href = '/index/product/index';
    });

</script>
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
