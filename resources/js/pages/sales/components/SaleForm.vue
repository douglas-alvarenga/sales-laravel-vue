<template>
    <form @submit.prevent="submit" class="p-4 border rounded shadow-sm bg-white">
        <BaseSelect v-model="localForm.sale.seller_id" id="seller" label="Vendedor" :error="errors.sale?.seller_id"
            required :options="sellers" />
        <BaseInput id="date" label="Data" type="datetime" v-model="localForm.sale.date" :error="errors.sale?.date"
            required />
        <BaseInput id="amount" label="Valor total" type="number" v-model="localForm.sale.amount"
            :error="errors.sale?.amount" required />
        <SaveButton :loading="loading" @click="submit" />
    </form>
</template>

<script>
import BaseInput from "@/components/BaseInput.vue";
import SaveButton from "@/components/SaveButton.vue"
import BaseSelect from "@/components/BaseSelect.vue"

export default {
    components: {
        BaseInput, SaveButton, BaseSelect
    },
    props: {
        form: {
            type: Object,
            required: true,
        },
        isEdit: {
            type: Boolean,
            default: false,
        },
        apiErrors: {
            type: Object,
            default: () => ({})
        },
        loading: {
            type: Boolean,
            default: false
        },
        sellers: {
            type: [Array, Object],
            required: true
        }
    },
    data() {
        return {
            localForm: {
                sale: {
                    seller_id: '',
                    date: '',
                    amount: '',
                }
            },
            errors: {},
        }
    },
    watch: {
        form: {
            immediate: true,
            handler(newVal) {
                this.localForm = { ...newVal }
            }
        },
        apiErrors: {
            immediate: true,
            handler(newErrors) {
                this.errors = { ...this.errors, ...newErrors };
            }
        }
    },
    methods: {
        submit() {
            if (!this.validate()) {
                return
            }
            this.$emit('submit', this.localForm)
        },
        validate() {
            //TODO: finalizar validacao, mascara e formatacao dos campos
            //TODO: estilizar home
            const saleErrors = {}

            if (!this.localForm.sale.seller_id) saleErrors.seller_id = 'Vendedor é obrigatório'
            if (!this.localForm.sale.date) saleErrors.date = 'Data é obrigatória'
            if (!this.localForm.sale.amount) {
                saleErrors.email = 'Valor total é obrigatório'
            } else if (!/^\S+@\S+\.\S+$/.test(this.localForm.sale.amount)) {
                //saleErrors.email = 'Valor total inválido'
            }

            const errors = {}
            if (Object.keys(saleErrors).length > 0) {
                errors.sale = saleErrors
            }

            this.errors = errors

            const isValid = Object.keys(errors).length === 0

            return isValid
        }

    }
}
</script>