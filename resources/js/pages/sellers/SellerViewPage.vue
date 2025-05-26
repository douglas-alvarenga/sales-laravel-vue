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
            <hr>
            <div class="card p-3 mb-4">
                <h4>Enviar email de relatório diário das vendas</h4>
                <div class="card-body">
                    <SendDailySalesReportForm :form="form" :apiErrors="formErrors" @submit="sendDailySalesReport" />
                </div>
            </div>
            <hr>
            <div class="card p-3 mb-4">
                <h4>Vendas</h4>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Comissão</th>
                                <th>Valor Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sale in sales" :key="sale.id">
                                <td>{{ formatDatetime(sale.date, { seconds: false }) }}</td>
                                <td>{{ formatCurrency(sale.seller_commission_amount) }} ({{
                                    formatPercent(sale.seller_commission_percentage) }})</td>
                                <td>{{ formatCurrency(sale.amount) }}</td>
                                <td>
                                    <router-link :to="`/sales/${sale.id}`"
                                        class="btn btn-sm btn-info me-2">Ver</router-link>
                                    <router-link :to="`/sales/form/${sale.id}`"
                                        class="btn btn-sm btn-warning me-2">Editar</router-link>
                                    <button @click="deleteSale(sale.id)" class="btn btn-sm btn-danger">Excluir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <router-link to="/sellers" class="btn btn-secondary">
                ← Voltar
            </router-link>
        </div>
    </div>
</template>


<script>
import api from '../../services/api.js'
import SendDailySalesReportForm from './components/SendDailySalesReportForm.vue'
import { formatDatetime, formatCurrency, formatPercent } from '@/utils/formatUtils.js'
import { mapApiErrors } from '@/utils/errorUtils.js'

export default {
    components: { SendDailySalesReportForm },
    data() {
        return {
            seller: {},
            sales: {},
            form: {
                date: ''
            },
            formErrors: {},
            loading: false
        }
    },
    async mounted() {
        this.fetchSeller()
        this.fetchSellerSales()
    },
    methods: {
        formatDatetime,
        formatCurrency,
        formatPercent,
        mapApiErrors,
        async fetchSeller() {
            const { id } = this.$route.params
            const res = await api.get(`/seller/${id}`)
            this.seller = res.data.data.seller
        },
        async fetchSellerSales() {
            const { id } = this.$route.params
            const res = await api.get(`/sale?seller=${id}`)
            this.sales = res.data.data.sales
        },
        async sendDailySalesReport(formData) {
            this.loading = true
            this.formErrors = {}
            try {
                await api.post(`/seller/${this.$route.params.id}/send-daily-sales-report`, formData)
                this.formErrors = {
                    date: ''
                }
                alert("Solicitação de envio de email realizada.")
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = mapApiErrors(error.response.data.errors)
                }
            } finally {
                this.loading = false
            }
        }
    }
}
</script>
