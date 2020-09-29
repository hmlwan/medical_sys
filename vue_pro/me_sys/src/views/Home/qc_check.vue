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

                <div class="jbxx" style="margin-top: 10px"><!--检验-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>检验</span>
                        </div>
                        <el-form style="width: 1000px" :model="sub_data" ref="sub_data" :inline="true"  class="demo-dynamic">
                            <div class="jbxx_div qc_xcg" >
                                <el-button type="primary" @click="addParts(2)"  style="margin-bottom: 10px" size="small" icon="el-icon-plus" title="添加血常规">添加血常规</el-button>
                                <div>
                                    <el-form-item style="margin-bottom: 10px; width: 45%"
                                                  v-for="(xcg_part_id, index) in sub_data.xcg_part_ids"
                                                  :label="xcg_part_id.name"
                                                  :key="xcg_part_id.key"
                                                  :prop="'xcg_part_ids.' + index + '.value'"
                                                  :rules="{ required: true, message: '不能为空', trigger: 'blur'}" >
                                        <el-input v-bind="clearInput" v-model="xcg_part_id.value"  style="width: 70%;margin-right: 10px;"></el-input><el-button @click.prevent="remove(xcg_part_id,2)">删除</el-button>
                                    </el-form-item>
                                </div>
                            </div>
                            <div class="jbxx_div qc_ncg" style="margin-top: 20px">
                                <el-button type="primary" @click="addParts(3)" style="margin-bottom: 10px" size="small" icon="el-icon-plus" title="添加尿常规">添加尿常规</el-button>
                                <div >
                                    <el-form-item style="margin-bottom: 10px; width: 45%"
                                                  v-for="(ncg_part_id, index) in sub_data.ncg_part_ids"
                                                  :label="ncg_part_id.name"
                                                  :key="ncg_part_id.key"
                                                  :prop="'ncg_part_ids.' + index + '.value'"
                                                  :rules="{ required: true, message: '不能为空', trigger: 'blur'}" >
                                        <el-input v-bind="clearInput"  v-model="ncg_part_id.value" style="width: 70%;margin-right: 10px;"></el-input><el-button @click.prevent="remove(ncg_part_id,3)" >删除</el-button>
                                    </el-form-item>
                                </div>
                            </div>
                            <div class="jbxx_div qc_sh" style="margin-top: 20px">
                                <el-button type="primary" @click="addParts(4)" style="margin-bottom: 10px"  size="small" icon="el-icon-plus" title="添加生化">添加生化</el-button>
                                <div>
                                    <el-form-item style="margin-bottom: 10px; width: 45%"
                                                  v-for="(sh_part_id, index) in sub_data.sh_part_ids"
                                                  :label="sh_part_id.name"
                                                  :key="sh_part_id.key"
                                                  :prop="'sh_part_ids.' + index + '.value'"
                                                  :rules="{ required: true, message: '不能为空', trigger: 'blur'}" >
                                    <el-input v-bind="clearInput"    v-model="sh_part_id.value" style="width: 70%;margin-right: 10px;"></el-input><el-button @click.prevent="remove(sh_part_id,4)">删除</el-button>
                                    </el-form-item>
                                </div>
                            </div>
                            <el-form-item style="margin-top: 20px">
                                <el-button type="primary" @click="onsubmit('sub_data')">提交</el-button>
                            </el-form-item>
                        </el-form>

                    </el-card>
                </div>
                <el-dialog
                        title=""
                        :visible.sync="centerDialogVisible"
                        width="50%"
                        >
                <span>
                    <el-form  v-model="part_id" label-width="80px" style="text-align: left;">
                        <el-form-item :label="qc_label">
                            <el-checkbox-group v-model="sel_part_ids">
                              <el-checkbox v-for="item in measur_list"
                                           :key="item.id"
                                           :label="item.id  +'_'+item.name" >
                                    {{item.name}}
                              </el-checkbox>
                            </el-checkbox-group>
                      </el-form-item>
                    </el-form>
                </span>
                    <span slot="footer" class="dialog-footer">
                        <el-button @click="centerDialogVisible = false" size="small">取 消</el-button>
                        <el-button type="primary" @click="onSelsubmit" size="small">确 定</el-button>
                    </span>
                </el-dialog>
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
                                    <el-select style="width: 120px"   placeholder="请选择状态" v-model="formInline.is_qc">
                                        <el-option label="全部" value=""></el-option>
                                        <el-option label="未检验" :value="0"></el-option>
                                        <el-option label="已检验" :value="1"></el-option>
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
                                        <el-button size="mini" type="primary" v-if="scope.row.is_qc == 1">已检验</el-button>
                                        <el-button size="mini" type="info" v-else>未检验</el-button>
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
        name: "qc_check",
        data(){
            return {
                formInline: {
                    card_id: '',
                    check_number: '',
                    real_name: '',
                    is_qc:"",
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
                    xcg_part_ids:() => [],
                    ncg_part_ids:() => [],
                    sh_part_ids:() => [],
                    'id':""
                },
                tableData:[],
                default_data:{},
                total:0,
                pageSize:10,
                loading:false,
                check_user_id:"",
                centerDialogVisible:false,
                part_id:'',
                type:'',
                qc_label:'',
                measur_list:'',
                sel_part_ids:[],
                clearInput:""
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
                        project:'qc'
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    this.loading = false;
                    if(res.code == 0){

                        if((res.data).length == 0){
                            if((this.sub_data.xcg_part_ids).length != 0){
                                for (let i  in  this.sub_data.xcg_part_ids){

                                    (this.sub_data.xcg_part_ids)[i]['value'] = ''
                                }
                            }
                            if((this.sub_data.ncg_part_ids).length != 0){
                                for (let j  in  this.sub_data.ncg_part_ids){
                                    this.sub_data.ncg_part_ids[j]['value'] = ''
                                }
                            }
                            if((this.sub_data.sh_part_ids).length != 0){
                                for (let k  in  this.sub_data.sh_part_ids){
                                    this.sub_data.sh_part_ids[k]['value'] = ''
                                }
                            }
                        }else{
                            this.sub_data = {
                                xcg_part_ids:res.data.xcg_data?JSON.parse(res.data.xcg_data):[],
                                ncg_part_ids:res.data.ncg_data?JSON.parse(res.data.ncg_data):[],
                                sh_part_ids:res.data.sh_data?JSON.parse(res.data.sh_data):[],
                            }
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
                        project:"qc",
                        p:p,
                        page_num:that.pageSize,
                        card_id:this.formInline.card_id,
                        check_number:this.formInline.check_number,
                        real_name:this.formInline.real_name,
                        check_status:this.formInline.is_qc,
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
            onsubmit(formName){
                if(!this.check_user_id){
                    this.$message.error('请选择病人')
                    return false
                }
                console.log(this.sub_data);
                let x_token = this.$cookies.get('X-Token');
                this.$refs[formName].validate((valid) => {
                    if (!valid) {
                        console.log('error submit!!');
                        return false;
                    }else{
                        if((this.sub_data.xcg_part_ids).length == 0
                            && (this.sub_data.ncg_part_ids).length == 0
                            && (this.sub_data.sh_part_ids).length == 0){
                            this.$message.error('请至少选择一项')
                            return false
                        }
                        this.sub_data.id = this.check_user_id
                        console.log(this.sub_data);
                        ajax_post({
                            'url':"/admin/type_in/qc_edit",
                            'data':this.sub_data,
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
                    }
                });

            },
            addParts(part_id){
                this.part_id = part_id;
                this.sel_part_ids = [];

                if(part_id == 2){
                    this.qc_label = "血常规："
                }else if(part_id == 3){
                    this.qc_label = "尿常规："
                }else{
                    this.qc_label = "生化："
                }
                this.getMeasur(part_id)
            },
            getMeasur(part_id){
                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/measur/get_measur",
                    'data':{
                        part_id:part_id,
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    this.loading = false;
                    if(res.code == 0){
                        if((res.data).length>0){
                            this.measur_list = res.data
                            this.centerDialogVisible = true

                        }else{
                            this.$message.error("无数据")
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
            onSelsubmit(){
                console.log(this.sel_part_ids);
                if((this.sel_part_ids).length == 0){
                    this.$message.error('请至少选择一项')
                    return false
                }
                let sel_part_ids = this.sel_part_ids
                if(this.part_id == 2){
                    this.sub_data.xcg_part_ids = []
                }else if(this.part_id == 3){
                    this.sub_data.ncg_part_ids = []
                }else{
                    this.sub_data.sh_part_ids = []
                }
                for (let  i in sel_part_ids){
                    let sel_val = sel_part_ids[i]
                    console.log(sel_val);
                    let sel_arr = sel_val.split('_');
                    if(this.part_id == 2){ //"血常规："
                        this.sub_data.xcg_part_ids.push({
                            name:sel_arr[1],
                            value:'',
                            id:sel_arr[0],
                            part_id:this.part_id
                        })
                    }else if(this.part_id == 3){ //尿常规
                        this.sub_data.ncg_part_ids.push({
                            name:sel_arr[1],
                            value:'',
                            id:sel_arr[0],
                            part_id:this.part_id
                        })
                    }else{ //生化
                        this.sub_data.sh_part_ids.push({
                            name:sel_arr[1],
                            value:'',
                            id:sel_arr[0],
                            part_id:this.part_id
                        })
                    }
                }

                this.centerDialogVisible = false
            },
            remove(item,part_id){
                let parts_ids = ''
                if(part_id == 2){
                     parts_ids = this.sub_data.xcg_part_ids;
                }else if(part_id == 3){
                     parts_ids = this.sub_data.ncg_part_ids;
                }else{
                     parts_ids = this.sub_data.sh_part_ids;
                }
                const index = parts_ids.indexOf(item)
                if(index !== -1){
                    parts_ids.splice(index, 1)
                }
            },
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