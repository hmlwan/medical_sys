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
                                        <label for="">检查序号</label>
                                        <el-input v-model="checkList.check_number"  style="width: 200px"  disabled placeholder="检查序号"></el-input>
                                    </div>
                                </el-col>
                                <el-col :span="5" :offset="3"><div>
                                    <div class="input_line">
                                        <label for="">姓名</label>
                                        <el-input  v-model="checkList.real_name" style="width: 200px"  disabled placeholder="姓名"></el-input>
                                    </div>
                                </div></el-col>
                            </el-row>

                            <el-row :gutter="20">
                                <el-col :span="5" :offset="3">
                                    <div class="input_line">
                                        <label for="">身份证</label>
                                        <el-input   v-model="checkList.card_id" style="width: 200px"  disabled placeholder="身份证"></el-input>
                                    </div>
                                </el-col>
                                <el-col :span="5" :offset="3">
                                    <div class="input_line">
                                        <label for="">年龄</label>
                                        <el-input  v-model="checkList.age" style="width: 200px"  disabled placeholder="年龄"></el-input>
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
                        <el-form :inline="true"  :model="comData" class="demo-form-inline">
                            <div class="jbxx_div">
                                <el-form-item label="身高" >
                                    <el-input style="width: 200px;" v-model="comData.sg" disabled  placeholder="身高">
                                        <template slot="append">CM</template>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="体重">
                                    <el-input style="width: 200px;" v-model="comData.tz"  disabled  placeholder="体重">
                                        <template slot="append">KG</template>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="BMI">
                                    <el-input style="width: 200px;" v-model="comData.bmi"   disabled  placeholder="BMI">
                                    </el-input>
                                </el-form-item>
                            </div>
                            <div  class="jbxx_div">
                                <el-form-item label="左侧血压">
                                    <el-col :span="6">
                                        <el-input v-model="comData.zcxyss"   disabled placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="8">
                                        <el-input v-model="comData.zcxysz"   disabled placeholder=""> <template slot="append">mmHg</template></el-input>
                                    </el-col>
                                </el-form-item>
                                <el-form-item label="右侧血压">
                                    <el-col :span="6">
                                        <el-input disabled v-model="comData.ycxyss"   placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="8">
                                        <el-input disabled v-model="comData.ycxysz"   placeholder="">   <template slot="append">mmHg</template></el-input>
                                    </el-col>
                                </el-form-item>
                            </div>
                            <div  class="jbxx_div">
                                <el-form-item label="视力">
                                    <el-col :span="6">
                                        <el-input disabled  v-model="comData.g_zysl"  placeholder="左侧视力"></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="6">
                                        <el-input disabled v-model="comData.g_yysl"  placeholder="右侧视力"></el-input>
                                    </el-col>
                                </el-form-item>
                                <el-form-item label="矫正视力">
                                    <el-col :span="6">
                                        <el-input disabled  v-model="comData.g_zyjz"  placeholder=""></el-input>
                                    </el-col>
                                    <el-col class="line" :span="1">-</el-col>
                                    <el-col :span="6">
                                        <el-input disabled v-model="comData.g_yyjz"  placeholder=""></el-input>
                                    </el-col>
                                </el-form-item>
                            </div>
                            <div class="jbxx_div">
                                <el-form-item label="脉率" >
                                    <el-input disabled v-model="comData.ml"   placeholder="脉率">
                                    </el-input>
                                </el-form-item>

                                <el-form-item label="腰围">
                                    <el-input disabled v-model="comData.G_YW"  placeholder="腰围">
                                    </el-input>
                                </el-form-item>
                            </div>
                        </el-form>
                        <div class="xdt">
                            <el-divider> <span>小结</span></el-divider>
                            <el-input
                                    v-html="comData.message"
                                    type="textarea"
                                    :rows="10"
                                    placeholder="请输入内容"
                            >
                            </el-input>

                        </div>
                    </el-card>
                </div>
                <div class="xdt" style="margin-top: 10px"> <!--血常规-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>血常规</span>
                        </div>
                        <el-input
                                type="textarea"
                                v-html="qcData.xcg_message"
                                :rows="10"
                                placeholder="请输入内容"
                        >
                        </el-input>
                    </el-card>
                </div>
                <div class="xdt" style="margin-top: 10px"> <!--尿常规-->
                    <el-card>

                        <div slot="header" class="clearfix text-left">
                            <span>尿常规</span>
                        </div>
                        <el-input
                                type="textarea"
                                v-html="qcData.ncg_message"
                                :rows="10"
                                placeholder="请输入内容"
                        >
                        </el-input>
                    </el-card>
                </div>
                <div class="xdt" style="margin-top: 10px"> <!--生化-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>生化</span>
                        </div>
                        <el-input
                                type="textarea"
                                v-html="qcData.sh_message"
                                :rows="10"
                                placeholder="请输入内容"
                        >
                        </el-input>
                    </el-card>
                </div>
                <div class="xdt" style="margin-top: 10px"> <!--影像模版-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>超声检查</span>
                        </div>
                        <el-input
                                v-html="qcData.sh_message"
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
                                    style="width: 70%;margin-left: 15%;"
                                    :src="xdtUrl"
                                    :preview-src-list="srcList">
                                <div slot="error" class="image-slot">
                                    <span class="dot"></span>
                                </div>
                            </el-image>
                        </div>
                        <div>
                            <el-divider> <span>小结</span></el-divider>
                            <el-input
                                    v-html="xdtData.message"
                                    style=""
                                    type="textarea"
                                    :rows="10"
                                    placeholder="请输入内容"
                            >
                            </el-input>

                        </div>
                    </el-card>
                </div>

                <div class="zjjl xdt" style="margin-top: 10px"><!--总检结论-->
                    <el-card>
                        <div slot="header" class="clearfix text-left">
                            <span>总检结论</span>
                        </div>
                        <div class="el-textarea">
                            <div style="width: 98%;padding: 1%" v-if="message_auto.bmi" >
                                <p>【{{message_auto.bmi.name}} {{message_auto.bmi.title}}】
                                    <el-checkbox></el-checkbox>
                                </p>
                                <p style="text-indent: 25px;">{{message_auto.bmi.popularization}}</p>
                                <p style="text-indent: 25px;">{{message_auto.bmi.propose}}</p>
                            </div>
                            <div style="width: 98%;padding: 1%" v-if="message_auto.dp" >
                                <p>【{{message_auto.dp.name}} {{message_auto.dp.title}}】
                                    <el-checkbox ></el-checkbox>
                                </p>
                                <p style="text-indent: 25px;">{{message_auto.dp.popularization}}</p>
                                <p style="text-indent: 25px;">{{message_auto.dp.propose}}</p>
                            </div>
                            <div style="width: 98%;padding: 1%" v-if="message_auto.ncg_data" >
                                <div  v-for="ncg_data in message_auto.ncg_data" v-bind:key="ncg_data.litefield">
                                    <p>【{{ncg_data.name}} {{ncg_data.title}}】
                                        <el-checkbox></el-checkbox>
                                    </p>
                                    <p style="text-indent: 25px;">{{ncg_data.popularization}}</p>
                                    <p style="text-indent: 25px;">{{ncg_data.propose}}</p>
                           </div>
                            </div>
                            <div style="width: 98%;padding: 1%" v-if="message_auto.xcg_data" >
                                     <div  v-for="xcg_data in message_auto.xcg_data" v-bind:key="xcg_data.litefield">
                                    <p>【{{xcg_data.name}} {{xcg_data.title}}】
                                        <el-checkbox></el-checkbox>
                                    </p>
                                    <p style="text-indent: 25px;">{{xcg_data.popularization}}</p>
                                    <p style="text-indent: 25px;">{{xcg_data.propose}}</p>
                                </div>
                            </div>
                            <div style="width: 98%;padding: 1%" v-if="message_auto.sh_data" >
                                <div  v-for="sh_data in message_auto.sh_data" v-bind:key="sh_data.litefield">
                                    <p>【{{sh_data.name}} {{sh_data.title}}】
                                        <el-checkbox></el-checkbox>
                                    </p>
                                    <p style="text-indent: 25px;">{{sh_data.popularization}}</p>
                                    <p style="text-indent: 25px;">{{sh_data.propose}}</p>
                                </div>
                            </div>
                            <div style="width: 98%;padding: 1%" v-if="message_auto.xdt" >
                                <p>【{{message_auto.xdt.name}} {{message_auto.xdt.title}}】
                                    <el-checkbox></el-checkbox>
                                </p>
                                <p style="text-indent: 25px;">{{message_auto.xdt.popularization}}</p>
                                <p style="text-indent: 25px;">{{message_auto.xdt.propose}}</p>
                            </div>

                        </div>
                        <div>
                            <el-divider> <span>疾病结论</span></el-divider>
                            <div class="el-textarea" style="margin-top: 10px;max-height: 300px;overflow-y: auto;">
                                <div style="width: 98%;padding: 1%" v-for="selitem in selDisData" v-bind:key="selitem.id">
                                   <p>【{{selitem.title}}】
                                       <el-button type="info" size="mini" icon="el-icon-delete" @click="delSelDis(selitem)"></el-button>
                                   </p>
                                   <p style="text-indent: 25px;">{{selitem.description}}</p>
                                   <p style="text-indent: 25px;">{{selitem.advise}}</p>
                               </div>
                            </div>
                        </div>

                        <el-row class="text-right" style="margin-top: 20px">
                            <el-button type="primary" @click="selDisease">选择疾病</el-button>
                            <el-button type="primary">保存</el-button>
                        </el-row>
                    </el-card>
                </div>

                <el-dialog
                        title=""
                        :visible.sync="diseaseDialogVisible"
                        width="50%"
                        highlight-current-row
                >
                    <span>
                        <div slot="header" style="margin-bottom: 20px;" class="clearfix text-left">
                             <el-form :inline="true" :model="disFormInline" class="demo-form-inline">
                                <el-button  size="small" type="primary"  @click="handleEditDisease('')" icon="el-icon-plus" style="margin-right: 10px" title="添加疾病">添加疾病</el-button>

                                <el-form-item label="拼音码">
                                    <el-input   size="mini" v-model="disFormInline.pinyin" placeholder="拼音码"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button  size="mini" type="primary" @click="onDisSearch">查询</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                     <template>
                        <el-table
                                :data="disease_list"
                                border
                                style="width: 100%">
                            <el-table-column
                                    prop="title"
                                    label="疾病名称"
                                    >
                            </el-table-column>
                            <el-table-column
                                    prop="description"
                                    label="描述"
                                    title="description"
                                    width="400"
                                    >
                                <template slot-scope="scope">
                                    <span style="width:400px; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;"
                                    >
                                        {{scope.row.description}}
                                    </span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                    label="操作">
                                <template slot-scope="scope">
                                    <el-button
                                            type="primary"
                                            size="mini"
                                            @click="handleEditDisease( scope.row)">编辑</el-button>
                                    <el-button
                                        type="primary"
                                        size="mini"
                                        @click="handleSelDisease( scope.row)">选择</el-button>

                                </template>
                            </el-table-column>
                        </el-table>
                        <el-pagination
                                style="margin-top: 20px;text-align: center"
                                background
                                layout="prev, pager, next"
                                :total="dtotal"
                                :page-size="dpageSize"
                                @current-change="dhandleChange"
                        >
                        </el-pagination>
                    </template>

                </span>

                </el-dialog>
                <el-dialog
                        title=""
                        :visible.sync="editDisDialogVisible"
                        width="40%"
                >
                <span>
                    <el-form v-model="sub_dis_data.id"   label-width="80px" >
                          <el-form-item  label="疾病名称">
                            <el-input  v-model="sub_dis_data.title"></el-input>
                          </el-form-item>
                          <el-form-item label="拼音码">
                            <el-input :disabled="disabled" v-model="sub_dis_data.pinyin"></el-input>
                          </el-form-item>
                          <el-form-item label="疾病内容">
                             <el-input
                                     v-model="sub_dis_data.description"
                                     type="textarea"
                                     :rows="4"
                                     placeholder="请输入内容"
                             ></el-input>
                          </el-form-item>
                          <el-form-item label="意见建议">
                             <el-input
                                     v-model="sub_dis_data.advise"
                                     type="textarea"
                                     :rows="4"
                                     placeholder="请输入内容"
                             ></el-input>
                          </el-form-item>
                    </el-form>
                </span>
                    <span slot="footer" class="dialog-footer">
                        <el-button @click="editDisDialogVisible = false">取 消</el-button>
                        <el-button type="primary" @click="onDisSubmit">确 定</el-button>
                     </span>
                </el-dialog>
            </el-main>
            <el-aside style="width: 38%;">
                <div class="el-aside_con">
                    <div class="jstj">
                        <el-form :inline="true"  v-model="check_user_id" :model="formInline" class="demo-form-inline">
                            <div class="jstj_search">
                                <el-form-item label="检查序号">
                                    <el-input style="width: 150px"  v-model="formInline.check_number"   placeholder="检查序号"></el-input>
                                </el-form-item>
                                <el-form-item label="身份证">
                                    <el-input style="width: 150px"  v-model="formInline.card_id" placeholder="身份证"></el-input>
                                </el-form-item>
                                <el-form-item label="姓名">
                                    <el-input style="width: 150px"  v-model="formInline.real_name"  placeholder="姓名"></el-input>
                                </el-form-item>
                            </div>
                            <div class="jstj_search">
                                <el-form-item label="打印状态">
                                    <el-select   placeholder="请选择打印状态" v-model="formInline.is_print">
                                        <el-option label="全部" value=""></el-option>
                                        <el-option label="未打印" :value="0"></el-option>
                                        <el-option label="已打印" :value="1"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="机构">
                                    <el-select v-model="formInline.searche_abridge"  style="width:100%;"  placeholder="请选择机构">
                                        <el-option label="全部" value=""></el-option>
                                        <el-option
                                                v-for="item in orange_list"
                                                :key="item.abridge"
                                                :label="item.name"
                                                :value="item.abridge"/>
                                    </el-select>
                                </el-form-item>
                            </div>
                            <div class="jstj_search">
                                <el-form-item label="检查日期" >
                                    <el-date-picker
                                            value-format="yyyy-MM-dd"
                                            v-model="formInline.date"
                                            type="daterange"
                                            range-separator="至"
                                            start-placeholder="开始日期"
                                            end-placeholder="结束日期">
                                    </el-date-picker>
                                </el-form-item>
                            </div>
                            <el-form-item class="">
                                <el-button type="primary" size="small"  @click="onSearch()"  icon="el-icon-search">查询</el-button>
                            </el-form-item>
                        </el-form>
                      <div v-loading="loading">
                          <div class="text-right" style="margin-bottom: 10px">
                              <el-button  size="small" type="primary" icon="el-icon-printer" title="打印">打印</el-button>
                              <el-button size="small" type="primary" icon="el-icon-download" title="导出">导出</el-button>
                          </div>
                          <el-table
                                  :data="tableData"
                                  @row-click="RowClick"
                                  border
                                  highlight-current-row
                                  style="width: 100%">
                              <el-table-column
                                      prop="check_number"
                                      label="检查号"
                                     >
                                  <template slot-scope="scope">
                                      <span style="margin-left: 10px">{{ scope.row.check_number }}</span>
                                  </template>
                              </el-table-column>

                              <el-table-column
                                      prop="real_name"
                                      label="姓名"
                                      width="80"
                                      >
                              </el-table-column>
                                  <el-table-column
                                          prop="card_id"
                                          label="身份证"
                                          width="180"
                                          >
                                  </el-table-column>
                              <el-table-column
                                      prop="create_time"
                                      label="检查日期"
                                      width="180"
                                    >
                              </el-table-column>
                              <el-table-column
                                      prop="is_print"
                                      label="状态"
                                      width="100"
                              >
                                  <template slot-scope="scope">
                                      <el-button size="mini" type="primary" v-if="scope.row.is_print == 1">已打印</el-button>
                                      <el-button size="mini" type="info" v-else>未打印</el-button>
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
                    </div>
                </div>
            </el-aside>
        </el-container>
    </div>
