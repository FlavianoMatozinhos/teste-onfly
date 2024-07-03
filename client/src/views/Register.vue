<template>
    <div>
        <div v-if="alertMessage" :class="alertType">
            {{ alertMessage }}
        </div>
        <form @submit.prevent="register">
            <label>Name</label>
            <input type="text" class="form-control" v-model="form.name" placeholder="Name" required />
    
            <label>Email address</label>
            <input type="email" v-model="form.email" placeholder="Email" required />
    
            <label for="password">Password</label>
            <input type="password" v-model="form.password" placeholder="Password" required />
    
            <button type="submit">Register</button>
        </form>
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
            alertType: '' // 'success' or 'error'
        };
        },
        methods: {
        async register() {
            if (this.form.password.length < 8) {
            this.alertMessage = 'A senha deve ter pelo menos 8 caracteres.';
            this.alertType = 'error';
            return;
            }
    
            try {
            const response = await axios.post('http://localhost:8000/api/register', this.form);
            console.log('Registered successfully:', response.data);
            this.alertMessage = 'Registrado com sucesso!';
            this.alertType = 'success';
            setTimeout(() => {
                this.$router.push({ name: 'Login' });
            }, 2000); // Redireciona após 2 segundos (opcional)
            } catch (error) {
                console.log(error.response)
            if (error.response.status == 500) {
                this.alertMessage = 'Email já cadastrado.';
            } else {
                this.alertMessage = 'Erro ao registrar. Tente novamente mais tarde.';
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
  