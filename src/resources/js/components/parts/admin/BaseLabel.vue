<template>
    <label class="text-sm font-medium text-gray-900 block mb-2">{{
        labelName
    }}</label>
</template>
<script setup>
const props = defineProps({
    labelName: { type: String, required: true },
    forName: { type: String },
});
</script>
