<template>
  <div class="d-flex">
    <Sidebar />
    <div class="flex-grow-1 p-4">
      <div class="container mt-4">
        <h2 class="mb-4">Página Inicial</h2>
        <div class="row g-4">
          <div class="col-md-6 col-xl-3" v-for="item in items" :key="item.title">
            <div class="card h-100 shadow-sm d-flex flex-column">
              <div class="card-body d-flex flex-column justify-content-between">
                <div>
                  <h5 class="card-title">{{ item.title }}</h5>
                  <p class="card-text">{{ item.description }}</p>
                </div>
                <router-link :to="item.link" class="btn btn-sm btn-outline-primary mt-3">Acessar</router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from './Sidebar.vue'

export default {
  components: { Sidebar },
  data() {
    return {
      items: [
        {
          title: "Usuários",
          description: "Gerencie os usuários cadastrados no sistema.",
          link: "/users",
        },
        {
          title: "Vendedores",
          description: "Consulte e edite os vendedores.",
          link: "/sellers",
        },
        {
          title: "Vendas",
          description: "Veja o histórico de vendas registradas.",
          link: "/sales",
        },
      ],
    };
  },
  methods: {
    logout() {
      localStorage.removeItem('auth_token')
      this.$router.push('/login')
    }
  }
}
</script>
