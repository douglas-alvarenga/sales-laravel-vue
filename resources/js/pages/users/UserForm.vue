<template>
  <div>
    <h2>{{ isEdit ? 'Editar' : 'Novo' }} Usuário</h2>

    <form @submit.prevent="save">
      <input v-model="form.user.name" placeholder="Nome" required />
      <input v-model="form.user.username" placeholder="Nome de usuário" required />
      <input v-model="form.user.email" type="email" placeholder="Email" required />
      <input v-if="!isEdit" v-model="form.user.password" type="password" placeholder="Senha" />
      <button type="submit">Salvar</button>
    </form>

    <router-link to="/users">← Voltar</router-link>
  </div>
</template>

<script>
import api from '../../services/api.js'

export default {
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
      return this.$route.path.startsWith('/users/form/') && !!this.$route.params.id
    },
  },
  async mounted() {
    if (this.isEdit) {
      const id = this.$route.params.id
      const res = await api.get(`/user/${id}`)
      this.form = { ...res.data.data, password: '' }
    }
  },
  methods: {
    async save() {
      const id = this.$route.params.id
      this.form.user.is_admin = true;
      if (this.isEdit) {
        await api.put(`/user/${id}`, this.form.user)
      } else {
        await api.post('/user', this.form.user)
      }
      this.$router.push('/users')
    }
  }
}
</script>
