<?php /*a:3:{s:87:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/member/message.html";i:1591527760;s:84:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/head.html";i:1591527760;s:86:"/Applications/MAMP/htdocs/project/candyworld/application/index/view/layout/footer.html";i:1591527760;}*/ ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>客服</title>
    
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
<link type="text/css" rel="stylesheet" href="/static/css/public.css" />

<style type="text/css">
    .cont {
        background: white;
        height: auto;
    }

    .ter {
        background: white;
        height: auto;
    }
</style>
<body>
<header class="mui-bar mui-bar-nav my-header" style="background-color: #17c3e5">
    <a href="/index/member/index" class="mui-icon mui-icon-left-nav mui-pull-left"
       ></a>
    <h1 id="title" class="mui-title">客服</h1>
</header>
<div class="mui-content cont" >
    <p style="font-size: .26rem;padding: 10px;background: #f7f7f7">问题留言</p>
    <form action="<?php echo url('member/submitMsg'); ?>" style="height: 170px;padding: 0" method="post" onsubmit="return false" id="submitForm">
        <textarea style="font-size: .28rem;padding-left: .2rem;padding-top: .3rem;color:grey;border: none;height: 2rem" name="content" id="content" placeholder="请您输入您的问题，我们将尽快回复您"></textarea>
        <button data-form="submitForm" onclick="ajaxPost(this)" type="submit"
                style="width: 25%;height: 30px;border: none;background: #ffd21f;float: right;margin-right: 10px">提交
        </button>
    </form>
    <div style="margin-bottom:1rem">
        <?php foreach($list as $list): ?>
        <div style="text-align: center;color: gray;background: #f7f7f7;padding: 10px">
            <?php echo htmlentities($list->getCreateTime()); ?>
        </div>
        <div class="mui-table-view ter"  onclick="open_msg($(this))" data-is_open="0">
            <div class="mui-table-view-cell mui-media" >

                <div>
                    <?php if($list->avatar): ?>
                    <img class="mui-media-object mui-pull-left" src="<?php echo htmlentities($list->avatar); ?>" style="width: 20%;">
                    <?php else: ?>
                    <img class="mui-media-object mui-pull-left" src="/static/img/headphoto.png" style="width: 20%;">
                    <?php endif; ?>
                    <div class="mui-media-body" style="color: #8f8f94;margin-top: 2px;">
                        <?php echo htmlentities($list->nick_name); ?>
                        <p class='mui-ellipsis-2' style="margin-top: 5px;"><?php echo htmlentities($list->content); ?></p>
                        查看回复
                        <?php if($list->reply == ''): ?>
                        <p class='mui-word-break' style="display: none"> 暂无回复</p>
                        <?php else: ?>
                        <div class='mui-word-break' style="display: none"><?php echo htmlspecialchars_decode($list->reply); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!--footer-->
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
	
<script>

    function open_msg(obj) {
        var is_open = obj.data('is_open');
        console.log(is_open);
        if(is_open == '0'){
            obj.data('is_open','1');
            obj.find('.mui-word-break').css('display','-webkit-box');
        }else{
            obj.data('is_open','0');
            obj.find('.mui-word-break').css('display','none');

        }
    }

</script>
</body>
</html>