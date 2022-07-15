<template>
    <RouterLink
        class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-2 text-center"
        :to="link"
    >
        {{name}}
    </RouterLink>
</template>
<script setup>
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    link: {
        type: String,
        required: true,
    },
});

</script>
