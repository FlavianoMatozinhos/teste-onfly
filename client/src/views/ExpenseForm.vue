<template>
  <div class="expense-form">
    <h2>Cadastrar Despesa</h2>
    <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    
    <form @submit.prevent="submitExpense">
      <div>
        <label for="descriptions">Descrição:</label>
        <input type="text" id="descriptions" v-model="form.descriptions" required>
      </div>
      <div>
        <label for="price">Preço:</label>
        <input type="number" id="price" v-model.number="form.price" required>
      </div>
      <div>
        <label for="expense_date">Data da Despesa:</label>
        <DatePicker v-model="form.expense_date" type="date" :disabled-date="disabledDates"></DatePicker>
      </div>
      <div>
        <button type="submit">Cadastrar Despesa</button>
      </div>
    </form>
  </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from 'moment';

export default {
  components: {
    DatePicker
  },
  data() {
    return {
      form: {
        descriptions: '',
        price: null,
        expense_date: ''
      },
      successMessage: '',
      errorMessage: ''
    };
  },
  methods: {
    async submitExpense() {
      try {
        this.form.expense_date = moment(this.form.expense_date).format('DD/MM/YYYY');

        const response = await this.$http.post('/expenses', this.form);
        this.successMessage = 'Despesa cadastrada com sucesso!';
        this.errorMessage = '';
        this.$emit('expense-added', response.data.data);
        this.form.descriptions = '';
        this.form.price = null;
        this.form.expense_date = '';
      } catch (error) {
        this.successMessage = '';
        if (error.response && error.response.data && error.response.data.message) {
          this.errorMessage = 'Erro ao cadastrar despesa: ' + error.response.data.message;
        } else {
          this.errorMessage = 'Erro ao cadastrar despesa.';
        }
      }
    },
    disabledDates(date) {
      return date.getTime() > new Date().getTime();
    }
  }
};
</script>

<style scoped>
.expense-form {
  max-width: 400px;
  margin: auto;
}
.expense-form form {
  display: flex;
  flex-direction: column;
}
.expense-form form div {
  margin-bottom: 1rem;
}
.expense-form form label {
  font-weight: bold;
}
.expense-form form input {
  padding: 0.5rem;
  font-size: 1rem;
  border: 1px solid #ccc;
}
.expense-form form button {
  padding: 0.75rem;
  font-size: 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}

.alert {
  margin-top: 1rem;
  padding: 0.75rem;
  border-radius: 0.25rem;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}

.alert-danger {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}
</style>