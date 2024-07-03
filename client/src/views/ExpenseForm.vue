<template>
    <div class="expense-form">
      <h2>Cadastrar Despesa</h2>
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
        }
      };
    },
    methods: {
        async submitExpense() {
            try {
            const response = await this.$http.post('/expenses', this.form);
            console.log('Expense added:', response.data);
            this.$emit('expense-added', response.data.data); // Emit event to parent component
            this.form.descriptions = '';
            this.form.price = null;
            this.form.expense_date = '';
            } catch (error) {
            console.error('Erro ao cadastrar despesa:', error);
            // Handle error display or other actions as needed
            }
        },
        disabledDates(date) {
            return date.getTime() > new Date().getTime(); // Disable future dates
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
  </style>
  