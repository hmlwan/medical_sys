<template>
    <div>
        <nav-top></nav-top>
        <router-view></router-view>
    </div>
</template>
<script>
    import NavTop  from '../../components/Nav/top'
    export default {
        name: "index",
        components:{
            NavTop
        }
    }
</script>
<style scoped>


</style>