<template>
    <!-- page -->
    <main class="min-h-screen w-full text-gray-700" x-data="layout">
        <!-- header page -->
        <TheHeader />
        <RouterView />
    </main>
</template>
<script setup>
    import TheHeader from "@/components/templates/admin/TheHeader.vue";
</script>
