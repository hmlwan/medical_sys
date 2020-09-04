<template>
    <div>
        <el-container>

            <el-aside width="50%">
                <div class="tree_left">
                    <div style="text-align: left;margin-left: 15px;margin-bottom: 15px">
                        <el-button  type="primary" plain icon="el-icon-plus" @click="addTemplate()">添加一级菜单</el-button>
                    </div>
                    <el-tree
                            :data="data"
                            node-key="id"
                            :expand-on-click-node="false">
                    <span class="custom-tree-node" slot-scope="{ node, data }">

                        <span>{{ node.title }}</span>
                        <span>
                              <el-button
                                      type="text"
                                      size="mini"
                                      @click="() => append(data)">
                                <i class="el-icon-plus"></i>
                              </el-button>
                            <el-button
                                    type="text"
                                    size="mini"
                                    @click="() => edit(data)">
                                <i class="el-icon-edit"></i>
                              </el-button>
                              <el-button
                                      type="text"
                                      size="mini"
                                      @click="() => remove(node, data)">
                               <i class="el-icon-delete"></i>

                              </el-button>
                        </span>
                      </span>
                    </el-tree>
                </div>
            </el-aside>

            <el-main>
                <div class="tree_right">
                    <el-form :label-position="labelPosition" label-width="80px" >
                        <el-form-item label="标题">
                            <el-input v-model="title" placeholder="请输入标题"></el-input>
                        </el-form-item>
                        <el-form-item label="影像所见">
                            <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    placeholder="请输入影像所见"
                                    v-model="textarea1">
                            </el-input>
                        </el-form-item>
                        <el-form-item label="科普说明">
                            <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    placeholder="请输入科普说明"
                                    v-model="textarea2">
                            </el-input>
                        </el-form-item>
                        <el-form-item label="建议内容">
                            <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    placeholder="请输入建议内容"
                                    v-model="textarea3">
                            </el-input>
                        </el-form-item>
                        <el-form-item size="large">
                            <el-button type="primary" @click="onSubmit">确定</el-button>
                            <el-button>重置</el-button>
                        </el-form-item>
                    </el-form>

                </div>

            </el-main>
        </el-container>
    </div>

</template>

<script>
    import {ajax_post} from '../../assets/js/ajax'

    let id = 1000;
    export default {
        name: "temp",
        data() {
            return {
                data: [],
                title:"",
                textarea1:"",
                textarea2:"",
                textarea3:"",
                labelPosition: 'top',
            }
        },
        created() {
            let x_token = this.$cookies.get('X-Token');
            ajax_post({
                'url':'/admin/template/index',
                'data':{
                    'stype':1,
                },
                'headers':{
                    'X-Token':  x_token
                }
            }).then(res=>{
                console.log(res);
                if(res.code == 0){
                    let data = res.data;
                    this.data = data;
                }else if(res.code == 100){
                    this.$router.push('/login');
                }
            }).catch(err=>{
                console.log(err);
                this.$message.error('请求错误')
                return false
            })
        },
        methods: {
            addTemplate(){
                this.$prompt('','请输入名称',{
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',

                }).then(({ value })=>{
                    console.log(value);
                    if(!value){
                        this.$message.error('请输入名称');
                        return false
                    }
                    let x_token = this.$cookies.get('X-Token');
                    ajax_post({
                        'url':"/admin/template/edit",
                        'data':{
                            'stype':1,
                            'title':value,
                            'pid':0,
                            'id':''
                        },
                        'headers':{
                            'X-Token':  x_token
                        }
                    }).then(res=>{
                        console.log(res);
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
                }).catch(()=>{
                        this.$message({
                            'type':"info",
                            'message':"取消输入"
                        })
                })
            },
            onSubmit(){
                return ;
            },
            append(data) {
                const newChild = { id: id++, label: 'testtest', children: [] };
                if (!data.children) {
                    this.$set(data, 'children', []);
                }
                data.children.push(newChild);
            },
            edit(data) {
                const newChild = { id: id++, label: 'testtest', children: [] };
                if (!data.children) {
                    this.$set(data, 'children', []);
                }
                data.children.push(newChild);
            },
            remove(node, data) {
                const parent = node.parent;
                const children = parent.data.children || parent.data;
                const index = children.findIndex(d => d.id === data.id);
                children.splice(index, 1);
            },

           
        }
    }
</script>
<style scoped>
    .custom-tree-node {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        padding-right: 8px;
    }
    .el-aside{
        text-align: right;
    }
    .tree_left{
        width: 60%;
        margin: 8% auto 0;
        height: 800px;
        overflow-y: auto;
    }
    .tree_right{
        margin-top: 5%;
        width: 50%;
        height: 800px;
        overflow-y: auto;
    }
</style>