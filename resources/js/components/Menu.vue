<template>
  <div class="container mt-4">
    <h2 class="mb-4">Menu inicial</h2>
    <nav class="mb-3">
      <router-link to="/" class="btn btn-link p-0 me-2">Home</router-link>
      <router-link to="/users" class="btn btn-link p-0">Usuários</router-link>
    </nav>
    <button @click="logout" class="btn btn-danger">Sair</button>
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
