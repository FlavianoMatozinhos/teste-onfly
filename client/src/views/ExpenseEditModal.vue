<template>
  <div class="modal" v-if="showModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Despesa</h5>
          <button type="button" class="close" @click="closeModal">&times;</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveExpense">
            <div class="form-group mb-3">
              <label for="descriptions">Descrição:</label>
              <input type="text" id="descriptions" class="form-control" v-model="editedExpense.descriptions" required>
            </div>
            <div class="form-group  mb-3">
              <label for="price">Preço:</label>
              <input type="text" id="price" class="form-control" v-model="editedExpense.price" v-money="money" @input="clearPriceErrors" required>
              <span v-if="parseFloat(editedExpense.price.replace(/[^\d,-]/g, '').replace(',', '.')) < 0" class="error-message">O preço não pode ser negativo.</span>
            </div>
            <div class="form-group mb-3">
              <label for="expense_date">Data da Despesa:</label>
              <DatePicker v-model="selectedDate" type="date" format="DD/MM/YYYY" class="form-control" :disabled-date="disabledDates"></DatePicker>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Salvar</button>
          </form>
        </div>
        <div v-if="alertMessage" :class="['alert', alertType === 'error' ? 'alert-danger' : 'alert-success']">
          <p>{{ alertMessage }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import moment from 'moment';
import { VMoney } from 'v-money';

export default {
  props: {
    showModal: Boolean,
    expense: Object
  },
  data() {
    return {
      editedExpense: {
        id: null,
        descriptions: '',
        price: '',
        expense_date: null
      },
      selectedDate: '',
      money: {
        decimal: ',',
        thousands: '.',
        prefix: 'R$ ',
        suffix: '',
        precision: 2,
        masked: true
      },
      alertMessage: '',
      alertType: ''
    };
  },
  watch: {
    expense: {
      handler(newVal) {
        this.editedExpense = { ...newVal };
        this.selectedDate = moment(newVal.expense_date).format('DD/MM/YYYY');
      },
      immediate: true
    },
    selectedDate(newVal) {
      this.editedExpense.expense_date = moment(newVal, 'DD/MM/YYYY').format('DD/MM/YYYY');
    },
    alertMessage(newValue) {
      if (newValue) {
        setTimeout(() => {
          this.alertMessage = '';
        }, 3000);
      }
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
      this.editedExpense = {};
    },
    async saveExpense() {
      try {
        const price = parseFloat(this.editedExpense.price.replace(/[^\d,-]/g, '').replace(',', '.'));
        if (isNaN(price) || price < 0) {
          this.alertMessage = 'Por favor, insira um preço válido.';
          this.alertType = 'error';
          return;
        }

        const response = await this.$http.put(`/expenses/${this.editedExpense.id}`, {
          ...this.editedExpense,
          price: price,
          expense_date: this.editedExpense.expense_date
        });

        this.$emit('update', response.data);
        this.closeModal();
        this.alertMessage = 'Despesa atualizada com sucesso.';
        this.alertType = 'success';
      } catch (error) {
        this.alertMessage = 'Erro ao atualizar despesa. Por favor, tente novamente mais tarde.';
        this.alertType = 'error';
      }
    },
    clearPriceErrors() {
      if (parseFloat(this.editedExpense.price.replace(/[^\d,-]/g, '').replace(',', '.')) < 0) {
        this.editedExpense.price = '';
      }
    },
    disabledDates(date) {
      return date.getTime() > new Date().getTime();
    }
  },
  directives: {
    money: VMoney
  },
  components: {
    DatePicker
  }
};
</script>

<style scoped>
.modal {
  display: block;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modal-dialog {
  max-width: 400px;
  margin: 0vh auto;
}

.modal-content {
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.close {
  color: #aaaaaa;
  font-size: 28px;
  font-weight: bold;
  background: none;
  border: none;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

label {
  display: flex !important;
}

.mx-datepicker {
  width: 100% !important;
}

.error-message {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.alert {
  margin-top: 10px;
  transition: opacity 0.5s ease;
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
