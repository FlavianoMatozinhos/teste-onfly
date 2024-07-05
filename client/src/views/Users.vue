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
    <div v-if="alertMessage" :class="['alert', alertType === 'error' ? 'alert-danger' : 'alert-success']">
      {{ alertMessage }}
    </div>
    <table v-if="!editingUser" class="compact-table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Email</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in paginatedUsers" :key="user.id">
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <button class="btn btn-warning btn-sm mx-1" @click="editUser(user)">Editar</button>
            <button class="btn btn-danger btn-sm mx-1" @click="confirmDeleteUser(user.id, loggedInUserId)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!editingUser" class="pagination mb-4">
      <button :disabled="currentPage === 1" @click="currentPage--">Anterior</button>
      <span>Página {{ currentPage }} de {{ totalPages }}</span>
      <button :disabled="currentPage === totalPages" @click="currentPage++">Próxima</button>
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
        alertType: '',
        currentPage: 1,
        itemsPerPage: 10,
        loggedInUserId: null
      };
    },
    computed: {
      totalPages() {
        return Math.ceil(this.users.length / this.itemsPerPage);
      },
      paginatedUsers() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.users.slice(start, end);
      }
    },
    created() {
      this.loadUsers();
      this.getLoggedInUserId();
    },
    methods: {
      async loadUsers() {
        try {
          const response = await axios.get('/users');
          this.users = response.data.data;
        } catch (error) {
          this.alertMessage = 'Erro ao carregar usuários.';
          this.alertType = 'error';
          this.setAutoCloseAlert();
        }
      },
      async createUser() {
        if (!this.formData.password || this.formData.password.length < 8) {
          this.alertMessage = 'Campo de senha deve ter pelo menos 8 caracteres.';
          this.alertType = 'error';
          this.setAutoCloseAlert();
          return;
        }

        try {
          const response = await axios.post('/users', this.formData);
          this.users.push(response.data.data);
          this.resetForm();
          this.alertMessage = 'Usuário criado com sucesso!';
          this.alertType = 'success';
          this.setAutoCloseAlert();
        } catch (error) {
          this.handleFormError(error);
        }
      },
      async updateUser() {
        if (!this.formData.password || this.formData.password.length < 8) {
          this.alertMessage = 'Campo de senha deve ter pelo menos 8 caracteres.';
          this.alertType = 'error';
          this.setAutoCloseAlert();
          return;
        }
        if (this.formData.email !== this.editingUser.email) {
          const emailExists = this.users.some(user => user.email === this.formData.email);
          if (emailExists) {
            this.alertMessage = 'Já existe um usuário com este email.';
            this.alertType = 'error';
            this.setAutoCloseAlert();
            return;
          }
        }

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
          if (userId == this.loggedInUserId) {
            this.alertMessage = 'Não é possivel deletar o usuario logado';
            this.alertType = 'error';
            this.setAutoCloseAlert();
          } else {
            this.users = this.users.filter(user => user.id !== userId);
            this.alertMessage = 'Usuário excluído com sucesso!';
            this.alertType = 'success';
            this.setAutoCloseAlert();
          }
        } catch (error) {
          this.alertMessage = 'Erro ao excluir usuário.';
          this.alertType = 'error';
          this.setAutoCloseAlert();
        }
      },
      confirmDeleteUser(userId, loggedInUserId) {
          if (confirm('Tem certeza que deseja excluir este usuário?')) {
            if (userId == loggedInUserId) {
              this.alertMessage = 'Não é possivel deletar o usuario logado';
              this.alertType = 'error';
              this.setAutoCloseAlert();
            } else {
              this.deleteUser(userId);
            }
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
            this.alertMessage = 'Erro ao processar usuário: O campo de senha deve ter pelo menos 8 caracteres.';
            this.setAutoCloseAlert();
          } else if (error.response.data.errors.email) {
            this.alertMessage = 'Erro ao processar usuário: Já existe um usuário com este email.';
            this.setAutoCloseAlert();
          } else {
            this.alertMessage = 'Erro ao processar usuário.';
            this.setAutoCloseAlert();
          }
        } else {
          this.alertMessage = 'Erro ao processar usuário.';
          this.setAutoCloseAlert();
        }
        this.alertType = 'error';
      },
      setAutoCloseAlert() {
        setTimeout(() => {
          this.alertMessage = '';
        }, 3000);
      },
      async getLoggedInUserId() {
        const token = localStorage.getItem('token');
        const decodedToken = JSON.parse(atob(token.split('.')[1]));
        const userId = decodedToken.sub;
        this.loggedInUserId = userId;
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
    background-color: #4caf50;
    color: white;
  }

  .buttons button:last-of-type {
    background-color: #f44336;
    color: white;
  }

  table {
    width: 100%;
    max-width: 800px;
    margin: 1rem auto;
    border-collapse: collapse;
  }

  table th, table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #ccc;
  }

  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
  }

  .pagination button {
    padding: 0.5rem 1rem;
    margin: 0 0.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .alert {
    margin-top: 20px;
    padding: 10px;
    border-radius: 5px;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
  }
</style>
