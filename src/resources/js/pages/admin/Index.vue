<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="トップ" />
    </div>
</template>

<script setup>
    import { computed } from "vue";
    import { useStore } from "vuex";
    import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";

    const breadCrumbs = [{ name: "ダッシュボード", path: "/admin" }, { name: "トップ" }];
</script>
