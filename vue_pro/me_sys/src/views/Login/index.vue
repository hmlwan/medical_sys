<template>
    <div id="login">

        <el-container>
            <div class="login_con">
                <div class="login_item">
                    <el-header >
                        <h3>登录后台系统</h3>
                    </el-header>
                    <div class="login_form">
                        <el-main>
                            <el-form :model="ruleForm"   status-icon  ref="ruleForm" label-width="100px" class="demo-ruleForm">
                                <div >
                                    <el-input   v-model="ruleForm.username" placeholder="账号" autocomplete="off"></el-input>
                                </div>
                                <div style="margin-top: 15px;" >
                                    <el-input type="password" v-model="ruleForm.pass" placeholder="密码" autocomplete="off"></el-input>
                                </div>
                                <div style="margin-top: 15px;  float: right;">
                                    <el-button type="primary" size="medium" @click="submitForm()">登录</el-button>
                                </div>
                            </el-form>
                        </el-main>
                    </div>
                </div>
                <p class="text-center">©2017 渝ICP备16002511号-1</p>
            </div>
        </el-container>
    </div>
</template>

<script>
    import {ajax_post} from '../../assets/js/ajax'
    export default {
        name: "index",
        data() {
            return {
                ruleForm: {
                    username:"",
                    pass: ""
                },
            };
        },
        created() {
            // this.$message('确认关闭？')
            this.ruleForm.username = ''
            this.ruleForm.pass = ''
        },
        methods: {
            submitForm() {
                console.log(this.ruleForm);
                const pass = this.ruleForm.pass;
                const username = this.ruleForm.username;
                if(!username){
                    this.$message.error('请输入用户名');
                    return;
                }
                if(!pass){
                    this.$message.error('请输入密码');
                    return;
                }
                ajax_post({
                    'url':'/admin/login/login',
                    'data':{
                        'password' :pass,
                        'username' :username
                    },
                    'headers':{
                        'X-Token':  ""
                    },
                }).then(res=>{
                    console.log(res);
                    if(res.code == 0){
                        let data = res.data;
                        let x_token = data['x_token'];
                        let userinfo = data.userInfo;
                        let userinfo1 = JSON.stringify(userinfo)
                        this.$message.success("登录成功")
                        this.$cookies.set('X-Token',x_token,2*60*60);
                        this.$cookies.set('USERINFO',userinfo1,2*60*60);
                        if(userinfo.auth_type === -1 || userinfo.auth_type == 1){ //管理员
                            this.$router.replace('/home/user')
                        }else if(userinfo.auth_type == 2){  //录入
                            this.$router.replace('/home/type_in')
                        }else if(userinfo.auth_type == 3){ //一般检查
                            this.$router.replace('/home/general_check')
                        }else if(userinfo.auth_type == 4){ //心电图
                            this.$router.replace('/home/ecg_check')
                        }else if(userinfo.auth_type == 5){ //超声
                            this.$router.replace('/home/us_check')
                        }else if(userinfo.auth_type == 6){ //影像
                            this.$router.replace('/home/rt_check')
                        }else if(userinfo.auth_type == 7){ //检验
                            this.$router.replace('/home/qc_check')
                        }else if(userinfo.auth_type == 10){ //总检
                            this.$router.replace('/home/check')
                        }
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })

            },
        }
    }
</script>

<style scoped>
    #login{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        overflow-y: auto;
        background-color: #ededed;
        border-color: #409EFF;
    }
    .login_con{
        width: 16%;
        height: auto;
        text-align: center;
        margin: 10% auto 0;
    }
    .login_con .text-center{
        color: #a7abb4;
        margin-top: 10px;
    }
    .login_item{
        border: 1px solid #409EFF;
        background-color: #fff;
    }
    .login_item .el-header{
        background-color: #409EFF;
        border-color: #409EFF;

    }
    .login_item .el-header h3{
        color: #fff;
        font-size: 20px;
        line-height: 2.6;
        font-weight: normal;
        padding: 5px 0;

    }
    .login_form{

    }
</style>
