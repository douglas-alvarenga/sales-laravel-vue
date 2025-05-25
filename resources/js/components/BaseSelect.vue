<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label">
            {{ label }}
        </label>

        <input :id="id" :type="type" :placeholder="placeholder" :value="modelValue" class="form-control" v-bind="$attrs"
            :class="{ 'is-invalid': error }" @input="$emit('update:modelValue', $event.target.value)" />

        <p v-if="error" class="invalid-feedback">
            {{ error }}
        </p>
    </div>
</template>

<script>
export default {
    name: "BaseInput",
    inheritAttrs: false,
    props: {
        id: { type: String, required: true },
        label: { type: String, default: "" },
        modelValue: [String, Number],
        type: { type: String, default: "text" },
        placeholder: { type: String, default: "" },
        error: { type: String, default: "" },
    },
};
</script>