</template>

<script>
    import {ajax_get, ajax_post} from "../../assets/js/ajax";
    import config from '../../config'
    export default {
        name: "check",
        data(){
            return {
                circleUrl: "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png",
                xdtUrl:"",
                srcList: [],
                formInline: {
                    card_id: '',
                    check_number: '',
                    real_name: '',
                    is_print:"",
                    sex:"",
                    date:"",
                    searche_abridge:''
                },
                comData:{
                    sg:"",
                    tz:"",
                    bmi:"",
                    zcxyss:"",
                    zcxysz:"",
                    ycxyss:"",
                    ycxysz:"",
                    g_zysl:"",
                    g_yysl:"",
                    g_zyjz:"",
                    g_yyjz:"",
                    ml:"",
                    G_YW:"",
                    message:""
                },
                qcData:{
                    xcg_message:"",
                    ncg_message:"",
                    sh_message:"",
                },
                xdtData:{
                    message:"",
                },
                checkList:{
                    check_number:""  ,
                    real_name:""  ,
                    age:""  ,
                    card_id:""  ,
                },
                tableData:[],
                default_com_data:{},
                default_xdt_data:{},
                default_qc_data:{},
                total:0,
                pageSize:10,
                loading:false,
                check_user_id:"",
                orange_list:'',
                diseaseDialogVisible:false,
                editDisDialogVisible:false,
                disease_list:[],
                dtotal:0,
                dpageSize:10,
                disFormInline:{
                    pinyin:''
                },
                sub_dis_data:{
                    title:'',
                    pinyin:'',
                    description:'',
                    advise:'',
                    id:''
                },
                disabled:false,
                selDisData:[],
                message_auto:{},
                checked:false
            }
        },

        created() {
            this.default_com_data = this.comData;
            this.default_xdt_data = this.xdtData;
            this.default_qc_data = this.qcData;
            this.getList(this,1);
            this.getOrgan();
        },
        methods:{
            handleChange(p) {
                this.getList(this,p);
            },
            onSearch(){
                this.getList(this,1);
            },
            dhandleChange(p) {
                this.getList(this,p);
            },
            getOrgan(){

                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/Organ/get_organ",
                    'headers':{
                        'X-Token': x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        if((res.data).length == 0){
                            this.orange_list = ''
                        }else{
                            this.orange_list = res.data
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
            handleEditDisease(item){
                if(item){
                    this.sub_dis_data = item
                    this.disabled = true
                }else{
                    this.sub_dis_data = {
                        title:'',
                        pinyin:'',
                        description:'',
                        advise:'',
                        id:''
                    }
                }
                this.editDisDialogVisible = true
            },
            handleSelDisease(item){
                this.selDisData.push(item)
            },
            delSelDis(item){
                console.log(item);
                let index = this.selDisData.indexOf(item)
                console.log(index)
                if (index !== -1) {
                    this.selDisData.splice(index, 1)
                }
            },
            onDisSearch(){
                this.getDisease(this,1);
            },
            getDisease(that,p){
                let x_token = this.$cookies.get('X-Token');
                ajax_get({
                    'url':"/admin/disease/index",
                    'data':{
                        p:p,
                        page_num:that.dpageSize,
                        pinyin:this.disFormInline.pinyin
                    },
                    'headers':{
                        'X-Token': x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        if((res.data).length == 0){
                            that.dtotal = 0
                            that.disease_list = []
                        }else{

                            that.dtotal = parseInt(res.data.total)
                            that.disease_list = res.data.list
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
            RowClick(raw ){
                this.check_user_id = raw.id
                this.checkList = {
                    check_number:raw.check_number  ,
                    real_name:raw.real_name ,
                    age:raw.age  ,
                    card_id:raw.card_id  ,
                }
                let x_token = this.$cookies.get('X-Token');
                this.loading = true;
                ajax_post({
                    'url':"/admin/type_in/get_data_info",
                    'data':{
                        id:raw.id,
                        project:'check'
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    this.loading = false;
                    if(res.code == 0){
                        if((res.data).length == 0){
                            this.comData = {}
                            this.xdtData = {}
                            this.qcData = {}
                        }else{
                            const data = res.data;
                            console.log(data.com_data);
                            if(data.com_data){
                                this.comData = data.com_data
                            }else{
                                this.comData = {}
                            }
                            if(data.message_auto){
                                this.message_auto= data.message_auto
                            }else{
                                this.message_auto= {}
                            }

                            if(data.qc_data){
                                let qc_message_arr = ''
                                if(data.qc_data.message){
                                     qc_message_arr = JSON.parse(data.qc_data.message);
                                }
                                this.qcData.xcg_message = qc_message_arr.xcg_message?qc_message_arr.xcg_message:''
                                this.qcData.ncg_message = qc_message_arr.ncg_message?qc_message_arr.ncg_message:''
                                this.qcData.sh_message = qc_message_arr.sh_message?qc_message_arr.sh_message:''
                            }else{
                                this.qcData = {}
                            }
                            if(data.xdt_data){
                                this.xdtData = {
                                    'message': data.xdt_data.message
                                }
                                console.log(data.evaautograph)
                                if(data.evaautograph){
                                    let evaautograph = config.domain+data.evaautograph
                                    console.log(evaautograph);
                                    this.xdtUrl = evaautograph
                                    this.srcList = [evaautograph]
                                }else{
                                    this.xdtUrl = ''
                                    this.srcList = []
                                }
                            }else{
                                this.xdtData = {}
                                this.xdtUrl = ''
                                this.srcList = []
                            }
                        }
                    }else if(res.code == 100){
                        this.$router.push('/login');
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    this.loading = false;
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })


            },
            getList(that,p){
                let x_token = this.$cookies.get('X-Token');

                let date = this.formInline.date;
                let date1 = ''
                let date2 = ''
                if(date){
                    date1 = date[0]
                    date2 = date[1]
                }
                this.loading = true;
                ajax_post({
                    'url':"/admin/type_in/get_data",
                    'data':{
                        project:"check",
                        p:p,
                        page_num:that.pageSize,
                        card_id:this.formInline.card_id,
                        check_number:this.formInline.check_number,
                        real_name:this.formInline.real_name,
                        check_status:this.formInline.is_print,
                        abridge:this.formInline.searche_abridge,
                        sex:this.formInline.sex,
                        date1:date1,
                        date2:date2,
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    console.log(res);
                    this.loading = false;
                    if(res.code == 0){
                        if(res.data.total >0){
                            that.total = parseInt(res.data.total)
                            that.tableData = res.data.list
                        }else{
                            that.total = 0
                            that.tableData = []
                        }
                    }else if(res.code == 100){
                        this.$router.push('/login');
                    }else{
                        this.$message.error(res.message)
                    }
                }).catch(err=>{
                    this.loading = false;
                    console.log(err);
                    this.$message.error('请求错误')
                    return false
                })
            },
            selDisease(){
                this.diseaseDialogVisible = true
                this.getDisease(this,1);
            },
            onDisSubmit(){
                let x_token = this.$cookies.get('X-Token');
                ajax_post({
                    'url':"/admin/disease/edit",
                    'data':{
                        'id':this.sub_dis_data.id,
                        'title':this.sub_dis_data.title,
                        'pinyin':this.sub_dis_data.pinyin,
                        'description':this.sub_dis_data.description,
                        'advise':this.sub_dis_data.advise,
                        'biochemistry_notice':this.sub_dis_data.biochemistry_notice,
                    },
                    'headers':{
                        'X-Token':  x_token
                    }
                }).then(res=>{
                    if(res.code == 0){
                        this.$message.success(res.message)
                        this.diseaseDialogVisible = false
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
    .el-aside {
        /*background-color: #D3DCE6;*/
        color: #333;
        /*text-align: center;*/
        height: auto;
    }

    .el-main {
        background-color: #E9EEF3;
        color: #333;
        text-align: left;
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
        height: 500px;
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

   .xdt .el-textarea{
        width: 98%;
        padding:1%;
        min-height: 120px;
        border: 1px solid #DCDFE6;
        font-size: 13px;
        line-height: 18px;
    }





</style>