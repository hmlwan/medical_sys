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
                            <el-form :model="ruleForm" status-icon  ref="ruleForm" label-width="100px" class="demo-ruleForm">
                                <div >
                                    <el-input   v-model="ruleForm.username" placeholder="账号" autocomplete="off"></el-input>
                                </div>
                                <div style="margin-top: 15px;" >
                                    <el-input type="password" v-model="ruleForm.pass" placeholder="密码" autocomplete="off"></el-input>
                                </div>
                                <div style="margin-top: 15px;  float: right;">
                                    <el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
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
                    pass: ''
                },

            };
        },
        created() {
            // this.$message('确认关闭？')
        },
        methods: {
            submitForm() {
                ajax_post({
                    'url':'login/test',
                    'data':{
                        'type' :'1'
                    }
                }).then(res=>{
                    console.log(res);
                    if(res.code == 0){
                        this.$alert('登录成功','提示~').then(res=>{
                            if(res == 'confirm'){
                                this.$router.replace('/home')
                            }
                        })
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })
                // this.$refs[formName].validate((valid) => {
                //     if (valid) {
                //         alert('submit!');
                //     } else {
                //         console.log('error submit!!');
                //         return false;
                //     }
                // });
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
