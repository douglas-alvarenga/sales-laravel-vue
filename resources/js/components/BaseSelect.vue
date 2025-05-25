<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label">
            {{ label }}
        </label>
        <select :id="id" class="form-select" :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)">
            <option value="">Selecione</option>
            <option v-for="option in options" :key="option[valueKey]" :value="option[valueKey]">{{ option[labelKey] }}
            </option>
        </select>
        <p v-if="error" class="invalid-feedback">
            {{ error }}
        </p>
    </div>
</template>

<script>
export default {
    name: "BaseSelect",
    inheritAttrs: false,
    props: {
        id: { type: String, required: true },
        label: { type: String, default: "" },
        //modelValue: [String, Number],
        error: { type: String, default: "" },
        options: { type: [Array, Object], required: true },
        modelValue: [String, Number],
        valueKey: {
            type: String,
            default: 'id'
        },
        labelKey: {
            type: String,
            default: 'name'
        }
    },
};
</script>
