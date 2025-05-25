<template>
    <div class="d-flex">
        <Sidebar />
        <div class="flex-grow-1 p-4"></div>
        <div class="container mt-4">
            <h2 class="mb-4">Visualizar Venda</h2>

            <div class="card p-3 mb-4">
                <p><strong>Vendedor:</strong> {{ sale.seller.name }}</p>
                <p><strong>Data:</strong> {{ formatDatetime(sale.date, { seconds: false }) }}</p>
                <p>
                    <strong>Comissão ({{ formatPercent(sale.seller_commission_percentage) }}):</strong>
                    {{ formatCurrency(sale.seller_commission_amount) }}
                </p>
                <p><strong>Valor Total:</strong> {{ formatCurrency(sale.amount) }}</p>
            </div>

            <router-link to="/sales" class="btn btn-secondary">
                ← Voltar
            </router-link>
        </div>
    </div>
</template>


<script>
import api from '../../services/api.js'
import { formatDatetime, formatCurrency, formatPercent } from '@/utils/formatUtils.js'

export default {
    data() {
        return {
            sale: {
                seller: {}
            }
        }
    },
    methods: {
        formatDatetime,
        formatCurrency,
        formatPercent
    },
    async beforeMount() {
        const { id } = this.$route.params
        const res = await api.get(`/sale/${id}`)
        this.sale = res.data.data.sale
    }, async teste() {
        const { id } = this.$route.params
        const res = await api.get(`/sale/${id}`)
        this.sale = res.data.data.sale
    }
}
</script>
