<template>
    <aside
        id="sidebar"
        class="fixed hidden z-20 h-full top-0 left-0 pt-16 flex lg:flex flex-shrink-0 flex-col w-64 transition-width duration-75"
        aria-label="Sidebar"
    >
        <div
            class="relative flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white pt-0"
        >
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex-1 px-3 bg-white divide-y space-y-1">
                    <ul class="space-y-2 pb-2">
                        <li v-for="(list, index) in sidebarList" :key="index">
                            <RouterLink
                                class="text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group"
                                :to="list.link"
                                exact
                            >
                                <span class="ml-3">{{ list.title }}</span>
                            </RouterLink>
                        </li>
                        <li v-if="rootAdmin">
                            <RouterLink
                                class="text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group"
                                to="/admin/admins"
                                exact
                            >
                                <span class="ml-3">管理者管理</span>
                            </RouterLink>
                        </li>
                        <li>
                            <a
                                class="text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group cursor-pointer"
                                @click="logout"
                            >
                                <span class="ml-3">ログアウト</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
</template>
<script setup>
import { computed } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const router = useRouter();
const store = useStore();
const admin = computed(() => store.state.admin.admin);
const rootAdmin = computed(() => store.getters["admin/rootAdmin"]);

const sidebarList = [
    { title: "通知管理", link: "/admin/notifications" },
    { title: "ユーザー管理", link: "/admin/users" },
    { title: "退会ユーザー管理", link: "/admin/withdrawal" },
    // { title: "記事管理", link: "/admin/posts" },
    // { title: "報告管理", link: "/admin/reports" },
    // { title: "カテゴリ管理", link: "/admin/categories" },
    // { title: "ハッシュタグ管理", link: "/admin/hashtag" },
    // { title: "管理者管理",link:"/admin/admins"},
    // { title: "管理者作成記事管理", link: "admin-posts" },
];

const logout = async () => {
    try {
        await store.dispatch("admin/logout");
        router.push("/admin/login");
    } catch (err) {
        console.log(err);
    }
};
</script>
