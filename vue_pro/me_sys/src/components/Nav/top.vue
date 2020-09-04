<template>
    <div>
        <el-menu
                :default-active="activeIndex2"
                class="el-menu-demo"
                mode="horizontal"
                @select="handleSelect"
                :router="true"
                background-color="#545c64"
                text-color="#fff"
                active-text-color="#ffd04b">
            <span class="sys_logo"><img style="height: 30px;vertical-align: middle" src="../../assets/img/sys_logo.png" alt=""></span >
            <el-menu-item index="1" :route="{name:'type_in'}">检索病人</el-menu-item>
            <el-menu-item index="2" :route="{name:'user'}" >用户管理</el-menu-item>
            <el-menu-item index="3" :route="{name:'agency'}" >机构管理</el-menu-item>
            <el-menu-item index="4" :route="{name:'temp'}">超声模板</el-menu-item>
            <el-menu-item index="5" :route="{name:'temp1'}">影像模板</el-menu-item>
            <el-menu-item index="6" :route="{name:'measure'}">测体管理</el-menu-item>
            <el-submenu index="7"  >
                <template slot="title">基本设置</template>
                <el-menu-item index="7-1">基本内容</el-menu-item>
                <el-menu-item index="7-2" :route="{name:'basic_conf'}">基本信息参数</el-menu-item>
                <el-menu-item index="7-3" :route="{name:'eeg_conf'}">心电图参数</el-menu-item>
            </el-submenu>
            <el-submenu index="8"  class="el-menu-demo_five">
                <template slot="title">admin</template>
                <el-menu-item index="8-1">个人资料</el-menu-item>
                <el-menu-item index="8-2">修改密码</el-menu-item>
                <el-menu-item index="8-3"  @click="outlogin()">退出</el-menu-item>
            </el-submenu>
        </el-menu>
        <div class="line"></div>
    </div>
</template>

<script>
    import {ajax_post} from "../../assets/js/ajax";

    export default {
        name: "top",
        data() {
            return {
                activeIndex: '1',
                activeIndex2: '1'
            };
        },
        created() {

        },
        methods: {
            outlogin(){

                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':'/admin/login/logout',
                    'headers':{
                        'X-Token':  x_token
                    },
                }).then(res=>{
                    console.log(res);
                    this.$cookies.remove('X-Token')
                    this.$cookies.remove('USERINFO')
                    this.$router.push('/')

                }).catch((err)=>{
                    console.log(err);

                    // this.$message.error('请求错误')
                    return false
                })
            },
            handleSelect(key, keyPath) {
                console.log(key, keyPath);

            }
        }
    }
</script>

<style scoped>
    .el-menu{
        position: fixed;
        top: 0;
        width: 100%;
        left: 0;
        z-index: 100;
    }
    .line{
        height: 60px;
        clear: both;
    }
    .el-menu-demo_five{
        float: right;
    }
    .sys_logo{
        margin-left: 10%;
        float: left;
        height: 60px;
        line-height: 60px;
        margin-right: 5px;
    }

</style>