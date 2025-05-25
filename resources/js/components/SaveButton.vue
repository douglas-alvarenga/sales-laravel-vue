<template>
    <button type="button" @click="$emit('click')" class="btn btn-primary mt-3" :disabled="loading">
        {{ loading ? 'Salvando...' : 'Salvar' }}
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
        }
    },
};
</script>
