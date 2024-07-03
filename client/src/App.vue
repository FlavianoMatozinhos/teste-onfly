<template>
  <div id="app">
    <div id="nav">
      <router-link to="/">Home | </router-link>
      <router-link v-if="!loggedIn" to="/login">Login | </router-link>
      <router-link v-if="!loggedIn" to="/register">Register</router-link>
      <span v-if="loggedIn" @click="logout">Logout</span>
    </div>
    <router-view/>
  </div>
</template>

<script>
export default {
    methods: {
        logout() {
            this.$store.dispatch('logout').then(() => {
                this.$router.push({
                    name: 'Login'
                })
            })
        }
    },
    computed: {
        loggedIn() {
            return this.$store.getters.loggedIn
        }
    }
}

</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a, span {
  font-weight: bold;
  color: #2c3e50;
  text-decoration: underline;
}

span:hover {
  cursor: pointer;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>
