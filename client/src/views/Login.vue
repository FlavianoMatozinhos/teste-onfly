<template>
  <div class="login-container d-flex justify-content-center align-items-center vh-60">
    <div class="login-box p-5 shadow rounded">
      <h2 class="text-center mb-4">Login</h2>
      <div v-if="alertMessage" :class="`alert ${alertType}`">
        {{ alertMessage }}
      </div>
      <form @submit.prevent="login">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input v-model="formData.email" type="email" id="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Senha</label>
          <input v-model="formData.password" type="password" id="password" class="form-control" placeholder="Senha" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
    </div>
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
        this.alertType = 'alert-success';
        setTimeout(() => {
          this.$router.push('/');
        }, 2000);
      } catch (error) {
        this.alertMessage = 'Email ou Senha inválida.';
        this.alertType = 'alert-danger';
      }
    }
  }
};
</script>

<style scoped>
.login-container {
  background-color: #f8f9fa;
}

.login-box {
  background-color: #ffffff;
  border-radius: 10px;
}

.alert {
  padding: 10px;
  margin-bottom: 15px;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
}
</style>
