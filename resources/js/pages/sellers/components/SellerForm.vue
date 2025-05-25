<template>
    <form @submit.prevent="submit" class="p-4 border rounded shadow-sm bg-white">
        <BaseInput id="name" label="Nome" v-model="localForm.seller.name" :error="errors.seller?.name" minlength="3"
            maxlength="50" required />
        <BaseInput id="email" label="Email" type="email" v-model="localForm.seller.email" :error="errors.seller?.email"
            maxlength="50" required />
        <SaveButton :loading="loading" @click="submit" />
    </form>
</template>

<script>
import BaseInput from "@/components/BaseInput.vue";
import SaveButton from "@/components/SaveButton.vue"

export default {
    components: { BaseInput, SaveButton },
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
        }
    },
    data() {
        return {
            localForm: {
                seller: {
                    name: '',
                    email: '',
                }
            },
            errors: {}
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
            const sellerErrors = {}

            if (!this.localForm.seller.name) sellerErrors.name = 'Nome é obrigatório'
            if (!this.localForm.seller.email) {
                sellerErrors.email = 'E-mail é obrigatório'
            } else if (!/^\S+@\S+\.\S+$/.test(this.localForm.seller.email)) {
                sellerErrors.email = 'E-mail inválido'
            }

            const errors = {}
            if (Object.keys(sellerErrors).length > 0) {
                errors.seller = sellerErrors
            }

            this.errors = errors

            const isValid = Object.keys(errors).length === 0

            return isValid
        }

    }
}
</script>