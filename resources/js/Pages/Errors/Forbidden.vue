<template>
    <div class="p-4 bg-red-100 text-red-700 rounded">
        <h2>403 - Acesso Negado</h2>
        <p>{{ message }}</p>
    </div>
</template>

<script setup>
const props = defineProps({
    message: String,
});
</script>
