<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="マイページ" />
        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
            <UserPageLinkButton
                v-for="(list, index) in userList"
                :key="index"
                :link="list.link"
                :name="list.title"
            />
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import BreadCrumb from "@/components/parts/admin/BreadCrumb.vue";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";
import UserPageLinkButton from "@/components/parts/admin/button/UserPageLinkButton.vue";

const breadCrumbs = [{ name: "ダッシュボード",path:"/admin" },{ name: "マイページ"}];
const store = useStore();
const admin = computed(() => store.state.admin.admin);
const isLogin = computed(() => store.getters["admin/isAuthenticated"]);
const userList = [
    {
        title: "メールアドレス・パスワード設定",
        link: `/admin/${admin.value.id}/password`,
    },
];
store.dispatch("admin/currentAdmin");
</script>
