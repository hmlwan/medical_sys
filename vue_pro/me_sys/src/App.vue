<template>
  <div id="app">
    <keep-alive>
      <router-view></router-view>
    </keep-alive>
<!--    <LoginPage/>-->
  </div>
</template>

<script>
// import LoginPage from 'views/Login/index'
export default {
  name: 'App',
  components: {
    // LoginPage

  }
}
</script>
<style>
#app {

}
</style>
