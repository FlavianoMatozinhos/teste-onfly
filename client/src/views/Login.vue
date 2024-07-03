<template>
  <div>
    <div v-if="alertMessage" :class="alertType">
      {{ alertMessage }}
    </div>
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
      },
      alertMessage: '',
      alertType: ''
    };
  },
  methods: {
    async login() {
      try {
        await this.$store.dispatch('login', this.formData);
        this.alertMessage = 'Login realizado com sucesso!';
        this.alertType = 'success';
        setTimeout(() => {
          this.$router.push('/');
        }, 2000);
      } catch (error) {
        if (error) {
          this.alertMessage = 'Email ou Senha inválida.';
        }
        this.alertType = 'error';
      }
    }
  }
};
</script>

<style scoped>
.success {
  background-color: #d4edda;
  color: #155724;
  padding: 10px;
  margin-top: 10px;
}

.error {
  background-color: #f8d7da;
  color: #721c24;
  padding: 10px;
  margin-top: 10px;
}
</style>
