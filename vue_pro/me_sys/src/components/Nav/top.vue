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
            <el-menu-item index="1" :route="{name:'type_in'}" style="margin-left: 10%">检索病人</el-menu-item>
            <el-menu-item index="2" :route="{name:'user'}" >用户管理</el-menu-item>
            <el-menu-item index="3" :route="{name:'agency'}" >机构管理</el-menu-item>
            <el-menu-item index="4"  >超声模板</el-menu-item>
            <el-menu-item index="5"  >影像模板</el-menu-item>
            <el-menu-item index="8"  >测体管理</el-menu-item>
            <el-submenu index="7"  >
                <template slot="title">基本设置</template>
                <el-menu-item index="7-1">基本内容</el-menu-item>
                <el-menu-item index="7-2">超声病例</el-menu-item>
                <el-menu-item index="7-3">影像病例</el-menu-item>
            </el-submenu>
            <el-submenu index="8"  class="el-menu-demo_five">
                <template slot="title">admin</template>
                <el-menu-item index="8-1">个人资料</el-menu-item>
                <el-menu-item index="8-2">修改密码</el-menu-item>
                <el-menu-item index="8-3">退出</el-menu-item>
            </el-submenu>
        </el-menu>
        <div class="line"></div>
    </div>


</template>

<script>
    export default {
        name: "top",
        data() {
            return {
                activeIndex: '1',
                activeIndex2: '1'
            };
        },
        methods: {

            handleSelect(key, keyPath) {
                console.log(key, keyPath);
                if(key == 1){
                    this.$router.replace('/home/type_in')
                }
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
</style>