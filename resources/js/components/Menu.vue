<template>
  <div>
    <h2>Bem-vindo ao painel!</h2>
    <nav>
      <router-link to="/">Home</router-link> |
      <router-link to="/users">Usuários</router-link>
    </nav>
    <button @click="logout">Sair</button>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      localStorage.removeItem('auth_token')
      this.$router.push('/login')
    }
  }
}
</script>
