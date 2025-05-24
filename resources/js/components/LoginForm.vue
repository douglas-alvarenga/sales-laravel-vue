<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Senha" />
      <button type="submit">Entrar</button>
      <p v-if="error" style="color: red">{{ error }}</p>
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
