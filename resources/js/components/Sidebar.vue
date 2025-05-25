<template>
    <div class="bg-light border-end vh-100 p-3 d-flex flex-column justify-content-between" style="width: 220px;">
        <div>
            <h5 class="text-center mb-4">Menu</h5>
            <nav class="d-grid gap-2">
                <router-link to="/" class="btn btn-outline-dark">🏠 Home</router-link>
                <router-link to="/users" class="btn btn-outline-dark">👤 Usuários</router-link>
                <router-link to="/sellers" class="btn btn-outline-dark">🧑‍💼 Vendedores</router-link>
                <router-link to="/sales" class="btn btn-outline-dark">📈 Vendas</router-link>
            </nav>
        </div>

        <div class="pt-3">
            <hr />
            <div class="d-grid">
                <button @click="logout" class="btn btn-danger">🚪 Sair</button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'Sidebar',
    methods: {
        logout() {
            localStorage.removeItem('auth_token')
            this.$router.push('/login')
        }
    }
}
</script>
