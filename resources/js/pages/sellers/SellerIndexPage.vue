<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Vendedores</h1>
            <router-link to="/sellers/form" class="btn btn-primary">Novo Vendedor</router-link>
        </div>

        <ul class="list-group">
            <li v-for="seller in sellers" :key="seller.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ seller.name }}</strong> - {{ seller.email }}
                </div>
                <div>
                    <router-link :to="`/sellers/${seller.id}`" class="btn btn-sm btn-info me-2">Ver</router-link>
                    <router-link :to="`/sellers/form/${seller.id}`"
                        class="btn btn-sm btn-warning me-2">Editar</router-link>
                    <button @click="deleteSeller(seller.id)" class="btn btn-sm btn-danger">Excluir</button>
                </div>
            </li>
        </ul>
        <router-link to="/" class="btn btn-secondary mt-3">← Voltar</router-link>
    </div>
</template>


<script>
import api from '../../services/api.js'

export default {
    data() {
        return {
            sellers: []
        }
    },
    methods: {
        async fetchSellers() {
            const res = await api.get('/seller')
            this.sellers = res.data.data.sellers
        },
        async deleteSeller(id) {
            if (confirm('Confirmar exclusão?')) {
                await api.delete(`/seller/${id}`)
                this.fetchSellers()
            }
        }
    },
    mounted() {
        this.fetchSellers()
    }
}
</script>
