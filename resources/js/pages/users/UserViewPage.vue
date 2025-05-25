<template>
  <div class="container mt-4">
    <h2 class="mb-4">Visualizar Usuário</h2>

    <div class="card p-3 mb-4">
      <p><strong>Nome:</strong> {{ user.name }}</p>
      <p><strong>Email:</strong> {{ user.email }}</p>
    </div>

    <router-link to="/users" class="btn btn-secondary">
      ← Voltar
    </router-link>
  </div>
</template>


<script>
import api from '../../services/api.js'

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
