<template>
  <div class="home">
    <h2>Home</h2>
    <button @click="goToUsers">Gerenciar Usuários</button>
  </div>
</template>

<script>
export default {
  methods: {
    goToUsers() {
      this.$router.push('/users'); // Navega para a rota '/users'
    }
  }
};
</script>

<style scoped>
  .home {
    text-align: center;
    margin-top: 20px;
  }
</style>