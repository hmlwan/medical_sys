<template>
    <el-container>
        <el-main>
            <div class="agency_con">
                <div class="text-left" style="margin-bottom: 15px">
                    <el-button type="primary" @click="centerDialogVisible=true" icon="el-icon-plus" title="添加">添加</el-button>
                </div>
                <el-table
                        :data="tableData"
                        style="width: 100%">
                    <el-table-column type="expand">
                        <template slot-scope="props">
                            <el-form label-position="left" inline class="demo-table-expand">
                                <el-form-item label="商品名称">
                                    <span>{{ props.row.name }}</span>
                                </el-form-item>
                                <el-form-item label="所属店铺">
                                    <span>{{ props.row.shop }}</span>
                                </el-form-item>
                                <el-form-item label="商品 ID">
                                    <span>{{ props.row.id }}</span>
                                </el-form-item>
                                <el-form-item label="店铺 ID">
                                    <span>{{ props.row.shopId }}</span>
                                </el-form-item>
                                <el-form-item label="商品分类">
                                    <span>{{ props.row.category }}</span>
                                </el-form-item>
                                <el-form-item label="店铺地址">
                                    <span>{{ props.row.address }}</span>
                                </el-form-item>
                                <el-form-item label="商品描述">
                                    <span>{{ props.row.desc }}</span>
                                </el-form-item>
                            </el-form>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="商品 ID"
                            prop="id">
                    </el-table-column>
                    <el-table-column
                            label="商品名称"

                           >
                        <template slot-scope="props">
                            <span>{{ props.row.name }}</span>
                        </template>

                    </el-table-column>
                    <el-table-column
                            label="描述"
                            prop="desc">
                    </el-table-column>
                    <el-table-column label="操作"
                                     prop="op">
                        <template>
                            <el-button
                                    size="mini"
                                    @click="handleEdit()">编辑</el-button>
                            <el-button
                                    size="mini"
                                    @click="handleConf()">参数</el-button>
                            <el-button
                                    size="mini"
                                    type="danger"
                                    @click="handleDelete()">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination
                        style="margin-top: 20px"
                        background
                        layout="prev, pager, next"
                        :total="1000">
                </el-pagination>
            </div>
            <el-dialog
                    title=""
                    :visible.sync="centerDialogVisible"
                    width="30%"
                    center="false">
                <span>
                    <el-form  label-width="80px" >
                          <el-form-item  label="测值名称">
                            <el-input  v-model="title"></el-input>
                          </el-form-item>
                          <el-form-item label="原始标识">
                            <el-input v-model="label"></el-input>
                          </el-form-item>
                        <el-form-item label="字段名">
                            <el-input v-model="fieldname"></el-input>
                          </el-form-item>
                        <el-form-item label="数据标识">
                            <el-input v-model="litefield"></el-input>
                          </el-form-item>
                          <el-form-item label="参考范围">
                            <el-input v-model="ranges"></el-input>
                          </el-form-item>
                         <el-form-item label="测值单位">
                            <el-input v-model="unit"></el-input>
                          </el-form-item>
                           <el-form-item label="科普说明">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入科普说明"
                                      v-model="popularization"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>
                        <el-form-item label="建议内容">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入建议内容"
                                      v-model="propose"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>
                    </el-form>
                </span>
                <span slot="footer" class="dialog-footer">
    <el-button @click="centerDialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="centerDialogVisible = false">确 定</el-button>
  </span>
            </el-dialog>
            <el-dialog
                    title=""
                    :visible.sync="confDialogVisible"
                    width="30%"
                    center="false">
                <span>
                    <el-form  label-width="80px" >
                          <el-form-item  label="测值名称">
                            <el-input  v-model="title"></el-input>
                          </el-form-item>
                          <el-form-item label="原始标识">
                            <el-input v-model="label"></el-input>
                          </el-form-item>
                        <el-form-item label="字段名">
                            <el-input v-model="fieldname"></el-input>
                          </el-form-item>
                        <el-form-item label="数据标识">
                            <el-input v-model="litefield"></el-input>
                          </el-form-item>
                          <el-form-item label="参考范围">
                            <el-input v-model="ranges"></el-input>
                          </el-form-item>
                         <el-form-item label="测值单位">
                            <el-input v-model="unit"></el-input>
                          </el-form-item>
                           <el-form-item label="科普说明">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入科普说明"
                                      v-model="popularization"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>
                        <el-form-item label="建议内容">
                              <el-input
                                      type="textarea"
                                      placeholder="请输入建议内容"
                                      v-model="propose"
                                      show-word-limit
                                      rows="3"
                              >
                                </el-input>
                          </el-form-item>
                    </el-form>
                </span>
                <span slot="footer" class="dialog-footer">
    <el-button @click="confDialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="confDialogVisible = false">确 定1</el-button>
  </span>
            </el-dialog>

        </el-main>
    </el-container>
</template>

<script>
    export default {
        name: "basic_conf",
        data(){
            return {
                tableData: [{
                    id: '1298712112',
                    name: '好滋好味鸡蛋仔',
                    category: '江浙小吃、小吃零食',
                    desc: '荷兰优质淡奶，奶香浓而不腻',
                    address: '上海市普陀区真北路',
                    shop: '王小虎夫妻店',
                    shopId: '10333'
                }, {
                    id: '12987123',
                    name: '好滋好味鸡蛋仔',
                    category: '江浙小吃、小吃零食',
                    desc: '荷兰优质淡奶，奶香浓而不腻',
                    address: '上海市普陀区真北路',
                    shop: '王小虎夫妻店',
                    shopId: '10333'
                }, {
                    id: '12987125',
                    name: '好滋好味鸡蛋仔',
                    category: '江浙小吃、小吃零食',
                    desc: '荷兰优质淡奶，奶香浓而不腻',
                    address: '上海市普陀区真北路',
                    shop: '王小虎夫妻店',
                    shopId: '10333'
                }, {
                    id: '12987126',
                    name: '好滋好味鸡蛋仔',
                    category: '江浙小吃、小吃零食',
                    desc: '荷兰优质淡奶，奶香浓而不腻',
                    address: '上海市普陀区真北路',
                    shop: '王小虎夫妻店',
                    shopId: '10333'
                }],
                centerDialogVisible:false,
                confDialogVisible:false,

                title:"",
                label:"",
                fieldname:"",
                ranges:"",
                unit:"",
                part_id:"",
                litefield:"",
                propose:"",
                popularization:"",
            }
        },
        methods:{
            handleChange(file, fileList) {
                this.fileList = fileList.slice(-3);
            },
            handleEdit(){
                console.log(2222);
                this.centerDialogVisible = true;
            },
            handleDelete(){

            },
            handleConf(){
                this.confDialogVisible = true;
            }
        }
    }

</script>

<style scoped>
    .el-main {
        color: #333;
        text-align: center;
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