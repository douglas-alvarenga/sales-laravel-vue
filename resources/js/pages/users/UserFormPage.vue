<template>
  <div>
    <h2>{{ isEdit ? 'Editar' : 'Novo' }} Usuário</h2>
    <UserForm :form="form" :isEdit="isEdit" @submit="save" />
    <router-link to="/users">← Voltar</router-link>
  </div>
</template>

<script>
import api from '../../services/api.js'
import UserForm from './components/UserForm.vue'

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
      }
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
      console.log('→ evento chegou no pai', formData)
      if (this.isEdit) {
        await api.put(`/user/${this.$route.params.id}`, formData.user)
      } else {
        await api.post('/user', formData.user)
      }
      this.$router.push('/users')
    }
  }
}
</script>
