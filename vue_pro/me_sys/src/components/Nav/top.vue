<template>
    <div>
        <el-menu
                :default-active="activeIndex"
                class="el-menu-demo"
                mode="horizontal"
                @select="handleSelect"
                :router="true"
                background-color="#545c64"
                text-color="#fff"
                active-text-color="#ffd04b">
            <span class="sys_logo"><img style="height: 30px;vertical-align: middle" src="../../assets/img/sys_logo.png" alt=""></span >
            <el-menu-item index="1" :disabled="disabled1" :route="router">检索病人</el-menu-item>
            <el-menu-item index="2" :disabled="disabled2" :route="{name:'user'}" >用户管理</el-menu-item>
            <el-menu-item index="3" :disabled="disabled3"  :route="{name:'agency'}" >机构管理</el-menu-item>
            <el-menu-item index="4" :disabled="disabled4"  :route="{name:'temp'}">超声模板</el-menu-item>
            <el-menu-item index="5" :disabled="disabled5"  :route="{name:'portrait_temp'}">影像模板</el-menu-item>
            <el-menu-item index="6" :disabled="disabled6" :route="{name:'measure'}">测体管理</el-menu-item>
            <el-submenu index="7"  >
                <template slot="title">基本设置</template>
                <el-menu-item >基本内容</el-menu-item>
                <el-menu-item index="7_2" :disabled="disabled7_2"  :route="{name:'basic_conf'}">基本信息参数</el-menu-item>
                <el-menu-item index="7_3" :disabled="disabled7_3" :route="{name:'ecg_conf'}">心电图参数</el-menu-item>
            </el-submenu>

            <el-submenu index="8"  class="el-menu-demo_five">
                <template slot="title"> {{username}}</template>
<!--                <el-menu-item index="8_1">个人资料</el-menu-item>-->
                <el-menu-item  @click="editpass()">修改密码</el-menu-item>
                <el-menu-item index="8_3"  @click="outlogin()">退出</el-menu-item>
            </el-submenu>
            <el-menu-item style="float: right;"  v-model="is_open_oran"  >  <span style="margin-right:20px" @click="isOpenOran">{{organ_name}}</span > </el-menu-item>

        </el-menu>
        <div class="line"></div>
        <el-dialog
                title=""
                :visible.sync="centerDialogVisible"
                width="30%"
        >
                <span>
                    <el-form   label-width="80px" >
                          <el-form-item  label="当前密码">
                            <el-input type="password" v-model="sub_data.oldpass"></el-input>
                          </el-form-item>
                          <el-form-item label="新密码">
                            <el-input type="password" v-model="sub_data.newpass"></el-input>
                          </el-form-item>
                        <el-form-item label="确认密码">
                            <el-input type="password" v-model="sub_data.repass"></el-input>
                          </el-form-item>
                    </el-form>

                </span>
            <span slot="footer" class="dialog-footer">
    <el-button @click="centerDialogVisible = false" size="small">取 消</el-button>
    <el-button type="primary" @click="onsubmit" size="small">确 定</el-button>
  </span>
        </el-dialog>
    </div>
</template>

<script>
    import {ajax_post} from "../../assets/js/ajax";

    export default {
        name: "top",
        props:['organName'],
        data() {
            return {
                activeIndex: '1',
                disabled1:false,
                disabled2:false,
                disabled3:false,
                disabled4:false,
                disabled5:false,
                disabled6:false,
                disabled7_1:false,
                disabled7_2:false,
                disabled7_3:false,
                username:'',
                router:"",
                sub_data:{
                    oldpass:"",
                    newpass:'',
                    repass:''
                },
                centerDialogVisible:false,
                organ_name:"",
                is_open_oran:0
            };
        },

        watch:{
            organName:function (val) {
                console.log(val)
                this.organ_name = val
            }
        },
        created() {
            const ORGANINFO =  this.$cookies.get('ORGANINFO');
            if(ORGANINFO){
               this.organ_name =   ORGANINFO.name
            }
            let index_id = this.$route.meta.index_id;
             this.activeIndex = index_id
            let USERINFO = this.$cookies.get('USERINFO');

            this.username = USERINFO.manage_name
            if(USERINFO.auth_type == -1){ //超级管理员
                this.disabled1 = true
                this.disabled3 = true

            }else if(USERINFO.auth_type == 1){ //机构管理员
                this.disabled1 = true
                this.disabled6 = true
                this.disabled7_2 = true
            }else if(USERINFO.auth_type > 1){  //操作医生
                this.disabled2 = true
                this.disabled3 = true
                this.disabled4 = true
                this.disabled5 = true
                this.disabled6 = true
                this.disabled7_2 = true
                this.disabled7_3 = true
                if(USERINFO.auth_type == 2){ //录入
                    this.router = {name:'type_in'}
                }else if(USERINFO.auth_type == 3){ //一般检查
                    this.router = {name:'general_check'}
                }else if(USERINFO.auth_type == 4){ //心电图
                    this.router = {name:'ecg_check'}
                }else if(USERINFO.auth_type == 5){ //超声波
                    this.router = {name:'us_check'}
                }else if(USERINFO.auth_type == 6){ //放射
                    this.router = {name:'rt_check'}
                }else if(USERINFO.auth_type == 7){ //检验
                    this.router = {name:'qc_check'}
                }else if(USERINFO.auth_type == 10){ //总检
                    this.router = {name:'check'}
                }
            }
        },
        methods: {
            editpass(){
                 this.editpass = {}
                 this.centerDialogVisible = true
            },
            isOpenOran(){
                this.is_open_oran = this.is_open_oran +1
                this.$emit('is_open',this.is_open_oran)
            },
            onsubmit(){
                let x_token = this.$cookies.get('X-Token');
                if(!this.sub_data.oldpass){
                    this.$message.error('请输入旧密码')
                    return false
                }
                if(!this.sub_data.newpass){
                    this.$message.error('请输入新密码')
                    return false
                }
                if(this.sub_data.newpass != this.sub_data.repass ){
                    this.$message.error('密码不一致')
                    return false
                }
                ajax_post({
                    'url':'/admin/manage/edit_pass',
                    'data':{
                        oldpass:this.sub_data.oldpass,
                        newpass:this.sub_data.newpass,
                        repass:this.sub_data.repass,
                    },
                    'headers':{
                        'X-Token':  x_token
                    },
                }).then(res=>{
                    console.log(res);
                    if(res.code == 0){
                        this.$message.success('修改成功')
                        this.$cookies.remove('X-Token')
                        this.$cookies.remove('USERINFO')
                        this.$cookies.remove('ORGANINFO')
                        this.$router.push('/')
                    }else if(res.code == 100){
                        this.$router.push('/login');
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch((err)=>{
                    console.log(err);
                    // this.$message.error('请求错误')
                    return false
                })
            },
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
                    this.$cookies.remove('ORGANINFO')
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