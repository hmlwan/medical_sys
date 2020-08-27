<template>
    <el-container>
        <el-main>
            <div class="text-left" style="margin-bottom: 10px">
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
            <el-dialog
                    title=""
                    :visible.sync="centerDialogVisible"
                    width="30%"
                    center="false">
                <span>
                    <el-form  label-width="80px" >
                          <el-form-item label="机构名称">
                            <el-input ></el-input>
                          </el-form-item>
                          <el-form-item label="标识">
                            <el-input ></el-input>
                          </el-form-item>
                        <el-form-item label="联系地址">
                            <el-input ></el-input>
                          </el-form-item>
                        <el-form-item label="联系电话">
                            <el-input ></el-input>
                          </el-form-item>
                          <el-form-item label="状态">
                              <el-select v-model="status"  placeholder="请选择状态">
                                  <el-option label="启用" value="1"></el-option>
                                  <el-option label="禁用" value="0"></el-option>
                              </el-select>
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
        name: "agency",
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
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1516 弄'
                }],
                centerDialogVisible:false,
                status:'',

            }
        },
        methods:{
            handleChange(file, fileList) {
                this.fileList = fileList.slice(-3);
            },
            handleEdit(){
                this.centerDialogVisible = true;
            }

        }
    }
</script>

<style scoped>
    .el-main {
        color: #333;
        text-align: center;
    }
</style>