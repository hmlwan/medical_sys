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
                            prop="manage_name"
                            label="账号"
                            width="180">
                        <template slot-scope="scope">
                            <span style="margin-left: 10px">{{ scope.row.manage_name }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column
                            prop="real_name"
                            label="真实姓名"
                            width="180">

                    </el-table-column>
                    <el-table-column
                            prop="auth_type_name"
                            label="授权">
                    </el-table-column>
                    <el-table-column
                            prop="sign_img"
                            label="手写签名图片">
                        <template slot-scope="scope">
                            <span style="margin-left: 10px">
                                <el-image
                                        style="width: 100px; height: 100px"
                                        :src="signImg(scope.row.sign_img)"
                                         fit="fit"></el-image>
                                </span>
                        </template>
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
                    <el-table-column
                            prop="forbidden_time"
                            label="过期时间">
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button
                                    size="mini"
                                    @click="handleEdit( scope.row)">编辑</el-button>
<!--                            <el-button-->
<!--                                    size="mini"-->
<!--                                    type="danger"-->
<!--                                    @click="handleDelete(scope.row)">删除</el-button>-->
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
                    <el-form v-model="manager_id"   label-width="80px" >
                          <el-form-item label="用户名">
                            <el-input v-model="sub_data.manage_name" placeholder="输入用户名"></el-input>
                          </el-form-item>
                          <el-form-item label="真实姓名">
                            <el-input v-model="sub_data.real_name" placeholder="输入用户名"></el-input>
                          </el-form-item>
                          <el-form-item label="手写签名">
                              <el-upload
                                      class="upload-demo"
                                      :action="uploadUrl"
                                      :headers="headers"
                                      :multiple="false"
                                      :limit="1"
                                      :on-success="uploadSuccess"
                                      :on-exceed="uploadExceed"
                                      name="image"
                                      :file-list="fileList">
                              <el-button size="small" type="primary">点击上传</el-button>
                              <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                            </el-upload>
                          </el-form-item>
                         <el-form-item label="密码">
                            <el-input v-model="sub_data.password" placeholder="输入密码" type="password"></el-input>
                          </el-form-item>
                        <el-form-item label="权限" prop="resource">
                            <el-radio-group v-model="sub_data.auth_type">
                              <el-radio  :label="1"  style="margin-bottom: 20px" >管理员</el-radio>
                              <el-radio :label="2" style="margin-bottom: 20px"  >机构管理员</el-radio>
                              <el-radio :label="3" style="margin-bottom: 20px"  >录入医生</el-radio>
                              <el-radio :label="4" style="margin-bottom: 20px"  >心电医生</el-radio>
                              <el-radio :label="5" style="margin-bottom: 20px"  >普通用户</el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item label="到期时间">
                              <el-date-picker type="date" value-format=“yyyy-MM-dd” placeholder="不选为永久" v-model="sub_data.forbidden_time" style="width: 100%;"></el-date-picker>
                          </el-form-item>
                          <el-form-item label="状态">
                              <el-select v-model="sub_data.status"  placeholder="请选择状态">
                                  <el-option label="启用" :value="1"></el-option>
                                  <el-option label="禁用" :value="0"></el-option>
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
    import {ajax_get, ajax_post} from "../../assets/js/ajax";
    import config from '../../config'
    export default {
        name: "user",
        data(){
            return {
                tableData:[],
                sub_data:{
                    manage_name:"",
                    real_name:"",
                    auth_type:"",
                    password:"",
                    sign_img:"",
                    forbidden_time:"",
                    status:"",
                },
                resource:"",
                default_data:{},
                manager_id:'',
                total:0,
                pageSize:10,
                loading:false,
                centerDialogVisible:false,
                fileList:[],
                action:"/admin/upload/uploadEditor",
                fit:"fit",
                label:"",
            }
        },
        created() {
            this.default_data = this.sub_data;
            this.getList(this,1);
        },

        computed:{
            uploadUrl(){
                console.log( config.domain + this.action);
                return config.domain + this.action
            },

            headers(){
                let x_token = this.$cookies.get('X-Token');
                return  {
                    'X-Token':  x_token
                };
            }
        },
        methods:{
            handleChange(p) {
                this.getList(this,p);
            },
            signImg(sign_img){
                return config.domain + sign_img
            },
            handleEdit(item){
                console.log(item);
                if(item){
                    console.log(item);
                    item.auth_type = parseInt(item.auth_type)
                    this.sub_data = item
                    if(item.sign_img){
                       this.fileList = [
                           {
                               name:'sign_img.png',
                               url:item.sign_img
                           }
                       ]
                    }
                    console.log( this.fileList );
                    this.manager_id = item.id
                }else{
                    this.sub_data = this.default_data
                    this.manager_id = ''
                }
                this.centerDialogVisible = true
            },
            uploadSuccess(res,file){
                console.log(res);
                if(res.errno != 0){
                    this.$message.error('上传失败')
                    return false
                }
                this.fileList = [
                    {
                        name:file.name,
                        url:res.data[0]
                    }
                ]
            },

            uploadExceed(file,res){
                if(res.length>0){
                    this.$message.error('只能上传一个文件')
                    return false
                }
            },
            getList(that,p){
                let x_token = this.$cookies.get('X-Token');
                that.loading = true;
                ajax_get({
                    'url':"/admin/manage/index",
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
                console.log(this.fileList);
                const data = {
                    'id':this.manager_id,
                    'manage_name':this.sub_data.manage_name,
                    'real_name':this.sub_data.real_name,
                    'auth_type':this.sub_data.auth_type,
                    'password':this.sub_data.password,
                    'sign_img':this.fileList[0]['url'],
                    'forbidden_time':this.sub_data.forbidden_time,
                    'status':this.sub_data.status,
                };
                console.log(data);
                ajax_post({
                    'url':"/admin/manage/edit",
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
</style>