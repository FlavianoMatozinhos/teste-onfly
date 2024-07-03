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
          </tr>
        </thead>
        <tbody>
          <tr v-for="expense in expenses" :key="expense.id">
            <td>{{ expense.descriptions }}</td> <!-- Corrected typo in 'description' -->
            <td>{{ expense.price }}</td>
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      expenses: [],
      error: false,
      errorMessage: ''
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
