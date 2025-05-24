<template>
  <div>
    <h2>Visualizar Usuário</h2>
    <p><strong>Nome:</strong> {{ user.name }}</p>
    <p><strong>Email:</strong> {{ user.email }}</p>
    <router-link to="/users">← Voltar</router-link>
  </div>
</template>

<script>
import api from '../../axios'

export default {
  data() {
    return {
      user: {}
    }
  },
  async mounted() {
    const { id } = this.$route.params
    const res = await api.get(`/user/${id}`)
    this.user = res.data.data.user
  }
}
</script>
