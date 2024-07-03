<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="login">
      <input v-model="formData.email" type="email" placeholder="Email" required>
      <input v-model="formData.password" type="password" placeholder="Senha" required>
      <button type="submit">Entrar</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formData: {
        email: '',
        password: ''
      }
    };
  },
  methods: {
    async login() {
      try {
        await this.$store.dispatch('login', this.formData);
        this.$router.push('/');
      } catch (error) {
        console.error('Erro durante o login:', error);
      }
    }
  }
};
</script>
