<template>
    <RouterLink
        :to="link"
        class="focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-700 py-3.5 px-4 border rounded-lg border-gray-700 flex items-center w-full mt-4 hover:bg-gray-100"
    >
        <p class="text-base font-medium ml-4 text-gray-700">
            {{ name }}
        </p>
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
