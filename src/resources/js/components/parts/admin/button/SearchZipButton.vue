<template>
    <button
        type="button"
        class="block border border-transparent text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-md px-5 py-2 font-semibold ml-2 w-3/12"
        :disabled="disabled"
    >
        {{ name }}
    </button>
</template>
<script setup>
import { ref } from "vue";
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    disabled: { type: String },
});
</script>
