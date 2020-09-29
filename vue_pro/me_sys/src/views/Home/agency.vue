<template>
    <el-container>
        <el-main>
         <div class="agency_con">
             <div class="text-left" style="margin-bottom: 15px">
                 <el-button type="primary" @click="handleEdit('')" icon="el-icon-plus" title="添加">添加</el-button>
             </div>
             <el-table
                     :data="tableData"
                     border
                     style="width: 100%">
                 <el-table-column
                         prop="name"
                         label="机构名称"
                         width="300">
                     <template slot-scope="scope">
                         <span style="margin-left: 10px">{{ scope.row.name }}</span>
                     </template>
                 </el-table-column>
                 <el-table-column
                         prop="abridge"
                         label="标识"
                         width="180">
                 </el-table-column>
                 <el-table-column
                         prop="address"
                         label="联系地址"
                         width="300">
                 </el-table-column>
                 <el-table-column
                         prop="mobile"
                         label="联系电话">
                 </el-table-column>
                 <el-table-column
                         prop="status"
                         label="状态">
                     <template slot-scope="scope">
                         <span style="margin-left: 10px">
                             <el-tag v-if="scope.row.status == 1">开启</el-tag>
                             <el-tag v-else type="info">关闭</el-tag>
                         </span>
                     </template>
                 </el-table-column>
                 <el-table-column label="操作">
                     <template slot-scope="scope">
                         <el-button
                                 size="mini"
                                 @click="handleEdit( scope.row)">编辑</el-button>
<!--                         <el-button-->
<!--                                 size="mini"-->
<!--                                 type="danger"-->
<!--                                 @click="handleDelete(scope.$index, scope.row)">删除</el-button>-->
                     </template>
                 </el-table-column>
             </el-table>
         </div>
            <el-pagination
                    style="margin-top: 20px;text-align: center"
                    background
                    layout="prev, pager, next"
                    :total="total"
                    :page-size="pageSize"
                    @current-change="handleChange"
            >
            </el-pagination>
            <el-dialog
                    title=""
                    :visible.sync="centerDialogVisible"
                    width="30%"
                        >
                <span>
                    <el-form  v-model="organ_id" label-width="80px" >
                          <el-form-item  label="机构名称">
                            <el-input placeholder="输入机构名称" v-model="sub_data.name" ></el-input>
                          </el-form-item>
                          <el-form-item label="标识">
                            <el-input placeholder="唯一三位纯数字" :disabled="disabled" v-model="sub_data.abridge" ></el-input>
                          </el-form-item>
                        <el-form-item label="联系地址">
                            <el-input placeholder="输入联系地址" v-model="sub_data.address" ></el-input>
                          </el-form-item>
                        <el-form-item label="联系电话">
                            <el-input placeholder="输入联系电话" v-model="sub_data.mobile" ></el-input>
                          </el-form-item>
                          <el-form-item label="状态">
                              <el-select v-model="sub_data.status"  placeholder="请选择状态">
                                  <el-option label="启用" v-bind:value="1"></el-option>
                                  <el-option label="禁用" v-bind:value="0"></el-option>
                              </el-select>
                          </el-form-item>
                    </el-form>

                </span>
                <span slot="footer" class="dialog-footer">
    <el-button @click="centerDialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="onsubmit">确 定</el-button>
  </span>
            </el-dialog>
        </el-main>
    </el-container>
</template>

<script>
    import {ajax_get,ajax_post} from "../../assets/js/ajax";

    export default {
        name: "agency",
        data(){
            return {
                tableData:[],
                sub_data:{
                    name:"",
                    abridge:"",
                    address:"",
                    mobile:"",
                    status:1,
                },
                default_data:{},
                organ_id:'',
                total:0,
                pageSize:10,
                loading:false,
                centerDialogVisible:false,
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
                    this.organ_id = item.id
                    this.disabled = true
                }else{
                    this.sub_data = this.default_data
                    this.organ_id = ''
                    this.disabled = false
                }
                this.centerDialogVisible = true
            },

            getList(that,p){
                let x_token = this.$cookies.get('X-Token');
                that.loading = true;
                ajax_get({
                    'url':"/admin/organ/index",
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
                    'url':"/admin/organ/edit",
                    'data':{
                        'id':this.organ_id,
                        'name':this.sub_data.name,
                        'abridge':this.sub_data.abridge,
                        'address':this.sub_data.address,
                        'mobile':this.sub_data.mobile,
                        'status':this.sub_data.status,
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