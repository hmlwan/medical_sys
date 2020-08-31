import Vue from 'vue'
import VueRouter from "vue-router"

const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location, onResolve, onReject) {
    if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
    return originalPush.call(this, location).catch(err => err)
}
Vue.use(VueRouter)
const Login = ()=>import('../views/Login/index.vue')
const Home = ()=>import('../views/Home/index.vue')
const HomeTypeIn = ()=>import('../views/Home/type_in.vue')
const User = ()=>import('../views/Home/user.vue')
const Agency = ()=>import('../views/Home/agency.vue')
const Temp = ()=>import('../views/Home/temp.vue')
const Temp1 = ()=>import('../views/Home/temp1.vue')
const Measure = ()=>import('../views/Home/measure.vue')
const EegConf = ()=>import('../views/Home/eeg_conf.vue')
const BasicConf = ()=>import('../views/Home/basic_conf.vue')
const routes = [
    {
        path:'/',
        component:Login
    },
    {
        path:'/home',
        component:Home,
        children:[
            {
                path:'',
                component:HomeTypeIn,
                name:"type_in"
            },
            {
                path:'type_in',
                component:HomeTypeIn,
                name:"type_in"
            },
            {
                path:'user',
                component:User,
                name:"user"
            },
            {
                path:'agency',
                component:Agency,
                name:"agency"
            },
            {
                path:'temp',
                component:Temp,
                name:"temp"
            },
            {
                path:'temp1',
                component:Temp1,
                name:"temp1"
            },
            {
                path:'measure',
                component:Measure,
                name:"measure"
            },
            {
                path:'eeg_conf',
                component:EegConf,
                name:"eeg_conf"
            },
            {
                path:'basic_conf',
                component:BasicConf,
                name:"basic_conf"
            }
        ]
    },
]
const router = new VueRouter({
    routes,
    mode:'history'
    }
)
export default router















