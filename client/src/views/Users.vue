<template>
  <div class="users">
    <h2>Gestão de Usuários</h2>

    <form @submit.prevent="editingUser ? updateUser() : createUser()">
      <input v-model="formData.name" type="text" placeholder="Nome" required>
      <input v-model="formData.email" type="email" placeholder="Email" required>
      <input v-model="formData.password" type="password" placeholder="Senha" :required="!editingUser">

      <div class="buttons">
        <button type="submit">{{ editingUser ? 'Atualizar' : 'Criar' }} Usuário</button>
        <button v-if="editingUser" type="button" @click="resetForm">Voltar</button>
      </div>
    </form>

    <table v-if="!editingUser" class="compact-table">
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
import axios from '../plugins/axios';

export default {
  data() {
    return {
      users: [],
      formData: { name: '', email: '', password: '' },
      editingUser: null,
      alertMessage: '',
      alertType: ''
    };
  },
  created() {
    this.loadUsers();
  },
  methods: {
    async loadUsers() {
      try {
        const response = await axios.get('/users');
        this.users = response.data.data;
      } catch (error) {
        this.alertMessage = 'Erro ao carregar usuários.';
        this.alertType = 'error';
      }
    },
    async createUser() {
      try {
        const response = await axios.post('/users', this.formData);
        this.users.push(response.data.data);
        this.resetForm();
        this.alertMessage = 'Usuário criado com sucesso!';
        this.alertType = 'success';
      } catch (error) {
        this.handleFormError(error);
      }
    },
    async updateUser() {
      try {
        const response = await axios.put(`/users/${this.editingUser.id}`, this.formData);
        const updatedUser = response.data;
        const index = this.users.findIndex(user => user.id === updatedUser.id);
        if (index !== -1) {
          this.users.splice(index, 1, updatedUser);
        }
        this.resetForm();
        this.alertMessage = 'Usuário atualizado com sucesso!';
        this.alertType = 'success';
        window.location.reload();
      } catch (error) {
        this.handleFormError(error);
      }
    },
    async deleteUser(userId) {
      try {
        await axios.delete(`/users/${userId}`);
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
    },
    handleFormError(error) {
      if (error.response && error.response.status === 422 && error.response.data.errors) {
        if (error.response.data.errors.password) {
          this.alertMessage = 'Erro ao processar usuário: ' + error.response.data.errors.password[0];
        } else if (error.response.data.errors.email) {
          this.alertMessage = 'Erro ao processar usuário: ' + error.response.data.errors.email[0];
        } else {
          this.alertMessage = 'Erro ao processar usuário.';
        }
      } else {
        this.alertMessage = 'Erro ao processar usuário.';
      }
      this.alertType = 'error';
    }
  }
};
</script>

<style scoped>
.users {
  text-align: center;
  margin-top: 20px;
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

input {
  margin: 0.5rem 0;
  padding: 0.5rem;
  width: 80%;
  max-width: 300px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.buttons {
  display: flex;
  justify-content: center;
  margin-top: 1rem;
}

.buttons button {
  margin: 0 0.5rem;
  padding: 0.5rem 1rem;
  font-size: 1rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.buttons button:first-of-type {
  background-color: #007bff;
  color: white;
}

.buttons button:last-of-type {
  background-color: #6c757d;
  color: white;
}

table {
  width: 80%;
  margin: auto;
  border-collapse: collapse;
  margin-top: 20px;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
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
