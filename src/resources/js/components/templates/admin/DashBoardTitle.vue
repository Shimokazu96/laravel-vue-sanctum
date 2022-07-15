<template>
    <div
        class="p-4 bg-white block sm:flex items-center justify-between"
    >
        <div class="mb-1 w-full">
            <div class="mb-4">
                <BreadCrumb :breadCrumbs="breadCrumbs" />
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                    {{ title }}
                </h1>
                <UpdateMessage />
                <slot name="searchArea"></slot>
            </div>
        </div>
    </div>
</template>
<script setup>
import BreadCrumb from "@/components/parts/admin/BreadCrumb.vue";
import UpdateMessage from "@/components/parts/admin/UpdateMessage.vue";

const props = defineProps({
    title: { type: String, required: true },
    breadCrumbs: { type: Array, required: true },
});
</script>
