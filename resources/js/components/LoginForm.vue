<template>
  <div class="container mt-5" style="max-width: 400px;">
    <h1 class="mb-4">Login</h1>
    <form @submit.prevent="login">
      <div class="mb-3">
        <input v-model="email" type="email" placeholder="Email" class="form-control" required />
      </div>
      <div class="mb-3">
        <input v-model="password" type="password" placeholder="Senha" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
      <p v-if="error" class="text-danger mt-3">{{ error }}</p>
    </form>
  </div>
</template>


<script>
import api from '../services/api.js'

export default {
  data() {
    return {
      email: '',
      password: '',
      error: ''
    }
  },
  methods: {
    async login() {
      try {
        const response = await api.post('/login', {
          login: this.email,
          password: this.password
        })
        const respData = response.data
        localStorage.setItem('auth_token', respData.data.access_token)
        this.$router.push('/')
      } catch (err) {
        this.error = 'Email ou senha inválidos.'
      }
    }
  }
}
</script>
