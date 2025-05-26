<template>
    <form @submit.prevent="submit" class="p-4 border rounded shadow-sm bg-white">
        <DateInput id="date" label="Data" v-model="localForm.date" :error="errors?.date" required />
        <SaveButton :loading="loading" @click="submit" buttonText="Enviar" buttonTextDisabled="Enviando" />
    </form>
</template>

<script>
import SaveButton from "@/components/SaveButton.vue"
import DateInput from "@/components/DateInput.vue"

export default {
    components: {
        SaveButton, DateInput
    },
    props: {
        form: {
            type: Object,
            required: true,
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
                date: '',
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
            this.$emit('submit', this.localForm)
        },
        validate() {
            const formErrors = {}
            console.log(this.localForm.date)

            if (!this.localForm.date) formErrors.date = 'Data é obrigatória'

            this.errors = formErrors

            const isValid = Object.keys(formErrors).length === 0

            return isValid
        }

    }
}
</script>