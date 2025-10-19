<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600 mt-1 bg-red-50 p-2 rounded-lg border border-red-200">
            {{ message }}
        </p>
    </div>
</template>
