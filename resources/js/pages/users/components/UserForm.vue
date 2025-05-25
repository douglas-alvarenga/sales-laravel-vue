<template>
    <form @submit.prevent="submit" class="p-4 border rounded shadow-sm bg-white">
        <BaseInput id="name" label="Nome" v-model="localForm.user.name" :error="errors.user?.name" minlength="3"
            maxlength="50" required />
        <BaseInput id="username" label="Nome de usuário" v-model="localForm.user.username"
            :error="errors.user?.username" minlength="3" maxlength="20" required />
        <BaseInput id="email" label="Email" type="email" v-model="localForm.user.email" :error="errors.user?.email"
            maxlength="50" required />
        <BaseInput v-if="!isEdit" id="password" label="Senha" type="password" v-model="localForm.user.password"
            :error="errors.user?.password" minlength="4" maxlength="20" :required="!isEdit" />
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
                user: {
                    name: '',
                    username: '',
                    email: '',
                    password: ''
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
            this.localForm.user.is_admin = true
            this.$emit('submit', this.localForm)
        },
        validate() {
            const userErrors = {}

            if (!this.localForm.user.name) userErrors.name = 'Nome é obrigatório'
            if (!this.localForm.user.username) userErrors.username = 'Nome de usuário é obrigatório'
            if (!this.localForm.user.email) {
                userErrors.email = 'E-mail é obrigatório'
            } else if (!/^\S+@\S+\.\S+$/.test(this.localForm.user.email)) {
                userErrors.email = 'E-mail inválido'
            }

            if (!this.isEdit && !this.localForm.user.password) {
                userErrors.password = 'Senha é obrigatória'
            }

            const errors = {}
            if (Object.keys(userErrors).length > 0) {
                errors.user = userErrors
            }

            this.errors = errors

            const isValid = Object.keys(errors).length === 0

            return isValid
        }

    }
}
</script>