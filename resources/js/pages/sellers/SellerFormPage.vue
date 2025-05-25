<template>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h2 class="mb-0">{{ isEdit ? 'Editar' : 'Novo' }} Vendedor</h2>
            </div>
            <div class="card-body">
                <SellerForm :form="form" :isEdit="isEdit" :apiErrors="formErrors" @submit="save" />
                <router-link to="/sellers" class="btn btn-secondary mt-3">← Voltar</router-link>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../../services/api.js'
import SellerForm from './components/SellerForm.vue'
import { mapApiErrors } from '@/utils/errorUtils.js'

export default {
    components: { SellerForm },
    data() {
        return {
            form: {
                seller: {
                    name: '',
                    email: ''
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
            const { data } = await api.get(`/seller/${this.$route.params.id}`)
            this.form = { ...data.data }
        }
    },
    methods: {
        async save(formData) {
            this.loading = true
            this.formErrors = { seller: {} }
            try {
                if (this.isEdit) {
                    await api.put(`/seller/${this.$route.params.id}`, formData.seller)
                } else {
                    await api.post('/seller', formData.seller)
                }
                this.$router.push('/sellers')
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = {
                        seller: mapApiErrors(error.response.data.errors)
                    }
                }
            } finally {
                this.loading = false
            }
        }
    }
}
</script>
