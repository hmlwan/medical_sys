import axios from 'axios'
import config from '../../config.js'
axios.defaults.withCredentials = true
const domain = config.domain

export function ajax_get(opts) {
    const url = opts.url
    let params = opts.data
    return new Promise((resolve, reject)=>{
        const instance = axios.create({
            baseURL:domain,
        })
        //响应拦截器
        instance.interceptors.response.use(res=>{
            console.log(res);
            return res.data
        },err=>{
            console.log(err);
        })
        instance({
            responseType:"json",
            url:url,
            method:'get',
            params:params
        }).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        })

    })
}
export function ajax_post(opts) {
    const url = opts.url
    console.log(config.domain);
    let params = opts.data
    return new Promise((resolve, reject)=>{
        const instance = axios.create({
            baseURL:domain,
        })
        //响应拦截器
        instance.interceptors.response.use(res=>{
            console.log(res);
            return res.data
        },err=>{
            console.log(err);
        })
        instance({
            responseType:"json",
            url:url,
            method:'post',
            params:params
        }).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        })

    })
}


