<template lang="html">
  <div class="">
    <nav-component></nav-component>
    <router-view></router-view>
  </div>
</template>

<script>
import Navbar from './Navbar.vue';

export default {
  components: {
      'nav-component': Navbar
  }
}
</script>

<style lang="scss">
</style>
