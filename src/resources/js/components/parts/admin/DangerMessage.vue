<template>
    <div
        class="flex bg-red-100 rounded-lg p-4 mb-4 text-sm text-red-700"
        role="alert"
        v-if="message"
    >
        {{ message }}
    </div>
</template>

<script setup>
const props = defineProps({
    message: { type: String, required: true },
});
</script>
