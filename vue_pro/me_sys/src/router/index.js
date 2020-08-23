import Vue from 'vue'
import VueRouter from "vue-router"


Vue.use(VueRouter)


const routes = [
    {
        path:'/',
        component:()=>import('../views/Login/index.vue')

    },
    {
        path:'/picdown',
        component:()=>import('../components/login/index.vue')
    }
]

const router = new VueRouter({
    routes,
    mode:'history'
    }
)
export default router















