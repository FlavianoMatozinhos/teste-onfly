<template>
  <div class="expense-form">
    <h2>Cadastrar Despesa</h2>
    <form @submit.prevent="submitExpense">
      <div class="form-group">
        <label for="descriptions">Descrição:</label>
        <input type="text" id="descriptions" v-model="form.descriptions" :maxlength="maxLength" required>
        <span v-if="form.descriptions.length > 191" class="error-message">A descrição não pode ter mais de 191 caracteres.</span>
      </div>
      <div class="form-group">
        <label for="price">Preço:</label>
        <input type="text" id="price" v-model="form.price" v-money="money" @input="clearPriceErrors" @keypress="preventNegativeInput" required>
        <span v-if="parseFloat(form.price.replace(/[^\d,-]/g, '').replace(',', '.')) < 0" class="error-message">O preço não pode ser negativo.</span>
      </div>
      <div class="form-group">
        <label for="expense_date">Data da Despesa:</label>
        <DatePicker v-model="form.expense_date" type="date" format="DD/MM/YYYY" :disabled-date="disabledDates" class="form-control"></DatePicker>
        <span v-if="errors && errors.expense_date" class="error-message">A data da despesa é obrigatória.</span>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block">Cadastrar Despesa</button>
      </div>
    </form>

    <div v-if="errorMessage && errorMessage !== 'A data da despesa é obrigatória.'" class="alert alert-danger">{{ errorMessage }}</div>
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
      maxLength: 191,
      errorMessage: '',
      successMessage: '',
      money: {
        decimal: ',',
        thousands: '.',
        prefix: 'R$ ',
        suffix: '',
        precision: 2,
        masked: true
      },
      errors: {}
    };
  },
  methods: {
    async submitExpense() {
      this.errorMessage = '';
      this.successMessage = '';

      if (this.form.descriptions.length > 191) {
        this.errorMessage = 'A descrição não pode ter mais de 191 caracteres.';
        return;
      }

      const price = parseFloat(this.form.price.replace(/[^\d,-]/g, '').replace(',', '.'));
      if (isNaN(price) || price < 0) {
        this.errorMessage = 'Por favor, insira um preço válido.';
        return;
      }

      this.validateForm();

      if (Object.keys(this.errors).length > 0) {
        return;
      }

      try {
        this.form.expense_date = moment(this.form.expense_date).format('DD/MM/YYYY');

        const response = await this.$http.post('/expenses', { ...this.form, price });
        this.$emit('expense-added', response.data.data);
        this.successMessage = 'Despesa cadastrada com sucesso!';
        this.resetForm();
      } catch (error) {
        this.errorMessage = 'Erro ao cadastrar despesa.';
      }
    },
    clearPriceErrors() {
      if (parseFloat(this.form.price.replace(/[^\d,-]/g, '').replace(',', '.')) < 0) {
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
    },
    preventNegativeInput(event) {
      if (event.key === '-' || event.key === '+') {
        event.preventDefault();
      }
    },
    validateForm() {
      this.errors = {};

      if (!this.form.expense_date) {
        this.errors.expense_date = true;
      }
    }
  }
};
</script>

<style scoped>
.expense-form {
  max-width: 400px;
  margin: 40px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: #f9f9f9;
}

.expense-form h2 {
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: flex;
  font-weight: bold;
  margin-bottom: 5px;
}

.form-group input,
.form-group .form-control {
  width: 100%;
  padding: 8px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.form-group .form-control {
  padding: 0.5rem;
}

.btn {
  display: block;
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  color: #fff;
  background-color: #007bff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  text-align: center;
}

.btn:hover {
  background-color: #0056b3;
}

.error-message {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.alert {
  margin-top: 20px;
  padding: 15px;
  border-radius: 4px;
  font-size: 0.875rem;
  text-align: center;
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
