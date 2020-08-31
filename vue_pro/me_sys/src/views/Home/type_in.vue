<template>
    <div>
        <el-container >
            <el-main>
                <div class="yhxx"> <!--用户信息-->
                    <el-card>
                            <div slot="header" class="clearfix text-left">
                                <span>用户信息</span>
                            </div>
                        <div class="yhxx_left float_l">
                            <el-row :gutter="20" >
                                <el-col :span="5" :offset="3" >
                                    <div class="input_line">
                                        <label for="">姓名</label>
                                        <el-input  style="width: 200px"  disabled placeholder="姓名"></el-input>
                                    </div>
                                </el-col>
                                <el-col :span="5" :offset="3"><div>
                                    <div class="input_line">
                                        <label for="">性别</label>
                                        <el-input  style="width: 200px"  disabled placeholder="性别"></el-input>
                                    </div>
                                </div></el-col>
                            </el-row>
                            <el-row :gutter="20">
                                <el-col :span="5" :offset="3">
                                    <div class="input_line">
                                        <label for="">年龄</label>
                                        <el-input  style="width: 200px"  disabled placeholder="年龄"></el-input>
                                    </div>
                                </el-col>
                                <el-col :span="5" :offset="3">
                                    <div class="input_line">
                                        <label for="">检查号</label>
                                        <el-input  style="width: 200px"  disabled placeholder="检查号"></el-input>
                                    </div>
                                </el-col>
                            </el-row>
                            <el-row :gutter="20">
                                <el-col :span="5" :offset="3">
                                    <div class="input_line">
                                        <label for="">身份证</label>
                                        <el-input  style="width: 200px"  disabled placeholder="身份证"></el-input>
                                    </div>
                                </el-col>

                            </el-row>
                        </div>
                        <div class="yhxx_rigth float_r">
                            <el-row class="demo-avatar demo-basic">
                                <el-col :span="12">
                                    <div class="demo-basic--circle">
                                        <div class="block"><el-avatar shape="square"  :size="110"  :src="circleUrl"></el-avatar></div>
                                    </div>
                                </el-col>
                            </el-row>
                        </div>
                    </el-card>
                </div>
                <div class="jbxx" style="margin-top: 10px"><!--基本信息-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>基本信息</span>
                        </div>
                        <el-form :inline="true"  class="demo-form-inline">
                            <div class="jbxx_div">
                                <el-form-item label="身高" >
                                    <el-input style="width: 200px;"  placeholder="身高">
                                        <template slot="append">CM</template>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="体重">
                                    <el-input style="width: 200px;"  placeholder="体重">
                                        <template slot="append">KG</template>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="BMI">
                                    <el-input style="width: 200px;"   placeholder="BMI">
                                    </el-input>
                                </el-form-item>
                            </div>
                            <div  class="jbxx_div">
                                <el-form-item label="左侧血压">
                                    <el-col :span="6">
                                        <el-input  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="8">
                                        <el-input  placeholder=""> <template slot="append">mmHg</template></el-input>
                                    </el-col>
                                </el-form-item>
                                <el-form-item label="右侧血压">
                                    <el-col :span="6">
                                        <el-input  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="8">
                                        <el-input  placeholder="">   <template slot="append">mmHg</template></el-input>
                                    </el-col>
                                </el-form-item>
                            </div>
                            <div  class="jbxx_div">
                                <el-form-item label="视力">
                                    <el-col :span="6">
                                        <el-input  placeholder="左侧视力"></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="6">
                                        <el-input  placeholder="右侧视力"></el-input>
                                    </el-col>
                                </el-form-item>
                                <el-form-item label="矫正视力">
                                    <el-col :span="6">
                                        <el-input  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="6">
                                        <el-input  placeholder=""></el-input>
                                    </el-col>
                                </el-form-item>
                            </div>
                            <div class="jbxx_div">
                                <el-form-item label="脉率" >
                                    <el-input  placeholder="脉率">
                                    </el-input>
                                </el-form-item>

                                <el-form-item label="腰围">
                                    <el-input  placeholder="腰围">
                                    </el-input>
                                </el-form-item>
                            </div>
                            <div class="jbxx_div">
                                <el-form-item>
                                    <el-button type="primary" >保存</el-button>
                                </el-form-item>
                            </div>
                        </el-form>
                        <div>
                            <el-divider> <span>小结</span></el-divider>
                            <el-input
                                    type="textarea"
                                    :rows="10"
                                    placeholder="请输入内容"
                            >
                            </el-input>

                        </div>
                    </el-card>
                </div>
                <div class="xdt" style="margin-top: 10px"> <!--影像模版-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>超声检查</span>
                        </div>
                        <el-input
                                type="textarea"
                                :rows="10"
                                placeholder="请输入内容"
                        >
                        </el-input>
                    </el-card>
                </div>
                <div class="xdt" style="margin-top: 10px"> <!--心电图-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>心电图</span>
                        </div>
                        <div class="demo-image__preview">
                            <el-image
                                      :src="xdtUrl"
                                    :preview-src-list="srcList">
                            </el-image>
                        </div>
                        <div>
                            <el-divider> <span>小结</span></el-divider>
                            <el-input
                                    type="textarea"
                                    :rows="10"
                                    placeholder="请输入内容"
                            >
                            </el-input>

                        </div>
                    </el-card>
                </div>

                <div class="zjjl" style="margin-top: 10px"><!--总检结论-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>总检结论</span>
                        </div>
                        <el-input
                                type="textarea"
                                :rows="10"
                                placeholder="请输入内容"
                               >
                        </el-input>
                        <el-row class="text-right" style="margin-top: 20px">
                            <el-button type="primary">选择疾病</el-button>
                            <el-button type="primary">保存</el-button>
                        </el-row>
                    </el-card>
                </div>
            </el-main>
            <el-aside style="width: 38%;">
                <div class="el-aside_con">
                    <div class="jstj">
                        <el-form :inline="true"  class="demo-form-inline">
                            <div class="jstj_search">
                                <el-form-item label="检查序号">
                                    <el-input style="width: 150px"  placeholder="检查序号"></el-input>
                                </el-form-item>
                                <el-form-item label="身份证">
                                    <el-input style="width: 150px"  placeholder="身份证"></el-input>
                                </el-form-item>
                                <el-form-item label="姓名">
                                    <el-input style="width: 150px" placeholder="姓名"></el-input>
                                </el-form-item>
                            </div>
                            <div class="jstj_search">
                                <el-form-item label="打印状态">
                                    <el-select   placeholder="请选择打印状态" v-model="value2">
                                        <el-option label="区域一" value="shanghai"></el-option>
                                        <el-option label="区域二" value="beijing"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="机构">
                                    <el-select   placeholder="请选择机构" v-model="value3">
                                        <el-option label="区域一" value="shanghai"></el-option>
                                        <el-option label="区域二" value="beijing"></el-option>
                                    </el-select>
                                </el-form-item>

                            </div>
                            <div class="jstj_search">
                                <el-form-item label="检查日期" >
                                    <el-date-picker
                                            v-model="value1"
                                            type="daterange"
                                            range-separator="至"
                                            start-placeholder="开始日期"
                                            end-placeholder="结束日期">
                                    </el-date-picker>
                                </el-form-item>
                            </div>
                            <el-form-item class="">
                                <el-button type="primary"  icon="el-icon-search">查询</el-button>
                            </el-form-item>
                        </el-form>
                      <div>
                          <div class="text-right" style="margin-bottom: 10px">
                              <el-button type="primary" icon="el-icon-printer" title="打印">打印</el-button>
                              <el-button type="primary" icon="el-icon-download" title="导出">导出</el-button>
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
                    </div>
                </div>
            </el-aside>
        </el-container>
    </div>
