<template>
    <form @submit.prevent="submit">
        <BaseInput id="name" label="Nome" v-model="localForm.user.name" :error="errors.user?.name" />
        <BaseInput id="username" label="Nome de usuário" v-model="localForm.user.username"
            :error="errors.user?.username" />
        <BaseInput id="email" label="Email" type="email" v-model="localForm.user.email" :error="errors.user?.email" />
        <BaseInput v-if="!isEdit" id="password" label="Senha" type="password" v-model="localForm.user.password"
            :error="errors.user?.password" />
        <button type="button" @click="submit">Salvar</button>
    </form>
</template>

<script>
import BaseInput from "@/components/BaseInput.vue";

export default {
    components: { BaseInput },
    props: {
        form: {
            type: Object,
            required: true,
        },
        isEdit: {
            type: Boolean,
            default: false,
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
        }
    },
    methods: {
        submit() {
            console.log('submit chamado')
            if (!this.validate()) {
                console.log('validação falhou, interrompendo submit')
                return
            }
            console.log('→ emitindo evento pro pai')
            this.$emit('submit', this.localForm)
        },
        validate() {
            console.log('validando')

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
            console.log('é válido?', isValid)

            return isValid
        }

    }
}
</script>

<style scoped>
.error {
    color: red;
    font-size: 0.875rem;
}
</style>