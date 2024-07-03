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
  import axios from 'axios';
  
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
          const response = await axios.post('http://localhost:8000/api/login', {
            email: this.formData.email,
            password: this.formData.password
          });
          // Se o login for bem-sucedido, você pode lidar com a resposta aqui
          console.log('Login bem-sucedido:', response.data);
          // Redirecionar para a página de home ou outra página após o login
          this.$router.push({ name: 'Home' });
        } catch (error) {
          // Se houver um erro no login, você pode lidar com isso aqui
          console.error('Erro ao fazer login:', error.response.data);
          // Exemplo de mostrar uma mensagem de erro ao usuário
          alert('Erro ao fazer login. Verifique suas credenciais.');
        }
      }
    }
  };
  </script>
  