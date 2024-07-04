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
            <div class="form-group">
              <label for="descriptions">Descrição:</label>
              <input type="text" id="descriptions" class="form-control" v-model="editedExpense.descriptions" required>
            </div>
            <div class="form-group">
              <label for="price">Preço:</label>
              <input type="text" id="price" class="form-control" v-model="editedExpense.price" required>
            </div>
            <div class="form-group">
              <label for="expense_date">Data da Despesa:</label>
              <DatePicker v-model="selectedDate" type="date" format="DD/MM/YYYY" class="form-control"></DatePicker>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Salvar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import moment from 'moment';

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
      selectedDate: ''
    };
  },
  watch: {
    expense: {
      handler(newVal) {
        // Atualiza o objeto editedExpense com os novos valores da despesa
        this.editedExpense = { ...newVal };
        // Formata a data para exibir no DatePicker
        this.selectedDate = moment(newVal.expense_date).format('DD/MM/YYYY');
      },
      immediate: true // Para que o watch seja executado imediatamente ao carregar o componente
    },
    selectedDate(newVal) {
      // Converte a data selecionada de DD/MM/YYYY para YYYY-MM-DD antes de salvar
      this.editedExpense.expense_date = moment(newVal, 'DD/MM/YYYY').format('YYYY-MM-DD');
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
      this.editedExpense = {}; // Limpa o objeto editedExpense ao fechar o modal
    },
    async saveExpense() {
      try {
        // Envia a requisição para atualizar a despesa
        const response = await this.$http.post(`/expenses/${this.editedExpense.id}`, {
          ...this.editedExpense,
          expense_date: this.editedExpense.expense_date // Garante que a data esteja no formato YYYY-MM-DD
        });
        this.$emit('update', response.data); // Emite evento para atualizar dados
        this.closeModal(); // Fecha o modal após salvar
        alert('Despesa atualizada com sucesso.');
      } catch (error) {
        console.error('Erro ao atualizar despesa:', error);
        alert('Erro ao atualizar despesa. Por favor, tente novamente mais tarde.');
      }
    }
  },
  components: {
    DatePicker
  }
};
</script>

<style scoped>
.modal {
  display: block; /* Ensure modal is displayed */
  position: fixed; /* Fixed position */
  z-index: 1; /* Stay on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scrolling if needed */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-dialog {
  max-width: 400px;
  margin: 18vh auto;
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
</style>
