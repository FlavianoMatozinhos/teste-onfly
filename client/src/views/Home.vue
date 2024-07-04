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
    <div v-if="error" class="alert alert-danger text-center mt-4">
      <p>{{ errorMessage }}</p>
    </div>

    <expense-edit-modal
      :show-modal="showModal"
      :expense="selectedExpense"
      @close="closeModal"
      @update="updateExpense"
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
      selectedExpense: null
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
    async updateExpense(updatedExpense) {
      try {
        const response = await this.$http.post(`/expenses/${updatedExpense.id}`, updatedExpense);
        const index = this.expenses.findIndex(expense => expense.id === updatedExpense.id);
        if (index !== -1) {
          this.expenses.splice(index, 1, response.data);
        }
        this.closeModal();
        alert('Despesa atualizada com sucesso.');
      } catch (error) {
        console.error('Erro ao atualizar despesa:', error);
        alert('Erro ao atualizar despesa. Por favor, tente novamente mais tarde.');
      }
    },
    async confirmDelete(expenseId) {
      if (confirm('Tem certeza que deseja excluir esta despesa?')) {
        try {
          await this.$http.delete(`/expenses/${expenseId}`);
          this.expenses = this.expenses.filter(expense => expense.id !== expenseId);
          alert('Despesa excluída com sucesso.');
        } catch (error) {
          console.error('Erro ao excluir despesa:', error);
          alert('Erro ao excluir despesa. Por favor, tente novamente mais tarde.');
        }
      }
    },
    closeModal() {
      this.showModal = false;
      this.selectedExpense = null;
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
</style>
