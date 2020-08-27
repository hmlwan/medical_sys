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

        ]
    },
]
const router = new VueRouter({
    routes,
    mode:'history'
    }
)
export default router















