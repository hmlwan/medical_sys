<template>
    <el-container>
        <el-main>
            <div class="agency_con">
                <div class="text-left" style="margin-bottom: 15px">
                    <el-button type="primary" size="small" @click="centerDialogVisible=true" icon="el-icon-plus" title="添加">添加</el-button>
                </div>
                <el-table
                        :data="tableData"
                        border
                        style="width: 100%">
                    <el-table-column
                            prop="name"
                            label="测值名称"
                            width="180">
                    </el-table-column>

                    <el-table-column
                            prop="label"
                            label="标识"
                            width="180">

                    </el-table-column>
                    <el-table-column
                            prop="fieldname"
                            label="字段名">
                    </el-table-column>
                    <el-table-column
                            prop="ranges"
                            label="参考范围">
                    </el-table-column>
                    <el-table-column
                            prop="unit"
                            label="测值单位">
                    </el-table-column>
                    <el-table-column
                            prop="part_id"
                            label="项目">
                        <template slot-scope="scope">
                            <span style="margin-left: 10px" v-if="scope.row.part_id == 2">血常规</span>
                            <span style="margin-left: 10px" v-else-if="scope.row.part_id == 3">尿常规</span>
                            <span style="margin-left: 10px" v-else-if="scope.row.part_id == 4">生化</span>
                        </template>

                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button
                                    size="mini"
                                    @click="handleEdit( scope.row)" >编辑</el-button>
                            <el-button
                                    size="mini"
                                    type="danger"
                                    @click="handleDelete( scope.row)">删除</el-button>
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
                    :visible.sync="centerDialogVisible"
                    width="30%"
                    >
                <span>
                    <el-form  v-model="measur_id" label-width="80px" >
                          <el-form-item  label="测值名称">
                            <el-input  v-model="sub_data.name"></el-input>
                          </el-form-item>
                          <el-form-item label="原始标识">
                            <el-input v-model="sub_data.litefield"></el-input>
                          </el-form-item>
                        <el-form-item label="字段名">
                            <el-input v-model="sub_data.fieldname"></el-input>
                          </el-form-item>
                        <el-form-item label="数据标识">
                            <el-input v-model="sub_data.label"></el-input>
                          </el-form-item>
                          <el-form-item label="参考范围">
                            <el-input v-model="sub_data.ranges" placeholder="用 - 隔开"></el-input>
                          </el-form-item>
                         <el-form-item label="测值单位">
                            <el-input v-model="sub_data.unit"></el-input>
                          </el-form-item>
                          <el-form-item label="项目">
                              <el-select v-model="sub_data.part_id"  placeholder="请选择项目">
                                  <el-option label="血常规" :value="2"></el-option>
                                  <el-option label="尿常规" :value="3"></el-option>
                                  <el-option label="生化" :value="4"></el-option>
                              </el-select>
                          </el-form-item>
                           <el-form-item label="科普说明">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入科普说明"
                                      v-model="sub_data.popularization"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>
                        <el-form-item label="建议内容">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入建议内容"
                                      v-model="sub_data.propose"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>
                    </el-form>

                </span>
                <span slot="footer" class="dialog-footer">
    <el-button @click="centerDialogVisible = false" size="small">取 消</el-button>
    <el-button type="primary" @click="onsubmit" size="small">确 定</el-button>
  </span>
            </el-dialog>
        </el-main>
    </el-container>
</template>
<script>
    import {ajax_get, ajax_post} from "../../assets/js/ajax";

    export default {
        name: "measure",
        data(){
            return {
                tableData:[],
                sub_data:{
                    name:"",
                    label:"",
                    fieldname:"",
                    ranges:"",
                    unit:"",
                    part_id:2,
                    litefield:"",
                    propose:"",
                    popularization:"",
                },
                centerDialogVisible:false,
                default_data:{},
                measur_id:'',
                total:0,
                pageSize:10,
                loading:false,
                disabled:false
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
            handleEdit(item){
                console.log(item);
                if(item){
                    this.sub_data = item
                    this.measur_id = item.id
                    this.disabled = true
                }else{
                    this.sub_data = this.default_data
                    this.measur_id = ''
                    this.disabled = false
                }
                this.centerDialogVisible = true
            },
            handleDelete(item){
                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/measur/del",
                    'data':{
                        'id':item.id,
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
                    'url':"/admin/measur/index",
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
                ajax_post({
                    'url':"/admin/measur/edit",
                    'data':{
                        'id':this.measur_id,
                        'name':this.sub_data.name,
                        'label':this.sub_data.label,
                        'fieldname':this.sub_data.fieldname,
                        'ranges':this.sub_data.ranges,
                        'unit':this.sub_data.unit,
                        'part_id':this.sub_data.part_id,
                        'litefield':this.sub_data.litefield,
                        'propose':this.sub_data.propose,
                        'popularization':this.sub_data.popularization,
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
</style>