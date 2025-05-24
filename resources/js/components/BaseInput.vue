<template>
    <div class="mb-4">
        <label v-if="label" :for="id" class="block text-sm font-medium mb-1">
            {{ label }}
        </label>

        <input :id="id" :type="type" :placeholder="placeholder" :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="border rounded p-2 w-full focus:outline-none focus:ring focus:border-blue-300" />

        <p v-if="error" class="text-red-600 text-sm mt-1">
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
