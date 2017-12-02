<template lang="html">
  <nav class="navbar is-transparent">
  <div class="navbar-brand">
    <a class="navbar-item" href="https://bulma.io">
      <img src="/images/worsworthelt.svg" alt="Resource Server" width="150" height="50">
    </a>
    <div class="navbar-burger burger" data-target="navbarExampleTransparentExample" @click="showMenu = !showMenu">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <div id="navbarExampleTransparentExample" class="navbar-menu" :class="{'is-active': showMenu}">
    <div class="navbar-start">
      <router-link class="navbar-item"  active-class="is-active" :to="{ name: 'home'}" exact>
        Home
      </router-link>
      <router-link class="navbar-item"  active-class="is-active" :to="{ name: 'exercises'}" exact>
        Exercises
      </router-link>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
          <p class="control">
            <a class="bd-tw-button button" data-social-network="Twitter" data-social-action="tweet" data-social-target="http://localhost:4000" target="_blank" href="https://twitter.com/intent/tweet?text=Bulma: a modern CSS framework based on Flexbox&amp;hashtags=bulmaio&amp;url=http://localhost:4000&amp;via=jgthms">
              <span class="icon">
                <i class="fa fa-user-plus"></i>
              </span>
              <span>
                Register
              </span>
            </a>
          </p>
          <p class="control">
            <a class="button is-primary" @click="logout" v-if="loginStatus">
              <span class="icon">
                <i class="fa fa-sign-out"></i>
              </span>
              <span>Logout</span>
            </a>
            <a class="button is-primary" @click="login" v-else>
              <span class="icon">
                <i class="fa fa-sign-in"></i>
              </span>
              <span>Login</span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</nav>
</template>

<script>
export default {
  data: function(){
    return {
      showMenu: false
    }
  },
  computed: {
    loginStatus: function(){
      return this.$store.state.loginStatus;
    }
  },
  methods: {
    login: function(){
      window.location.href = '/login';
    },
    logout: function(){
      axios.post('api/auth/logout')
      .then(response => {
        console.log(response);
        this.$store.commit('setLoginStatus', false);
      })
      .catch(error => {
        console.log(error);
      })
    }
  }
}
</script>

<style lang="css">
</style>
