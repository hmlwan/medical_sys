<template>
    <div>
        <nav-top  @is_open="isOpen" :organ-name="oragnName"></nav-top>
        <router-view @organ_name="organName"   :is-open="isOpenOrgan"></router-view>
    </div>
</template>
<script>
    import NavTop  from '../../components/Nav/top'
    export default {
        name: "index",
        data(){
            return{
                isOpenOrgan:false,
                oragnName:false,
            }
        },
        components:{
            NavTop
        },
        methods:{
            isOpen(data){
                console.log('isOpen:'+data);
                this.isOpenOrgan = data
            },
            organName(data){
                console.log('organName:'+data);
                this.oragnName = data
            }

        }
    }
</script>
<style scoped>


</style>