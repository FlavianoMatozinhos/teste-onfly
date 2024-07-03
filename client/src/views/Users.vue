<template>
  <div class="users">
    <h2>Gestão de Usuários</h2>

    <form @submit.prevent="editingUser ? updateUser() : createUser()">
      <input v-model="formData.name" type="text" placeholder="Nome" required>
      <input v-model="formData.email" type="email" placeholder="Email" required>
      <input v-model="formData.password" type="password" placeholder="Senha" required>

      <button type="submit">{{ editingUser ? 'Atualizar' : 'Criar' }} Usuário</button>
    </form>

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
            <button @click="confirmDeleteUser(user.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="alertMessage" :class="['alert', alertType === 'error' ? 'alert-danger' : 'alert-success']">
      {{ alertMessage }}
    </div>
  </div>
</template>

  
<script>
export default {
  data() {
    return {
      users: [],
      formData: { name: '', email: '', password: '' },
      editingUser: null,
      alertMessage: '',
      alertType: '' // 'success' or 'error'
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
        this.alertMessage = 'Erro ao carregar usuários.';
        this.alertType = 'error';
      }
    },
    async createUser() {
      try {
        const response = await this.$http.post('/users', this.formData);
        this.users.push(response.data);
        this.resetForm();
        this.alertMessage = 'Usuário criado com sucesso!';
        this.alertType = 'success';
      } catch (error) {
        if (error.response && error.response.status === 422 && error.response.data.errors) {
          if (error.response.data.errors.password) {
            this.alertMessage = 'Erro ao criar usuário: ' + error.response.data.errors.password[0];
          } else if (error.response.data.errors.email) {
            this.alertMessage = 'Erro ao criar usuário: ' + error.response.data.errors.email[0];
          } else {
            this.alertMessage = 'Erro ao criar usuário.';
          }
        } else {
          this.alertMessage = 'Erro ao criar usuário.';
        }
        this.alertType = 'error';
      }
    },
    async updateUser() {
      try {
        await this.$http.put(`/users/${this.editingUser.id}`, this.formData);
        const index = this.users.findIndex(user => user.id === this.editingUser.id);
        if (index !== -1) {
          this.users.splice(index, 1, { ...this.formData, id: this.editingUser.id });
        }
        this.resetForm();
        this.alertMessage = 'Usuário atualizado com sucesso!';
        this.alertType = 'success';
      } catch (error) {
        if (error.response && error.response.status === 422 && error.response.data.errors) {
          if (error.response.data.errors.password) {
            this.alertMessage = 'Erro ao atualizar usuário: ' + error.response.data.errors.password[0];
          } else if (error.response.data.errors.email) {
            this.alertMessage = 'Erro ao atualizar usuário: ' + error.response.data.errors.email[0];
          } else {
            this.alertMessage = 'Erro ao atualizar usuário.';
          }
        } else {
          this.alertMessage = 'Erro ao atualizar usuário.';
        }
        this.alertType = 'error';
      }
    },
    async deleteUser(userId) {
      try {
        await this.$http.delete(`/users/${userId}`);
        this.users = this.users.filter(user => user.id !== userId);
        this.alertMessage = 'Usuário excluído com sucesso!';
        this.alertType = 'success';
      } catch (error) {
        this.alertMessage = 'Erro ao excluir usuário.';
        this.alertType = 'error';
      }
    },
    confirmDeleteUser(userId) {
      if (confirm('Tem certeza que deseja excluir este usuário?')) {
        this.deleteUser(userId);
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

.alert {
  margin-top: 20px;
  padding: 10px;
  border-radius: 5px;
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
