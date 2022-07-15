<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="ユーザーページ" />
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
import { ref, computed, watch } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";
import UserPageLinkButton from "@/components/parts/admin/button/UserPageLinkButton.vue";

const route = useRoute();
const breadCrumbs = [
    {
        name: "ダッシュボード",
        path: "/admin",
    },
    {
        name: "ユーザー一覧",
        path: "/admin/users",
    },
    {
        name: "ユーザーページ",
    },
];
const id = ref(route.params.id);

const userList = [
    { title: "ユーザー情報", link: `/admin/users/${id.value}/infomation` },
    {
        title: "メールアドレス・パスワード設定",
        link: `/admin/users/${id.value}/password`,
    },
];

const store = useStore();
</script>
