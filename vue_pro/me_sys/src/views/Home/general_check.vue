<template>
    <div>
        <el-container >
            <el-main>
                <div class="yhxx"> <!--用户信息-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>用户信息</span>
                        </div>
                        <div class="yhxx_left float_l">
                            <el-row :gutter="20" >
                                <el-col :span="5" :offset="3" >
                                    <div class="input_line">
                                        <label for="">检查序号</label>
                                        <el-input v-model="checkList.check_number"  style="width: 200px"  disabled placeholder="检查序号"></el-input>
                                    </div>
                                </el-col>
                                <el-col :span="5" :offset="3"><div>
                                    <div class="input_line">
                                        <label for="">姓名</label>
                                        <el-input  v-model="checkList.real_name" style="width: 200px"  disabled placeholder="姓名"></el-input>
                                    </div>
                                </div></el-col>
                            </el-row>

                            <el-row :gutter="20">
                                <el-col :span="5" :offset="3">
                                    <div class="input_line">
                                        <label for="">身份证</label>
                                        <el-input   v-model="checkList.card_id" style="width: 200px"  disabled placeholder="身份证"></el-input>
                                    </div>
                                </el-col>
                                <el-col :span="5" :offset="3">
                                    <div class="input_line">
                                        <label for="">年龄</label>
                                        <el-input  v-model="checkList.age" style="width: 200px"  disabled placeholder="年龄"></el-input>
                                    </div>
                                </el-col>
                            </el-row>
                        </div>
                    </el-card>
                </div>
                <div class="jbxx" style="margin-top: 10px"><!--基本信息-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>基本信息</span>
                        </div>
                        <el-form :inline="true"  class="demo-form-inline">
                            <div class="jbxx_div">
                                <el-form-item label="身高" >
                                    <el-input style="width: 200px;" @input="inputblur()" v-model="sub_data.sg"  placeholder="身高">
                                        <template  slot="append">M</template>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="体重">
                                    <el-input style="width: 200px;" @input="inputblur()" v-model="sub_data.tz" placeholder="体重">
                                        <template slot="append">KG</template>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="BMI">
                                    <el-input style="width: 200px;"  v-model="sub_data.bmi"  placeholder="BMI">
                                    </el-input>
                                </el-form-item>
                            </div>
                            <div  class="jbxx_div">
                                <el-form-item  label="左侧血压">
                                    <el-col :span="6">
                                        <el-input v-model="sub_data.zcxyss"  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="8">
                                        <el-input v-model="sub_data.zcxysz"  placeholder="">
                                            <template slot="append">mmHg</template>
                                        </el-input>
                                    </el-col>
                                </el-form-item>
                                <el-form-item label="右侧血压">
                                    <el-col :span="6">
                                        <el-input v-model="sub_data.ycxyss"  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="8">
                                        <el-input v-model="sub_data.ycxysz" placeholder="">
                                            <template slot="append">mmHg</template>
                                        </el-input>
                                    </el-col>
                                </el-form-item>
                            </div>
                            <div  class="jbxx_div">
                                <el-form-item label="视力">
                                    <el-col :span="6">
                                        <el-input v-model="sub_data.g_zysl"  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="6">
                                        <el-input v-model="sub_data.g_yysl"  placeholder=""></el-input>
                                    </el-col>
                                </el-form-item>
                                <el-form-item label="矫正视力">
                                    <el-col :span="6">
                                        <el-input v-model="sub_data.g_zyjz"  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="6">
                                        <el-input v-model="sub_data.g_yyjz" placeholder=""></el-input>
                                    </el-col>
                                </el-form-item>
                            </div>
                            <div class="jbxx_div">
                                <el-form-item label="脉率" >
                                    <el-input v-model="sub_data.ml"  placeholder="脉率">
                                    </el-input>
                                </el-form-item>

                                <el-form-item label="腰围">
                                    <el-input v-model="sub_data.G_YW"  placeholder="腰围">
                                    </el-input>
                                </el-form-item>
                            </div>
                            <div class="jbxx_div">
                                <el-form-item >
                                    <el-button type="primary" @click="onsubmit()" size="medium" >保存</el-button>
                                </el-form-item>
                                <el-form-item >
                                    <el-button type="primary" @click="reset()" size="medium" >重置</el-button>
                                </el-form-item>
                            </div>
                        </el-form>
                    </el-card>
                </div>
            </el-main>
            <el-aside style="width: 38%;">
                <div class="el-aside_con">
                    <div class="jstj">
                        <el-form :inline="true" v-model="check_user_id" :model="formInline"  class="demo-form-inline">
                            <div class="jstj_search">
                                <el-form-item label="检查序号">
                                    <el-input style="width: 150px"   v-model="formInline.check_number" formInline.check_number  placeholder="检查序号"></el-input>
                                </el-form-item>
                                <el-form-item label="身份证">
                                    <el-input style="width: 180px"  v-model="formInline.card_id"  placeholder="身份证"></el-input>
                                </el-form-item>
                                <el-form-item label="姓名">
                                    <el-input style="width: 150px"   v-model="formInline.real_name" placeholder="姓名"></el-input>
                                </el-form-item>
                            </div>
                            <div class="jstj_search">
                                <el-form-item label="状态">
                                    <el-select style="width: 120px"   placeholder="请选择状态" v-model="formInline.is_com">
                                        <el-option label="全部" value=""></el-option>
                                        <el-option label="未检查" :value="0"></el-option>
                                        <el-option label="已检查" :value="1"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="性别">
                                    <el-select style="width: 120px"   placeholder="请选择性别" v-model="formInline.sex">
                                        <el-option label="全部" value=""></el-option>
                                        <el-option label="男" :value="1"></el-option>
                                        <el-option label="女" :value="2"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="检查日期" >
                                    <el-date-picker
                                            style="width: 250px"
                                            v-model="formInline.date"
                                            type="daterange"
                                            value-format="yyyy-MM-dd"
                                            range-separator="至"
                                            start-placeholder="开始日期"
                                            end-placeholder="结束日期">
                                    </el-date-picker>
                                </el-form-item>
                            </div>
                            <div class="jstj_search">

                            </div>
                            <el-form-item class="">
                                <el-button type="primary" size="small" @click="onSearch()"  icon="el-icon-search">查询</el-button>
                            </el-form-item>
                        </el-form>
                        <div v-loading="loading">
                            <div class="text-right" style="margin-bottom: 10px">
                                <el-button type="primary" size="small" icon="el-icon-printer" title="打印">打印</el-button>
                                <el-button type="primary" size="small" icon="el-icon-download" title="导出">导出</el-button>
                            </div>
                            <el-table
                                    :data="tableData"
                                    @row-click="RowClick"
                                    border
                                    highlight-current-row
                                    style="width: 100%">
                                <el-table-column
                                        prop="check_number"
                                        label="检查号"
                                        width="180">
                                    <template slot-scope="scope">
                                        <span style="margin-left: 10px">{{ scope.row.check_number }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                        prop="real_name"
                                        label="姓名"
                                        width="100">
                                </el-table-column>
                                <el-table-column
                                        prop="create_time"
                                        label="检查日期"
                                        width="200">
                                </el-table-column>
                                <el-table-column
                                        prop="is_com"
                                        label="状态"
                                       >
                                    <template slot-scope="scope">
                                        <el-button size="mini" type="primary" v-if="scope.row.is_com == 1">已检查</el-button>
                                        <el-button size="mini" type="info" v-else>未检查</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-pagination
                                    style="margin-top: 20px;text-align: center"
                                    background
                                    layout="prev, pager, next"
                                    :total="total"
                                    :page-size="pageSize"
                                    @current-change="handleChange"
                            >
                            </el-pagination>
                        </div>
                    </div>
                </div>
            </el-aside>
        </el-container>
    </div>
</template>
<style>
    .el-table .success-row {
        background: #f0f9eb;
    }
</style>
<script>
    import {ajax_post} from "../../assets/js/ajax";

    export default {
        name: "general_check",
        data(){
            return {
                formInline: {
                    card_id: '',
                    check_number: '',
                    real_name: '',
                    is_com:"",
                    sex:"",
                    date:""
                },
                checkList:{
                    check_number:""  ,
                    real_name:""  ,
                    age:""  ,
                    card_id:""  ,
                },
                sub_data:{
                    sg:"",
                    tz:"",
                    bmi:"",
                    zcxyss:"",
                    zcxysz:"",
                    ycxyss:"",
                    ycxysz:"",
                    g_zysl:"",
                    g_yysl:"",
                    g_zyjz:"",
                    g_yyjz:"",
                    ml:"",
                    G_YW:"",
                },
                tableData:[],
                default_data:{},
                total:0,
                pageSize:10,
                loading:false,
                check_user_id:"",

            }
        },
        created() {
            this.default_data = this.sub_data;
            this.getList(this,1);
        },
        methods:{
            handleChange(p) {
                this.getList(this,p);
            },
            onSearch(){
                this.getList(this,1);
            },
            inputblur(){
                console.log(this.sub_data.sg);
                if(this.sub_data.sg && this.sub_data.tz){
                    this.sub_data.bmi = ((this.sub_data.tz)/Math.pow((this.sub_data.sg),2)).toFixed(1)
                }else{
                    this.sub_data.bmi = ''
                }
            },
            handleDelete(item){
                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/type_in/del",
                    'data':{
                        'id':item.id,
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        this.$message.success(res.message)
                        this.$router.go(0);
                    }else if(res.code == 100){
                        this.$router.push('/login');
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })
            },
            RowClick(raw ){
                this.check_user_id = raw.id
                this.checkList = {
                    check_number:raw.check_number  ,
                    real_name:raw.real_name ,
                    age:raw.age  ,
                    card_id:raw.card_id  ,
                }
                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/type_in/get_data_info",
                    'data':{
                        id:raw.id,
                        project:'comm'
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    this.loading = false;
                    if(res.code == 0){
                        this.sub_data = res.data
                    }else if(res.code == 100){
                        this.$router.push('/login');
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })


            },
            getList(that,p){
                let x_token = this.$cookies.get('X-Token');
                this.loading = true;
                let date = this.formInline.date;
                let date1 = ''
                let date2 = ''
                if(date){
                    date1 = date[0]
                    date2 = date[1]
                }
                ajax_post({
                    'url':"/admin/type_in/get_data",
                    'data':{
                        project:"comm",
                        p:p,
                        page_num:that.pageSize,
                        card_id:this.formInline.card_id,
                        check_number:this.formInline.check_number,
                        real_name:this.formInline.real_name,
                        check_status:this.formInline.is_com,
                        sex:this.formInline.sex,
                        date1:date1,
                        date2:date2,
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    this.loading = false;
                    if(res.code == 0){
                        if(res.data.total >0){
                            that.total = parseInt(res.data.total)
                            that.tableData = res.data.list
                        }else{
                            that.total = 0
                            that.tableData = []
                        }
                    }else if(res.code == 100){
                        this.$router.push('/login');
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })
            },
            onsubmit(){
                let x_token = this.$cookies.get('X-Token');
                if(!this.check_user_id){
                    this.$message.error('请选择病人')
                    return false
                }
                if(!this.sub_data.sg){
                    this.$message.error('请输入身高')
                    return false
                }
                if(!this.sub_data.tz){
                    this.$message.error('请输入体重')
                    return false
                }
                if(!this.sub_data.bmi){
                    this.$message.error('BMI不能为空')
                    return false
                }
                const data = {
                    'id':this.check_user_id,
                    'sg':this.sub_data.sg,
                    'tz':this.sub_data.tz,
                    'bmi':this.sub_data.bmi,
                    'zcxyss':this.sub_data.zcxyss,
                    'zcxysz':this.sub_data.zcxysz,

                    'ycxyss':this.sub_data.ycxyss,
                    'ycxysz':this.sub_data.ycxysz,

                    'g_zysl':this.sub_data.g_zysl,
                    'g_yysl':this.sub_data.g_yysl,

                    'g_zyjz':this.sub_data.g_zyjz,
                    'g_yyjz':this.sub_data.g_yyjz,

                    'ml':this.sub_data.ml,
                    'G_YW':this.sub_data.G_YW,
                }
                console.log(data);
                ajax_post({
                    'url':"/admin/type_in/com_edit",
                    'data':data,
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        this.$message.success(res.message)
                    }else if(res.code == 100){
                        this.$router.push('/login');
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })
            },
            reset(){
                console.log(1)
                this.sub_data = {
                    sg:"",
                    tz:"",
                    bmi:"",
                    zcxyss:"",
                    zcxysz:"",
                    ycxyss:"",
                    ycxysz:"",
                    g_zysl:"",
                    g_yysl:"",
                    g_zyjz:"",
                    g_yyjz:"",
                    ml:"",
                    G_YW:"",
                }
            }
        }
    }
</script>

<style scoped>
    .el-aside {
        /*background-color: #D3DCE6;*/
        color: #333;
        /*text-align: center;*/
        height: auto;
    }

    .el-main {
        /*background-color: #E9EEF3;*/
        color: #333;
        text-align: center;
        height: auto;
        /*line-height: 160px;*/
    }
    .yhxx .yhxx_left{
        width: 75%;
    }
    .yhxx .yhxx_rigth{
        width: 25%;
    }
    .input_line{
        width: 300px;
    }
    .input_line label{
        width: 90px;
        display: inline-block;
        text-align: right;
        margin-right: 10px;
    }
    .jbxx .jbxx_div{
        width: 100%;
        text-align: left;
        margin-left: 15%;
    }
    .xdt .demo-image__preview{
        height: 600px;
        overflow-y: auto;
    }
    .el-aside .el-aside_con{
        width: 36%;
        position: fixed;
        right: 1%;
        top:10%;
        z-index: 100;
        /*margin-left: 5%;*/
        /*margin-top: 10%*/
    }
    .jstj{
        width: 100%;
    }
    .el-row {
        margin-bottom: 20px;
    &:last-child {
         margin-bottom: 0;
     }
    }
    .el-col {
        border-radius: 4px;
    }
    .bg-purple-dark {
        background: #99a9bf;
    }
    .bg-purple {
        background: #d3dce6;
    }
    .bg-purple-light {
        background: #e5e9f2;
    }
    .grid-content {
        border-radius: 4px;
        min-height: 36px;
    }
    .row-bg {
        padding: 10px 0;
        background-color: #f9fafc;
    }
    .yhxx .yhxx_item{
        /*line-height: initial;*/
    }
    .yhxx .yhxx_item label{
        margin-right: 10px;
    }
    .yhxx .yhxx_item  .el-input {
        margin-right: 20px;
    }

</style>