<template>
    <div class="register-container d-flex justify-content-center align-items-center vh-60">
      <div class="register-box p-5 shadow rounded">
        <h2 class="text-center mb-4">Registrar</h2>
        <div v-if="alertMessage" :class="`alert ${alertType}`">
          {{ alertMessage }}
        </div>
        <form @submit.prevent="register">
          <div class="mb-3">
            <label for="name" class="form-label">Nome:</label>
            <input v-model="form.name" type="text" id="name" class="form-control" placeholder="Nome" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input v-model="form.email" type="email" id="email" class="form-control" placeholder="Email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Senha:</label>
            <input v-model="form.password" type="password" id="password" class="form-control" placeholder="Senha" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Registrar</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: "Register",
    data() {
      return {
        form: {
          name: '',
          email: '',
          password: ''
        },
        alertMessage: '',
        alertType: ''
      };
    },
    methods: {
      async register() {
        if (this.form.password.length < 8) {
          this.alertMessage = 'A senha deve ter pelo menos 8 caracteres.';
          this.alertType = 'alert-danger';
          return;
        }
  
        try {
          const response = await axios.post('http://localhost:8000/api/register', this.form);
          console.log('Registered successfully:', response.data);
          this.alertMessage = 'Registrado com sucesso!';
          this.alertType = 'alert-success';
          setTimeout(() => {
            this.$router.push({ name: 'Login' });
          }, 2000);
        } catch (error) {
          console.log(error.response);
          if (error.response.status === 500) {
            this.alertMessage = 'Email já cadastrado.';
          } else {
            this.alertMessage = 'Erro ao registrar. Tente novamente mais tarde.';
          }
          this.alertType = 'alert-danger';
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .register-container {
    background-color: #f8f9fa;
  }
  
  .register-box {
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
  