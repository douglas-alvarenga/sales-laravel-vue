<template>
  <div>
    <h1>Usuários</h1>
    <router-link to="/users/form">Novo Usuário</router-link>
    <ul>
      <li v-for="user in users" :key="user.id">
        {{ user.name }} - {{ user.email }}
        <router-link :to="`/users/${user.id}`">Ver</router-link>
        <router-link :to="`/users/form/${user.id}`">Editar</router-link>
        <button @click="deleteUser(user.id)">Excluir</button>
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
