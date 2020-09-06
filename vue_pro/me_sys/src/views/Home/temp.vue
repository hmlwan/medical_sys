<template>
    <div>
        <el-container v-model="stype">

            <el-aside width="50%">
                <div class="tree_left">
                    <div style="text-align: left;margin-left: 15px;margin-bottom: 15px">
                        <el-button  type="primary" plain icon="el-icon-plus" @click="addTemplate()">添加一级菜单</el-button>
                    </div>
                    <el-tree
                            :data="data"
                            node-key="id"
                            >
                    <span class="custom-tree-node" @click="getFormat(data)" slot-scope="{ node, data }">

                        <span><i v-if="node.level == 2" class="el-icon-folder" style="margin-right: 5px"></i>{{ node.label }}</span>
                        <span>
                              <el-button
                                      type="text"
                                      size="mini"
                                      @click="() => append(data)"
                                      v-if="node.level == 1"
                              >
                                <i class="el-icon-plus"></i>
                              </el-button>
                            <el-button
                                    type="text"
                                    size="mini"
                                    @click="() => edit(node,data)">
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
                    <el-form :label-position="labelPosition"  v-model="template_id" label-width="80px" >
                        <el-form-item label="标题">
                            <el-input v-model="sub_data.title"    disabled placeholder="请输入标题"></el-input>
                        </el-form-item>
                        <el-form-item label="影像所见">
                            <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    placeholder="请输入影像所见"
                                    v-model="sub_data.imagingfindings">
                            </el-input>
                        </el-form-item>
                        <el-form-item label="诊断建议">
                            <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    placeholder="请输入诊断建议"
                                    v-model="sub_data.diagnosis">
                            </el-input>
                        </el-form-item>
                        <el-form-item label="科普说明">
                            <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    placeholder="请输入科普说明"
                                    v-model="sub_data.popularization">
                            </el-input>
                        </el-form-item>
                        <el-form-item label="建议内容">
                            <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 6}"
                                    placeholder="请输入建议内容"
                                    v-model="sub_data.propose">
                            </el-input>
                        </el-form-item>
                    </el-form>
                </div>
                <el-button type="primary" @click="onSubmit">确定</el-button>
                <el-button @click="reset">重置</el-button>
            </el-main>
        </el-container>
    </div>

</template>

<script>
    import {ajax_post} from '../../assets/js/ajax'

    export default {
        name: "temp",
        data() {
            return {
                data: [],
                sub_data:{
                    title:"",
                    imagingfindings:"",
                    diagnosis:"",
                    popularization:"",
                    propose:"",

                },
                labelPosition: 'top',
                template_id:'',
                stype:''
            }
        },
        watch: {
            '$route.name':function(newVal,oldVal) {
                console.log(newVal+"---"+oldVal);
                if(newVal == 'temp'){
                    this.stype = 1;
                }else{
                    this.stype = 2;
                }
                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':'/admin/template/index',
                    'data':{
                        'stype':this.stype,
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
            }
        },
        methods: {
            addTemplate(){
                console.log( this.stype );
                this.$prompt('','添加一级菜单',{
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
                            'stype':this.stype,
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

                            this.templat(this.data,value,1,res.data);
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
               let template_id = this.template_id;
               if(!template_id){
                   this.$message.error('请选中模版')
                   return
               }
                let x_token = this.$cookies.get('X-Token');

                ajax_post({
                    'url':"/admin/template/edit_format",
                    'data':{
                        'id':template_id,
                        'diagnosis':this.sub_data.diagnosis,
                        'imagingfindings':this.sub_data.imagingfindings,
                        'popularization':this.sub_data.popularization,
                        'propose':this.sub_data.propose,
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
            },
            append(data) {
                console.log(data);
                let pid = data.id;
                this.$prompt('','请输入名称',{
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(({value})=>{
                    if(!value){
                        this.$message.error('请输入名称');
                        return false
                    }
                    let x_token = this.$cookies.get('X-Token');
                    ajax_post({
                        'url':"/admin/template/edit",
                        'data':{
                            'stype':this.stype ,
                            'title':value,
                            'pid':pid,
                            'id':''
                        },
                        'headers':{
                            'X-Token':  x_token
                        }
                    }).then(res=>{
                        console.log(res);
                        if(res.code == 0){
                            this.$message.success(res.message)
                            this.templat(data,value,2,res.data);
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
            templat(data,value,level,id){
                const newChild = { id: id, label: value, children: [],level:level };
                if (!data.children) {
                    this.$set(data, 'children', []);
                }
                if(level == 1){
                    data.push(newChild);
                }else{
                    data.children.push(newChild);
                }
            },
            edit(node,data) {
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
                        'url':"/admin/template/edit_name",
                        'data':{
                            'title':value,
                            'id':data.id
                        },
                        'headers':{
                            'X-Token':  x_token
                        }
                    }).then(res=>{
                        console.log(res);
                        if(res.code == 0){
                            this.$message.success(res.message)
                            data.label = value
                            this.sub_data.title = value
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
            remove(node, data) {
                console.log(data);
                this.$confirm("确定删除吗").then(()=>{
                    let x_token = this.$cookies.get('X-Token');
                    ajax_post({
                        'url':"/admin/template/del",
                        'data':{
                            'id':data.id,
                            'level':data.level,
                        },
                        'headers':{
                            'X-Token':  x_token
                        }
                    }).then(res=>{
                        console.log(res);
                        if(res.code == 0){
                            this.$message.success(res.message)

                            const parent = node.parent;
                            const children = parent.data.children || parent.data;
                            const index = children.findIndex(d => d.id === data.id);
                            children.splice(index, 1);

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
                }).catch(e=>{
                    console.log(e);
                })
            },

            getFormat(data){
                console.log(data);
                if(data.level == 1){
                    this.sub_data = {
                        imagingfindings:"",
                        diagnosis:"",
                        popularization:"",
                        propose:"",
                    }
                    return;
                }
                this.template_id = data.id

                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/template/format",
                    'data':{
                        'id':data.id,
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    if(res.code == 0){
                        this.sub_data = {
                            title:data.label,
                            imagingfindings:res.data.imagingfindings ,
                            diagnosis:res.data.diagnosis,
                            popularization:res.data.popularization,
                            propose:res.data.propose,
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
            reset(){
                this.sub_data.imagingfindings = ""
                this.sub_data.diagnosis = ""
                this.sub_data.popularization = ""
                this.sub_data.propose = ""
            }

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
        /*height: 700px;*/
        /*overflow-y: auto;*/
    }
</style>