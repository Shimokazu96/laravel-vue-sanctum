<template>
    <!-- This is an example component -->
    <div>
        <div class="flex overflow-hidden pt-16">
            <TheSidebar />
            <div
                class="bg-gray-900 opacity-50 hidden fixed inset-0 z-10"
                id="sidebarBackdrop"
            ></div>
            <div
                id="main-content"
                class="h-full w-full relative overflow-y-auto lg:ml-64"
            >
            <RouterView />
            </div>
        </div>
    </div>
</template>
<script setup>
import TheSidebar from "@/components/templates/admin/TheSidebar.vue";
</script>
