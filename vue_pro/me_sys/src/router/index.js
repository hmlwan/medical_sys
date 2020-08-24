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
        component:()=>import('../views/Home/index.vue'),
        children:[{
            path:'type_in',
            component:()=>import('../views/Home/type_in.vue')
        }]
    },

]
const router = new VueRouter({
    routes,
    mode:'history'
    }
)
export default router















