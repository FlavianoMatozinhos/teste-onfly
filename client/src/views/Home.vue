<template>
  <div class="home container">
    <h2 class="text-center my-4">Home</h2>
    <div class="d-flex justify-content-center mb-5">
      <button class="btn btn-primary mx-2" @click="goToUsers">Gerenciar Usuários</button>
      <button class="btn btn-success mx-2" @click="goToCreateExpense">Cadastrar Despesa</button>
    </div>
    <div v-if="expenses.length">
      <h3 class="text-center mb-4 mt-4">Suas Despesas</h3>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="expense in expenses" :key="expense.id">
            <td>{{ expense.descriptions }}</td>
            <td>{{ expense.price }}</td>
            <td>
              <button class="btn btn-warning btn-sm mx-1" @click="editExpense(expense)">Editar</button>
              <button class="btn btn-danger btn-sm mx-1" @click="confirmDelete(expense.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else>
      <p class="text-center">Você não possui despesas cadastradas.</p>
    </div>
    <div v-if="alertMessage" :class="['alert', alertType === 'error' ? 'alert-danger' : 'alert-success']">
      <p>{{ alertMessage }}</p>
    </div>

    <expense-edit-modal
      :show-modal="showModal"
      :expense="selectedExpense"
      @close="closeModal"
      @update="handleUpdateExpense"
    ></expense-edit-modal>
  </div>
</template>

<script>
import ExpenseEditModal from './ExpenseEditModal.vue';

export default {
  components: {
    ExpenseEditModal
  },
  data() {
    return {
      expenses: [],
      error: false,
      errorMessage: '',
      showModal: false,
      selectedExpense: null,
      alertMessage: '',
      alertType: ''
    };
  },
  created() {
    this.loadExpenses();
  },
  methods: {
    goToUsers() {
      this.$router.push('/users');
    },
    goToCreateExpense() {
      this.$router.push('/expenses/create');
    },
    async loadExpenses() {
      try {
        const response = await this.$http.get('/expenses');
        if (response.data && response.data.data) {
          this.expenses = response.data.data;
        } else {
          this.errorMessage = 'Dados de despesas vazios ou não encontrados.';
          this.expenses = [];
        }
      } catch (error) {
        this.expenses = [];
      }
    },
    editExpense(expense) {
      this.selectedExpense = expense;
      this.showModal = true;
    },
    async handleUpdateExpense(updatedExpense) {
      try {
        const index = this.expenses.findIndex(expense => expense.id === updatedExpense.id);
        if (index !== -1) {
          this.expenses.splice(index, 1, updatedExpense);
        }
        await this.loadExpenses();
        this.closeModal();
        this.alertMessage = 'Despesa atualizada com sucesso!';
        this.alertType = 'success';
        this.setAutoCloseAlert();
      } catch (error) {
        this.alertMessage = 'Erro ao atualizar despesa. Por favor, tente novamente mais tarde.';
        this.alertType = 'error';
        this.setAutoCloseAlert();
      }
    },
    async confirmDelete(expenseId) {
      if (confirm('Tem certeza que deseja excluir esta despesa?')) {
        try {
          await this.$http.delete(`/expenses/${expenseId}`);
          this.expenses = this.expenses.filter(expense => expense.id !== expenseId);
          this.alertMessage = 'Despesa excluída com sucesso!';
          this.alertType = 'success';
          this.setAutoCloseAlert();
        } catch (error) {
          this.alertMessage = 'Erro ao excluir despesa. Por favor, tente novamente mais tarde.';
          this.alertType = 'error';
          this.setAutoCloseAlert();
        }
      }
    },
    closeModal() {
      this.showModal = false;
      this.selectedExpense = null;
    },
    setAutoCloseAlert() {
      setTimeout(() => {
        this.alertMessage = '';
      }, 3000);
    }
  }
};
</script>

<style scoped>
.home {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

table {
  width: 100%;
}

th, td {
  text-align: center;
}

.alert {
  margin-top: 20px;
}

.alert-success {
  background-color: #d4edda;
  border-color: #c3e6cb;
  color: #155724;
}

.alert-danger {
  background-color: #f8d7da;
  border-color: #f5c6cb;
  color: #721c24;
}
</style>
