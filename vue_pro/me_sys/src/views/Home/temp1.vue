<template>
    <div>
        <el-container>
            <el-aside width="50%">
                <div class="tree_left">
                   <div style="text-align: left;margin-left: 15px;">
                       <el-button size="mini" icon="el-icon-plus" circle></el-button>
                   </div>
                    <el-tree
                            :data="data"

                            node-key="id"
                            :expand-on-click-node="false">
                    <span class="custom-tree-node" slot-scope="{ node, data }">
                        <span>{{ node.label }}</span>
                        <span>
                              <el-button
                                      type="text"
                                      size="mini"
                                      @click="() => append(data)">
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
    let id = 1000;
    export default {
        name: "temp1",
        data() {
            const data = [{
                id: 1,
                label: '一级 1',
                children: [{
                    id: 4,
                    label: '二级 1-1',
                }]
            }, {
                id: 2,
                label: '一级 2',
                children: [{
                    id: 5,
                    label: '二级 2-1'
                }, {
                    id: 6,
                    label: '二级 2-2'
                }]
            }, {
                id: 3,
                label: '一级 3',
                children: [{
                    id: 7,
                    label: '二级 3-1'
                }, {
                    id: 8,
                    label: '二级 3-2'
                }]
            }];
            return {
                data: JSON.parse(JSON.stringify(data)),
                title:"",
                textarea1:"",
                textarea2:"",
                textarea3:"",
                labelPosition: 'top',
            }
        },
        methods: {
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
        margin: 10% auto 0;
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