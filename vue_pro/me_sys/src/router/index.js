import Vue from 'vue'
import VueRouter from "vue-router"


Vue.use(VueRouter)


const routes = [
    {
        path:'/',
        component:()=>import('../views/Login/index.vue')
    },
    {
        path:'/home',
        component:()=>import('../views/Home/index.vue')

    }
]
const router = new VueRouter({
    routes,
    mode:'history'
    }
)
export default router















