<template>
    <div class="d-flex">
        <Sidebar />
        <div class="flex-grow-1 p-4">
            <div class="container mt-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="mb-0">{{ isEdit ? 'Editar' : 'Nova' }} Venda</h2>
                    </div>
                    <div class="card-body">
                        <SaleForm :form="form" :isEdit="isEdit" :sellers="sellers" :apiErrors="formErrors"
                            @submit="save" />
                        <router-link to="/sales" class="btn btn-secondary mt-3">← Voltar</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../../services/api.js'
import SaleForm from './components/SaleForm.vue'
import { mapApiErrors } from '@/utils/errorUtils.js'

export default {
    components: { SaleForm },
    data() {
        return {
            form: {
                sale: {
                    seller_id: '',
                    date: '',
                    amount: '',
                }
            },
            sellers: {},
            formErrors: {},
            loading: false
        }
    },
    computed: {
        isEdit() {
            return !!this.$route.params.id
        }
    },
    async mounted() {
        if (this.isEdit) {
            const { data } = await api.get(`/sale/${this.$route.params.id}`)
            this.form = { ...data.data }
        }
        const response = await api.get('/seller')
        this.sellers = response.data.data.sellers
    },
    methods: {
        async save(formData) {
            this.loading = true
            this.formErrors = { sale: {} }
            try {
                if (this.isEdit) {
                    await api.put(`/sale/${this.$route.params.id}`, formData.sale)
                } else {
                    await api.post('/sale', formData.sale)
                }
                this.$router.push('/sales')
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = {
                        sale: mapApiErrors(error.response.data.errors)
                    }
                }
            } finally {
                this.loading = false
            }
        }
    }
}
</script>
