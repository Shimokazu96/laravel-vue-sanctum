<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="退会ユーザー一覧" />
        <SearchBox
            v-model="searchKeyward"
            @openSearchModal="openSearchWithdrawalUserModal()"
        />
        <SearchWithdrawalUserModal
            v-if="showSearchWithdrawalUserModal"
            @close="closeSearchWithdrawalUserModal()"
            link="/admin/users"
            :per_page="Number(per_page)"
            @updateData="getUsers"
        />
        <div class="py-3">
            <div class="p-4">
                <PerPageSelect
                    :per_page="Number(per_page)"
                    @changeData="getUsers"
                />
            </div>
            <UserTableList
                :columns="columns"
                :rows="sortedUsers"
                link="admin/users"
                editRoute="userpage"
                :updateData="getUsers"
                @sortBy="sortBy"
            />
            <div class="p-4">
                <div v-if="sortedUsers.length">
                    <Pagination
                        :current_page="Number(current_page)"
                        :last_page="Number(last_page)"
                        :per_page="Number(per_page)"
                        @changePage="getUsers"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";
import EditButton from "@/components/parts/admin/button/EditButton.vue";
import DeleteButton from "@/components/parts/admin/button/DeleteButton.vue";
import CreateButton from "@/components/parts/admin/button/CreateButton.vue";
import SearchButton from "@/components/parts/admin/button/SearchButton.vue";
import DeleteModal from "@/components/templates/admin/modal/DeleteModal.vue";
import SearchInput from "@/components/parts/admin/SearchInput.vue";
import SearchWithdrawalUserModal from "@/components/templates/admin/modal/SearchWithdrawalUserModal.vue";
import UpdateMessage from "@/components/parts/admin/UpdateMessage.vue";
import { OK } from "@/util";
import BreadCrumb from "@/components/parts/admin/BreadCrumb.vue";
import table from "@/functions/table";
import Pagination from "@/components/parts/admin/Pagination.vue";
import PerPageSelect from "@/components/parts/admin/PerPageSelect.vue";
import UserTableList from "@/components/templates/admin/UserTableList.vue";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";
import SearchBox from "@/components/templates/admin/SearchBox.vue";

const {
    filteredTable,
    sortedTable,
    sort_key,
    sort_asc,
    sortBy,
    searchKeyward,
    per_page,
    current_page,
    last_page,
} = table();

const router = useRouter();
const route = useRoute();

const store = useStore();

const breadCrumbs = [
    {
        name: "ダッシュボード",
        path: "/admin",
    },
    {
        name: "退会ユーザー一覧",
    },
];

const rows = ref([]);
const columns = [
    {
        title: "ID",
        name: "id",
    },
    {
        title: "Email",
        name: "email",
    },
    {
        title: "ユーザー名",
        name: "name",
    },
    {
        title: "登録日",
        name: "created_at",
    },
    {
        title: "退会日",
        name: "deleted_at",
    },
    {
        title: "利用フラグ",
        name: "available",
    },
    {
        title: "",
    },
];

const getUsers = async (routeQuery, currentPage, perPage) => {
    let query = Object.assign({}, routeQuery);
    query.page = currentPage;
    query.per_page = perPage;
    await axios
        .get(`/api/admin/withdrawal`, {
            params: query,
        })
        .then((response) => {
            let params = response.data.params ?? "";
            router.push({ path: "/admin/withdrawal", query: params });
            rows.value = response.data.users.data;
            current_page.value = response.data.users.current_page;
            last_page.value = response.data.users.last_page;
            per_page.value = response.data.users.per_page;
        });
};

getUsers(route.query, current_page.value, per_page.value);

const showDeleteModal = ref(false);
const showSearchWithdrawalUserModal = ref(false);
const deleteFlag = ref(false);
const deleteId = ref(null);

const openDeleteModal = (id) => {
    showDeleteModal.value = true;
    deleteId.value = id;
};
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deleteId.value = null;
};
const openSearchWithdrawalUserModal = () => {
    showSearchWithdrawalUserModal.value = true;
};
const closeSearchWithdrawalUserModal = () => {
    showSearchWithdrawalUserModal.value = false;
};

const deleteUser = async () => {
    try {
        await axios
            .delete(`/api/admin/users/${deleteId.value}`)
            .then((response) => {
                if (response.status !== OK) {
                    errorMessage.value = response.data.errors;
                    store.commit("error/setCode", response.status);
                    return false;
                }
            });
        await getUsers(route.query);
        await closeDeleteModal();
        await store.commit("message/setContent", {
            content: "削除しました。",
            timeout: 6000,
        });
    } catch (err) {
        console.log(err);
    }
};

const searchFlag = ref(false);

const filteredUsers = computed(() =>
    filteredTable(rows.value, searchKeyward.value)
);

const sortedUsers = computed(() =>
    sortedTable(sort_key.value, sort_asc.value, filteredUsers.value)
);
</script>
