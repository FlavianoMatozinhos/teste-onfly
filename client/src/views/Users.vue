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
        users: [], // Lista de usuários
        formData: { name: '', email: '', password: '' }, // Dados do formulário
        editingUser: null // Usuário em edição (se houver)
      };
    },
    created() {
      this.loadUsers(); // Carrega usuários ao entrar na página
    },
    methods: {
      async loadUsers() {
        try {
          const response = await this.$http.get('/users'); // Altere para o endpoint correto
          this.users = response.data;
        } catch (error) {
          console.error('Erro ao carregar usuários:', error);
        }
      },
      async createUser() {
        try {
          const response = await this.$http.post('/users', this.formData); // Endpoint correto conforme definido no Laravel
          this.users.push(response.data); // Adiciona novo usuário à lista local
          this.resetForm(); // Limpa o formulário após criação
        } catch (error) {
          console.error('Erro ao criar usuário:', error);
        }
      },
      async updateUser() {
        try {
          await this.$http.put(`/users/${this.editingUser.id}`, this.formData); // Endpoint correto conforme definido no Laravel
          const index = this.users.findIndex(user => user.id === this.editingUser.id);
          if (index !== -1) {
            this.users.splice(index, 1, { ...this.formData, id: this.editingUser.id });
          }
          this.resetForm(); // Limpa o formulário após atualização
        } catch (error) {
          console.error('Erro ao atualizar usuário:', error);
        }
      },
      async deleteUser(userId) {
        try {
          await this.$http.delete(`/users/${userId}`); // Endpoint correto conforme definido no Laravel
          this.users = this.users.filter(user => user.id !== userId); // Remove usuário da lista local
        } catch (error) {
          console.error('Erro ao excluir usuário:', error);
        }
      },
      editUser(user) {
        // Preenche o formulário com os dados do usuário selecionado para edição
        this.editingUser = user;
        this.formData.name = user.name;
        this.formData.email = user.email;
        // Senha não é preenchida para edição por questões de segurança ou política de senha
      },
      resetForm() {
        // Reseta o formulário e o usuário em edição
        this.formData = { name: '', email: '', password: '' };
        this.editingUser = null;
      }
    }
  };
  </script>
  
  <style scoped>
  /* Estilos específicos para esta componente */
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
  