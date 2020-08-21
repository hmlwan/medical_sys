function sendCode(target) {
    var url = $(target).attr('data-url');

    var mobile = $("#" + $(target).attr('data-mobile')).val();

    var form = $(target).attr('data-form');
    console.log(form);
    var check_ret = check_require($("#" + form));
    if(check_ret.ret != 0){
        mui.alert(check_ret.msg);
        return false;
    }
    if (!mobile) {
        mui.alert('请填写手机号码');
        return false;
    }
    mui.showLoading("发送中..", "div");

    $.post(url, {mobile: mobile}, function (data) {
        mui.hideLoading();
        mui.alert(data.message);
        if (data.code == 0) {
            $("#send-code").html(60);
            daojishi();
            $("#send-code").attr('disabled', true);
        }
    }, 'json')
}

function daojishi() {
    var daojishi = setInterval(function () {
        var time = parseInt($("#send-code").html());
        if (time <= 1) {
            $("#send-code").html('发送验证码');
            $("#send-code").attr('disabled', false);
            clearInterval(daojishi);
        } else {
            $("#send-code").html(time - 1);
        }
    }, 1000)
}

function ajaxPost(target) {console.log(555);
    var form = $(target).attr('data-form');

    var data = $("#" + form).serialize();
    var url = $("#" + form).attr('action');
    mui.showLoading("处理中..", "div");
    $.post(url, data, function (data) {
        mui.hideLoading();
        if (data.code == 0) {
            if (data.toUrl) {
                mui.alert(data.message, '', function () {
                    window.location.href = data.toUrl;
                })
            } else {
                mui.alert(data.message, '');
            }
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
function check_require(obj) {
    //检测

    var msg = '';
    var is_require = 0;
    obj.find("input,textarea").each(function () {
        var attr  = $(this).hasClass('required');
        var val = $(this).val();
        if(attr == true&& !val){
            is_require = 1;
            msg = $(this).attr('placeholder');
            msg = msg.replace('请输入','').replace('输入','');
            return false;
        }
    });

    if(is_require == 1){
        return {
            'ret':1,
            'msg':'请输入'+msg
        };
    }
    return {
        'ret':0
    };
}

function goback() {
    window.location.go(-1);
}

function jump(url)
{
    window.location.href = url;
}
function openeye(obj) {
    var active = obj.attr('data-active');
    if(active == 1){
        obj.prev("input[type='text']").attr('type','password');
        obj.attr('class','eye');
        obj.attr('data-active',0);

    }else{
        obj.prev("input[type='password']").attr('type','text');
        obj.attr('data-active',1);

        obj.attr('class','eye active');
    }

}
/*懒加载*/
function lazy_loading(url,elem,mb,ent_con) {
    if(!elem){
        elem = '.flow_load'
    }
    if(!ent_con){
        ent_con='没有更多了'
    }
    if(!mb){
        mb = 120
    }
    layui.use('flow', function() {
        var flow = layui.flow;
        console.log(flow);
        flow.load({
            elem: elem //流加载容器
//                , scrollElem: '.flow_load' //滚动条所在元素，一般不用填，此处只是演示需要。
            ,end:ent_con
            ,isLazyimg: true
            ,mb:mb
            , done: function (page, next) { //执行下一页的回调
                //模拟数据插入
                $.get(url,{p:page},function(data){
                    console.log(data);
                    var d = data.data;
                    var totalRows = d['count']['totalRows'];
                    var listRows = d['count']['listRows'];
                    var p_num = listRows*page;
                    next(d.content,p_num<totalRows);
                });
            }
        });
    })
}
