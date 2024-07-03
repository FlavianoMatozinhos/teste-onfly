<template>
  <div class="expense-form">
    <h2>Cadastrar Despesa</h2>
    <form @submit.prevent="submitExpense">
      <div>
        <label for="descriptions">Descrição:</label>
        <input type="text" id="descriptions" v-model="form.descriptions" maxlength="191" required>
        <span v-if="form.descriptions.length > 191" class="error-message">A descrição não pode ter mais de 191 caracteres.</span>
      </div>
      <div>
        <label for="price">Preço:</label>
        <input type="text" id="price" v-model="form.price" v-money="money" @input="clearPriceErrors" required>
        <span v-if="form.price < 0" class="error-message">O preço não pode ser negativo.</span>
      </div>
      <div>
        <label for="expense_date">Data da Despesa:</label>
        <DatePicker v-model="form.expense_date" type="date" :disabled-date="disabledDates"></DatePicker>
      </div>
      <div>
        <button type="submit">Cadastrar Despesa</button>
      </div>
    </form>

    <!-- Alertas -->
    <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
    <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
  </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import { VMoney } from 'v-money';
import moment from 'moment';

export default {
  components: {
    DatePicker
  },
  directives: {
    money: VMoney
  },
  data() {
    return {
      form: {
        descriptions: '',
        price: '',
        expense_date: ''
      },
      errorMessage: '',
      successMessage: '', // Adicionando estado para sucesso
      money: {
        decimal: ',',
        thousands: '.',
        prefix: 'R$ ',
        suffix: '',
        precision: 2,
        masked: true
      }
    };
  },
  methods: {
    async submitExpense() {
      this.errorMessage = '';
      this.successMessage = '';

      // Validar descrição
      if (this.form.descriptions.length > 191) {
        this.errorMessage = 'A descrição não pode ter mais de 191 caracteres.';
        return;
      }

      // Validar preço
      const price = parseFloat(this.form.price.replace(/[^\d,-]/g, '').replace(',', '.'));
      if (isNaN(price) || price < 0) {
        this.errorMessage = 'Por favor, insira um preço válido.';
        return;
      }

      try {
        // Format the date before sending it to the server
        this.form.expense_date = moment(this.form.expense_date).format('DD/MM/YYYY');

        const response = await this.$http.post('/expenses', { ...this.form, price });
        console.log('Expense added:', response.data);
        this.$emit('expense-added', response.data.data);
        this.successMessage = 'Despesa cadastrada com sucesso!'; // Mostra o alerta de sucesso
        this.resetForm();
      } catch (error) {
        this.errorMessage = 'Erro ao cadastrar despesa.';
        console.error('Erro ao cadastrar despesa:', error);
      }
    },
    clearPriceErrors() {
      if (this.form.price < 0) {
        this.form.price = '';
      }
      this.errorMessage = '';
    },
    disabledDates(date) {
      return date.getTime() > new Date().getTime();
    },
    resetForm() {
      this.form.descriptions = '';
      this.form.price = '';
      this.form.expense_date = '';
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

.error-message {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.alert {
  margin-top: 1rem;
  padding: 0.75rem;
  border-radius: 0.25rem;
}

.alert-danger {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}
</style>
