<template>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h2 class="mb-0">{{ isEdit ? 'Editar' : 'Novo' }} Venda</h2>
            </div>
            <div class="card-body">
                <SaleForm :form="form" :isEdit="isEdit" :apiErrors="formErrors" @submit="save" />
                <router-link to="/sales" class="btn btn-secondary mt-3">← Voltar</router-link>
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
                    sale_id: '',
                    date: '',
                    amount: '',
                }
            },
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
