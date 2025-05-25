<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label">
            {{ label }}
        </label>

        <input :id="id" type="text" :placeholder="placeholder" :value="formattedValue" class="form-control"
            :class="{ 'is-invalid': error }" @input="onInput" />

        <p v-if="error" class="invalid-feedback">
            {{ error }}
        </p>
    </div>
</template>

<script>
import { formatCurrency } from '@/utils/formatUtils.js'

export default {
    name: 'CurrencyInput',
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
            const number = parseFloat(this.modelValue) || 0
            return formatCurrency(number)
        },
    },
    methods: {
        formatCurrency,
        onInput(event) {
            let value = event.target.value.replace(/\D/g, '')
            value = (parseFloat(value) / 100).toFixed(2)
            this.$emit('update:modelValue', parseFloat(value))
        },
    },
}
</script>
