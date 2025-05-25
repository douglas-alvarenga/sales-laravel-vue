<template>
  <div class="container mt-4">
    <div class="card shadow-sm">
      <div class="card-header">
        <h2 class="mb-0">{{ isEdit ? 'Editar' : 'Novo' }} Usuário</h2>
      </div>
      <div class="card-body">
        <UserForm :form="form" :isEdit="isEdit" :apiErrors="formErrors" @submit="save" />
        <router-link to="/users" class="btn btn-secondary mt-3">← Voltar</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../services/api.js'
import UserForm from './components/UserForm.vue'
import { mapApiErrors } from '@/utils/errorUtils.js'

export default {
  components: { UserForm },
  data() {
    return {
      form: {
        user: {
          name: '',
          username: '',
          email: '',
          password: ''
        }
      },
      formErrors: {},
      loading: false
    }
  },
  computed: {
    isEdit() {
      return !!this.$route.params.id
    }
  },
  async mounted() {
    if (this.isEdit) {
      const { data } = await api.get(`/user/${this.$route.params.id}`)
      this.form = { ...data.data, password: '' }
    }
  },
  methods: {
    async save(formData) {
      this.loading = true
      this.formErrors = { user: {} }
      try {
        if (this.isEdit) {
          await api.put(`/user/${this.$route.params.id}`, formData.user)
        } else {
          await api.post('/user', formData.user)
        }
        this.$router.push('/users')
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.formErrors = {
            user: mapApiErrors(error.response.data.errors)
          }
        }
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
