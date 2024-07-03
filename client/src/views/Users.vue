<template>
    <div class="users">
      <h2>Gestão de Usuários</h2>
  
      <!-- Formulário para criar ou editar usuário -->
      <form @submit.prevent="editingUser ? updateUser() : createUser()">
        <input v-model="formData.name" type="text" placeholder="Nome" required>
        <input v-model="formData.email" type="email" placeholder="Email" required>
        <input v-model="formData.password" type="password" placeholder="Senha" required>
  
        <button type="submit">{{ editingUser ? 'Atualizar' : 'Criar' }} Usuário</button>
      </form>
  
      <!-- Tabela para listar usuários -->
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <button @click="editUser(user)">Editar</button>
              <button @click="deleteUser(user.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        users: [],
        formData: { name: '', email: '', password: '' },
        editingUser: null
      };
    },
    created() {
      this.loadUsers();
    },
    methods: {
      async loadUsers() {
        try {
          const response = await this.$http.get('/users');
          this.users = response.data;
        } catch (error) {
          console.error('Erro ao carregar usuários:', error);
        }
      },
      async createUser() {
        try {
          const response = await this.$http.post('/users', this.formData);
          this.users.push(response.data);
          this.resetForm();
        } catch (error) {
          console.error('Erro ao criar usuário:', error);
        }
      },
      async updateUser() {
        try {
          await this.$http.post(`/users/${this.editingUser.id}`, this.formData);
          const index = this.users.findIndex(user => user.id === this.editingUser.id);
          if (index !== -1) {
            this.users.splice(index, 1, { ...this.formData, id: this.editingUser.id });
          }
          this.resetForm();
        } catch (error) {
          if (error.response.status === 422 && error.response.data.errors && error.response.data.errors.email) {
            console.error('Erro ao atualizar usuário:', error.response.data.errors.email[0]);
            // Exemplo: Mostrar uma mensagem de erro ao usuário
            alert('Erro ao atualizar usuário: ' + error.response.data.errors.email[0]);
          } else {
            console.error('Erro ao atualizar usuário:', error);
          }
        }
      },
      async deleteUser(userId) {
        try {
          await this.$http.delete(`/users/${userId}`);
          this.users = this.users.filter(user => user.id !== userId);
        } catch (error) {
          console.error('Erro ao excluir usuário:', error);
        }
      },
      editUser(user) {
        this.editingUser = user;
        this.formData.name = user.name;
        this.formData.email = user.email;
      },
      resetForm() {
        this.formData = { name: '', email: '', password: '' };
        this.editingUser = null;
      }
    }
  };
  </script>
  
  <style scoped>
  .users {
    text-align: center;
    margin-top: 20px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  
  th {
    background-color: #f2f2f2;
  }
  </style>
  