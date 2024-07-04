<template>
  <div class="home">
    <h2>Home</h2>
    <div>
      <button @click="goToUsers">Gerenciar Usuários</button>
      <button @click="goToCreateExpense">Cadastrar Despesa</button>
    </div>
    <div v-if="expenses.length">
      <h3>Suas Despesas</h3>
      <table>
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
              <button @click="editExpense(expense)">Editar</button>
              <button @click="confirmDelete(expense.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else>
      <p>Você não possui despesas cadastradas.</p>
    </div>
    <div v-if="error">
      <p>{{ errorMessage }}</p>
    </div>

    <!-- Modal de Edição -->
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
        this.error = true;
        this.errorMessage = 'Erro ao carregar despesas. Por favor, tente novamente mais tarde.';
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
