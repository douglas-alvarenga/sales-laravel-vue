<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Usuários</h1>
      <router-link to="/users/form" class="btn btn-primary">Novo Usuário</router-link>
    </div>

    <ul class="list-group">
      <li v-for="user in users" :key="user.id"
        class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong>{{ user.name }}</strong> - {{ user.email }}
        </div>
        <div>
          <router-link :to="`/users/${user.id}`" class="btn btn-sm btn-info me-2">Ver</router-link>
          <router-link :to="`/users/form/${user.id}`" class="btn btn-sm btn-warning me-2">Editar</router-link>
          <button @click="deleteUser(user.id)" class="btn btn-sm btn-danger">Excluir</button>
        </div>
      </li>
    </ul>
  </div>
</template>


<script>
import api from '../../services/api.js'

export default {
  data() {
    return {
      users: []
    }
  },
  methods: {
    async fetchUsers() {
      const res = await api.get('/user')
      this.users = res.data.data.users
    },
    async deleteUser(id) {
      if (confirm('Confirmar exclusão?')) {
        await api.delete(`/user/${id}`)
        this.fetchUsers()
      }
    }
  },
  mounted() {
    this.fetchUsers()
  }
}
</script>