</template>
<script>
    export default {
        name: "type_in",
        data(){
            return {
                circleUrl: "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png",
                xdtUrl:"https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg",
                srcList: [
                    'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg',
                ],
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
                value1: '',
                value2: '',
                value3: '',
            }
        },
        methods: {
            handleEdit(index, row) {
                console.log(index, row);
            },
            handleDelete(index, row) {
                console.log(index, row);
            }
        },
    }
</script>

<style scoped>
    .el-aside {
        /*background-color: #D3DCE6;*/
        color: #333;
        /*text-align: center;*/
        height: auto;
    }

    .el-main {
        background-color: #E9EEF3;
        color: #333;
        text-align: center;
        height: auto;
        /*line-height: 160px;*/
    }
    .yhxx .yhxx_left{
        width: 75%;
    }
    .yhxx .yhxx_rigth{
        width: 25%;
    }
    .input_line{
         width: 300px;
    }
    .input_line label{
        width: 90px;
        display: inline-block;
        text-align: right;
        margin-right: 10px;
    }
    .jbxx .jbxx_div{
        width: 100%;
        text-align: left;
        margin-left: 15%;
    }
    .xdt .demo-image__preview{
        height: 600px;
        overflow-y: auto;
    }
    .el-aside .el-aside_con{
        width: 36%;
        position: fixed;
        right: 1%;
        top:10%;
        z-index: 100;
        /*margin-left: 5%;*/
        /*margin-top: 10%*/
    }
    .jstj{
        width: 100%;
    }
    .el-row {
        margin-bottom: 20px;
        &:last-child {
         margin-bottom: 0;
     }
    }
    .el-col {
        border-radius: 4px;
    }
    .bg-purple-dark {
        background: #99a9bf;
    }
    .bg-purple {
        background: #d3dce6;
    }
    .bg-purple-light {
        background: #e5e9f2;
    }
    .grid-content {
        border-radius: 4px;
        min-height: 36px;
    }
    .row-bg {
        padding: 10px 0;
        background-color: #f9fafc;
    }
    .yhxx .yhxx_item{
        /*line-height: initial;*/
    }
    .yhxx .yhxx_item label{
        margin-right: 10px;
    }
    .yhxx .yhxx_item  .el-input {
        margin-right: 20px;
    }







</style>