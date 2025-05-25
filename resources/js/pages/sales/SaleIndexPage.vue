<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Vendas</h1>
            <router-link to="/sales/form" class="btn btn-primary">Nova Venda</router-link>
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Vendedor</th>
                        <th>Valor Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sale in sales" :key="sale.id">
                        <td>{{ formatDate(sale.date) }}</td>
                        <td>{{ sale.seller.name }}</td>
                        <td>{{ formatCurrency(sale.amount) }}</td>
                        <td>
                            <router-link :to="`/sales/${sale.id}`" class="btn btn-sm btn-info me-2">Ver</router-link>
                            <router-link :to="`/sales/form/${sale.id}`"
                                class="btn btn-sm btn-warning me-2">Editar</router-link>
                            <button @click="deleteSale(sale.id)" class="btn btn-sm btn-danger">Excluir</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <router-link to="/" class="btn btn-secondary mt-3">← Voltar</router-link>
    </div>
</template>

<script>
import api from '../../services/api.js'
import { formatDate, formatCurrency } from '@/utils/formatUtils.js'

export default {
    data() {
        return {
            sales: []
        }
    },
    methods: {
        formatDate,
        formatCurrency,
        async fetchSales() {
            const res = await api.get('/sale')
            this.sales = res.data.data.sales
        },
        async deleteSale(id) {
            if (confirm('Confirmar exclusão?')) {
                await api.delete(`/sale/${id}`)
                this.fetchSales()
            }
        }
    },
    mounted() {
        this.fetchSales()
    }
}
</script>
