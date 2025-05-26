<template>
    <button type="button" @click="$emit('click')" class="btn btn-primary mt-3" :disabled="loading">
        {{ loading ? buttonTextDisabled : buttonText }}
    </button>
</template>

<script>
export default {
    name: "SaveButton",
    inheritAttrs: false,
    props: {
        loading: {
            type: Boolean,
            default: false
        },
        buttonText: {
            type: String,
            default: 'Salvar'
        },
        buttonTextDisabled: {
            type: String,
            default: 'Salvando...'
        }
    },
};
</script>
