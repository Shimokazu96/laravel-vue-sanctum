<template>
    <div class="flex mb-5" aria-label="Breadcrumb">
        <div class="inline-flex items-center space-x-1 md:space-x-2" v-for="(value, index) in breadCrumbs" :key="index">
            <div v-if="value.path" class="inline-flex items-center">
            <router-link :to="value.path" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                <span class="link">{{value.name}}</span>
            </router-link>
            <span class="mx-2">></span>
            </div>
        <div v-else class="flex items-center">
            <span
                        class="text-gray-400 ml-1 md:ml-2 text-sm font-medium"
                        aria-current="page"
                        >{{value.name}}</span
                    >

            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    breadCrumbs: { type: Array, default: () => [] },
});
</script>
