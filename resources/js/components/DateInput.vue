<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label">
            {{ label }}
        </label>

        <input :id="id" type="text" :placeholder="placeholder" :value="formattedValue" class="form-control"
            :class="{ 'is-invalid': error }" @input="onInput" @blur="onBlur" />

        <p v-if="error" class="invalid-feedback">
            {{ error }}
        </p>
    </div>
</template>

<script>

export default {
    name: 'DateInput',
    props: {
        id: { type: String, required: true },
        placeholder: { type: String, default: "" },
        error: { type: String, default: "" },
        modelValue: {
            type: [Number, String],
            default: 0,
        },
        label: {
            type: String,
            default: '',
        },
    },
    computed: {
        formattedValue() {
            let value = this.modelValue.slice(0, 12)
            let date = value
            const parts = value.split('-')
            if (parts.length === 3) {
                const [year, month, day] = parts
                if (day.length === 2 && month.length === 2 && year.length === 4) {
                    date = `${day}/${month}/${year}`
                }
            }
            return (date)
        },
    },
    methods: {
        onInput(event) {
            let value = event.target.value.replace(/\D/g, '')
            event.target.value = this.maskDate(value)
        },
        onBlur(event) {
            const value = event.target.value
            if (value.length < 12) {
                this.$emit('update:modelValue', value)
            }
            const parts = value.split('/')
            if (parts.length === 3) {
                const [day, month, year] = parts
                if (day.length === 2 && month.length === 2 && year.length === 4) {
                    const date = `${year}-${month}-${day}`
                    this.$emit('update:modelValue', date)
                }
            }
        },
        maskDate(value) {
            if (value.length > 2 && value.length <= 4) {
                value = value.slice(0, 2) + '/' + value.slice(2)
            } else if (value.length > 4) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4) + '/' + value.slice(4, 8)
            }
            return value
        }
    },
}
</script>
