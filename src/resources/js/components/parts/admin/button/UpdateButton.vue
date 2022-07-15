<template>
    <button class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-3 py-2 text-center">
        {{name}}
    </button>
</template>
<script setup>
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
});

</script>
