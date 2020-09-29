import Vue from 'vue'
import VueRouter from "vue-router"
import VueCookies from "vue-cookies"

const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location, onResolve, onReject) {
    if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
    return originalPush.call(this, location).catch(err => err)
}
Vue.use(VueRouter)
Vue.use(VueCookies)
const Login = ()=>import('../views/Login/index.vue')
const Home = ()=>import('../views/Home/index.vue')
const HomeCheck = ()=>import('../views/Home/check.vue') //总检
const HomeTypeIn = ()=>import('../views/Home/type_in.vue') //录入
const HomeGeneralCheck = ()=>import('../views/Home/general_check.vue') //一般检查
const HomeEcgCheck = ()=>import('../views/Home/ecg_check.vue') //心电图
const HomeUsCheck = ()=>import('../views/Home/us_check.vue') //超声波
const HomeRtCheck = ()=>import('../views/Home/rt_check.vue') //放射
const HomeQcCheck = ()=>import('../views/Home/qc_check.vue') //检验
const User = ()=>import('../views/Home/user.vue')
const Agency = ()=>import('../views/Home/agency.vue')
const Temp = ()=>import('../views/Home/temp.vue')
const Measure = ()=>import('../views/Home/measure.vue')
const BasicConf = ()=>import('../views/Home/basic_conf.vue')
const routes = [
    {
        path:'',
        name:'HomeTypeIn',
        component:Home,
        redirect:"/home"
    },
    {
        path:'/login',
        component:Login,
        name:'login'
    },
    {
        path:'/outlog',
        component:Login,
        name:'outlogin'
    },
    {
        path:'/home',
        component:Home,
        children:[
            {
                path:'check',
                component:HomeCheck,
                name:"check",
                meta:{
                    index_id:'1'
                }
            },
            {
                path:'type_in',
                component:HomeTypeIn,
                name:"type_in",
                meta:{
                    index_id:'1'
                }
            },
            {
                path:'general_check',
                component:HomeGeneralCheck,
                name:"general_check",
                meta:{
                    index_id:'1'
                }
            },{
                path:'qc_check',
                component:HomeQcCheck,
                name:"qc_check",
                meta:{
                    index_id:'1'
                }
            },
            {
                path:'ecg_check',
                component:HomeEcgCheck,
                name:"ecg_check",
                meta:{
                    index_id:'1'
                }
            },
            {
                path:'us_check',
                component:HomeUsCheck,
                name:"us_check",
                meta:{
                    index_id:'1'
                }
            }, {
                path:'rt_check',
                component:HomeRtCheck,
                name:"rt_check",
                meta:{
                    index_id:'1'
                }
            },
            {
                path:'user',
                component:User,
                name:"user",
                meta:{
                    index_id:'2'
                }
            },
            {
                path:'agency',
                component:Agency,
                name:"agency",
                meta:{
                    index_id:'3'
                }
            },
            {
                path:'temp',
                component:Temp,
                name:"temp",
                meta:{
                    index_id:'4'
                }
            },
            {
                path:'portrait_temp',
                component:Temp,
                name:"portrait_temp",
                meta:{
                    index_id:'5'
                }
            },
            {
                path:'measure',
                component:Measure,
                name:"measure",
                meta:{
                    index_id:'6'
                }
            },
            {
                path:'basic_conf',
                component:BasicConf,
                name:"basic_conf",
                meta:{
                    index_id:'7_2'
                }
            },
            {
                path:'ecg_conf',
                component:Temp,
                name:"ecg_conf",
                meta:{
                    index_id:'7_3'
                }
            },

        ]
    },
]
const router = new VueRouter({
    routes,
    mode:'history'
    }
)


router.beforeEach(function (to, from, next) {


    const path = to.path.replace('/','');
    console.log(path);
    if(path === 'login'){
        next()
    }else{
        let x_token =  Vue.$cookies.get('X-Token')
        if(!x_token){
            next('/login')
        }else{
            next()
        }
    }
})

export default router















