<template>
    <el-container>
        <el-main>
            <div class="agency_con">
                <div class="text-left" style="margin-bottom: 15px">
                    <el-button type="primary" @click="centerDialogVisible=true" icon="el-icon-plus" title="添加">添加</el-button>
                </div>
                <el-table
                        :data="tableData"
                        border
                        style="width: 100%">
                    <el-table-column
                            prop="date"
                            label="日期"
                            width="180">
                        <template slot-scope="scope">
                            <span style="margin-left: 10px">{{ scope.row.date }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column
                            prop="name"
                            label="姓名"
                            width="180">

                    </el-table-column>
                    <el-table-column
                            prop="address"
                            label="地址">
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button
                                    size="mini"
                                    @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                            <el-button
                                    size="mini"
                                    type="danger"
                                    @click="handleDelete(scope.$index, scope.row)">删除</el-button>
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
                          <el-form-item label="项目">
                              <el-select v-model="part_id"  placeholder="请选择项目">
                                  <el-option label="血常规" value="1"></el-option>
                                  <el-option label="尿常规" value="2"></el-option>
                                  <el-option label="生化" value="3"></el-option>
                              </el-select>
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
        </el-main>
    </el-container>
</template>

<script>
    export default {
        name: "measure",
        data(){
            return {
                tableData:[{
                    date: '2016-05-02',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }, {
                    date: '2016-05-04',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1517 弄'
                }, {
                    date: '2016-05-01',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1519 弄'
                }, {
                    date: '2016-05-03',
                    name: '王小虎1',
                    address: '上海市普陀区金沙江路 1516 弄'
                }],
                centerDialogVisible:false,

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
</style>