<template>
    <el-container>
        <el-main>
            <div class="agency_con">
                <div class="text-left" style="margin-bottom: 15px">
                    <el-button type="primary" size="small"  @click="handleEdit('')" icon="el-icon-plus" title="添加">添加</el-button>
                </div>
                <el-table :data="tableData"  style="width: 100%">
                    <el-table-column   type="expand">
                        <template slot-scope="props" >
                            <el-table style="width: 100%; " :data="props.row.detail_conf"     align="center">
                                <el-table-column style="width: 100%; background-color: red" label="类型" >
                                    <template  slot-scope="item">
                                        <span>{{ item.row.name }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="标识">
                                    <template  slot-scope="item">
                                        <span>{{ item.row.label }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column  label="参考范围" >
                                    <template  slot-scope="item">
                                        <span>{{item.row.desc }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="测值单位">
                                    <template slot-scope="item" >
                                        <span>{{ item.row.unit }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="操作" prop="op">
                                    <template  slot-scope="item">
                                        <el-button
                                                size="mini"
                                                @click="handleDetailEdit(item.row,'')">编辑</el-button>
                                        <el-button
                                                size="mini"
                                                type="danger"
                                                @click="handleDelete(item.row,2)">删除</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="ID"
                            prop="id">
                    </el-table-column>
                    <el-table-column
                            label="诊断名称">
                        <template slot-scope="props">
                            <span>{{ props.row.title }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="标识">
                        <template slot-scope="props">
                            <span>{{ props.row.label }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="描述"
                            prop="desc">
                    </el-table-column>
                    <el-table-column label="操作"
                                     prop="op">
                        <template slot-scope="props">
                            <el-button
                                    size="mini"
                                    @click="handleEdit(props.row)">编辑</el-button>
                            <el-button
                                    size="mini"
                                    type="primary"
                                    @click="handleDetailEdit('',props.row.id)">添加参数</el-button>
                            <el-button
                                    size="mini"
                                    type="danger"
                                    @click="handleDelete(props.row,1)">删除</el-button>
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

            <el-dialog
                    title=""
                    :visible.sync="detailDialogVisible"
                    width="30%">
                <span>
                    <el-form v-model="basic_detail_id"  label-width="80px" >
                          <el-form-item  label="类型">
                            <el-input placeholder="请输入类型"  v-model="sub_detail_data.name"></el-input>
                          </el-form-item>
                          <el-form-item label="原始标识">
                            <el-input placeholder="请输入原始标识"  :disabled="disabled2" v-model="sub_detail_data.label"></el-input>
                          </el-form-item>
                        <el-form-item label="字段名">
                            <el-input placeholder="请输入字段名" v-model="sub_detail_data.fieldname"></el-input>
                          </el-form-item>
                        <el-form-item label="数据标识">
                            <el-input placeholder="请输入数据标识" v-model="sub_detail_data.litefield"></el-input>
                          </el-form-item>
                          <el-form-item label="描述">
                            <el-input placeholder="请输入参考范围描述" v-model="sub_detail_data.desc"></el-input>
                          </el-form-item>
                         <el-form-item label="运算符">
                            <el-select style="width: 100%"   placeholder="请选择运算符" v-model="sub_detail_data.operator">
                                <el-option label="小于" :value="1"></el-option>
                                <el-option label="区间" :value="2"></el-option>
                                <el-option label="大于" :value="3"></el-option>
                                <el-option label="大于&小于" :value="4"></el-option>
                                <el-option label="小于&大于" :value="5"></el-option>
                            </el-select>
                        </el-form-item>
                          <el-form-item label="范围">
                            <el-input placeholder="运算符为区间类型用~隔开" v-model="sub_detail_data.ranges"></el-input>
                          </el-form-item>
                         <el-form-item label="测值单位">
                            <el-input placeholder="请输入测值单位" v-model="sub_detail_data.unit"></el-input>
                          </el-form-item>
                           <el-form-item label="科普说明">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入科普说明"
                                      v-model="sub_detail_data.popularization"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>
                        <el-form-item label="建议内容">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入建议内容"
                                      v-model="sub_detail_data.propose"
                                      show-word-limit
                                      rows="3"
                                      center
                              >
                                </el-input>
                          </el-form-item>
                    </el-form>
                </span>
                <span slot="footer" class="dialog-footer">
                    <el-button size="small" @click="detailDialogVisible = false">取 消</el-button>
                    <el-button size="small" type="primary" @click="onsubmitdetail()">确 定</el-button>
               </span>
            </el-dialog>
            <el-dialog
                    title=""
                    :visible.sync="confDialogVisible"
                    width="30%">
                <span>
                    <el-form v-model="basic_id" label-width="80px" >
                          <el-form-item  label="诊断名称">
                            <el-input  v-model="sub_data.title"></el-input>
                          </el-form-item>
                          <el-form-item label="原始标识">
                            <el-input :disabled="disabled1" v-model="sub_data.label"></el-input>
                          </el-form-item>

                           <el-form-item label="描述">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入描述"
                                      v-model="sub_data.desc"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>

                    </el-form>
                </span>
                <span slot="footer" class="dialog-footer">
    <el-button @click="confDialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="onsubmit">确 定</el-button>
  </span>
            </el-dialog>

        </el-main>
    </el-container>
</template>

<script>
    import {ajax_get, ajax_post} from "../../assets/js/ajax";

    export default {
        name: "basic_conf",
        data(){
            return {
                tableData: [],
                tableDetailData: [],
                sub_data:{
                    title:"",
                    label:"",
                    desc:"",
                },
                sub_detail_data:{
                    name:"",
                    label:"",
                    fieldname:"",
                    ranges:"",
                    unit:"",
                    litefield:"",
                    propose:"",
                    popularization:"",
                    desc:"",
                    operator:1,
                },
                default_data:{},
                default_detail_data:{},

                detailDialogVisible:false,
                confDialogVisible:false,
                basic_id:'',
                basic_detail_id:'',
                total:0,
                pageSize:10,
                loading:false,
                disabled1:false,
                disabled2:false
            }
        },
        created() {
            this.default_data = this.sub_data;
            this.default_detail_data = this.sub_detail_data;
            this.getList(this,1);
        },
        methods:{
            handleChange(p) {
                this.getList(this,p);
            },
            expandChange(row){
                this.tableDetailData = row.detail_conf?row.detail_conf:[]
            },
            handleEdit(item){
                console.log(item);
                if(item){
                    this.sub_data = item
                    this.basic_id = item.id
                    this.disabled1 = true
                }else{
                    this.sub_data = this.default_data
                    this.basic_id = ''
                    this.disabled1 = false
                }
                this.confDialogVisible = true
            },
            handleDetailEdit(item,basic_id){
                console.log(item);
                console.log(basic_id);
                this.basic_id = basic_id
                if(item){
                    if(item.operator){
                        item.operator = parseInt(item.operator);
                    }
                    this.sub_detail_data = item
                    this.basic_detail_id = item.id
                    this.disabled2= true
                }else{
                    this.sub_detail_data = this.default_detail_data
                    this.basic_detail_id = ''
                    this.disabled2 = false

                }
                this.detailDialogVisible = true
            },
            handleDelete(item,type){
                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/basic_conf/del",
                    'data':{
                        'id':item.id,
                        'type':type
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        this.$message.success(res.message)
                        this.centerDialogVisible = false
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
            getList(that,p){
                let x_token = this.$cookies.get('X-Token');
                that.loading = true;
                ajax_get({
                    'url':"/admin/basic_conf/index",
                    'data':{
                        p:p,
                        page_num:that.pageSize
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    that.loading = false;
                    if(res.code == 0){
                        if(res.data.total >0){
                            that.total = parseInt(res.data.total)
                            that.tableData = res.data.list
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
                const data = {
                    'id':this.basic_id,
                    'title':this.sub_data.title,
                    'label':this.sub_data.label,
                    'desc':this.sub_data.desc,
                }
                console.log(data)
                ajax_post({
                    'url':"/admin/basic_conf/edit",
                    'data':data,
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        this.$message.success(res.message)
                        this.centerDialogVisible = false
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
            onsubmitdetail(){

                let x_token = this.$cookies.get('X-Token');
                const data = {
                    'id':this.basic_detail_id,
                    'basic_id':this.basic_id,
                    'name':this.sub_detail_data.name,
                    'label':this.sub_detail_data.label,
                    'fieldname':this.sub_detail_data.fieldname,
                    'ranges':this.sub_detail_data.ranges,
                    'desc':this.sub_detail_data.desc,
                    'operator':this.sub_detail_data.operator,
                    'unit':this.sub_detail_data.unit,
                    'litefield':this.sub_detail_data.litefield,
                    'propose':this.sub_detail_data.propose,
                    'popularization':this.sub_detail_data.popularization,
                }
                console.log(data)
                ajax_post({
                    'url':"/admin/basic_conf/edit_detail",
                    'data':data,
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        this.$message.success(res.message)
                        this.centerDialogVisible = false
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
            }

        }
    }

</script>

<style scoped>
    .el-main {
        color: #333;
    }
    .agency_con{
        width: 96%;
        margin: 1% auto 0;
    }
    .demo-table-expand {
        font-size: 0;
    }
    .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
    }
</style>