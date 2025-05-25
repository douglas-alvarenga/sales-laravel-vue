<template>
    <div class="d-flex">
        <Sidebar />
        <div class="flex-grow-1 p-4"></div>
        <div class="container mt-4">
            <h2 class="mb-4">Visualizar Vendedor</h2>

            <div class="card p-3 mb-4">
                <p><strong>Nome:</strong> {{ seller.name }}</p>
                <p><strong>Email:</strong> {{ seller.email }}</p>
            </div>

            <router-link to="/sellers" class="btn btn-secondary">
                ← Voltar
            </router-link>
        </div>
    </div>
</template>


<script>
import api from '../../services/api.js'

export default {
    data() {
        return {
            seller: {}
        }
    },
    async mounted() {
        const { id } = this.$route.params
        const res = await api.get(`/seller/${id}`)
        this.seller = res.data.data.seller
    }
}
</script>
