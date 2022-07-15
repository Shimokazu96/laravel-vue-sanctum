<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="管理者一覧" />
        <div
            class="px-4 block sm:flex items-center md:divide-x md:divide-gray-100"
        >
            <div
                class="flex items-center justify-between sm:justify-end w-full"
            >
                <!-- <div class="block md:flex space-x-1">
                            <SearchButton
                                name="こだわり検索"
                                @click.prevent="openSearchUserModal()"
                            />
                        </div> -->
                <CreateButton name="ユーザー登録" link="/admin/admins/create" />
            </div>
        </div>
        <div class="py-3">
            <!-- <SearchUserModal
                v-if="showSearchUserModal"
                @close="closeSearchUserModal()"
                link="/admin/users"
                :per_page="Number(per_page)"
                @updateData="deleteAdmin"
            /> -->

            <!-- <div class="p-4">
                <PerPageSelect
                    :per_page="Number(per_page)"
                    @changeData="getAdmins"
                />
            </div> -->
            <UserTableList
                :columns="columns"
                :rows="sortedUsers"
                link="admin/admins"
                editRoute="edit"
                @updateData="getAdmins"
                @sortBy="sortBy"
            />
            <!-- <div class="p-4">
                <div v-if="sortedUsers.length">
                    <Pagination
                        :current_page="Number(current_page)"
                        :last_page="Number(last_page)"
                        :per_page="Number(per_page)"
                        @changePage="getAdmins"
                    />
                </div>
            </div> -->
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
import SearchUserModal from "@/components/templates/admin/modal/SearchUserModal.vue";
import UpdateMessage from "@/components/parts/admin/UpdateMessage.vue";
import { OK } from "@/util";
import BreadCrumb from "@/components/parts/admin/BreadCrumb.vue";
import table from "@/functions/table";
import Pagination from "@/components/parts/admin/Pagination.vue";
import PerPageSelect from "@/components/parts/admin/PerPageSelect.vue";
import UserTableList from "@/components/templates/admin/UserTableList.vue";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";

const {
    customFormat,
    filteredTable,
    sortedTable,
    addArrowClass,
    sortBy,
    sort_key,
    sort_asc,
} = table();

const breadCrumbs = [
    {
        name: "ダッシュボード",
        path: "/admin",
    },
    {
        name: "管理者一覧",
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
        title: "利用フラグ",
        name: "available",
    },
    {
        title: "",
    },
];
const store = useStore();

const getAdmins = async () => {
    await axios.get("/api/admin/admins").then((response) => {
        rows.value = response.data;
    });
};

getAdmins();

const showDeleteModal = ref(false);
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

const searchKeyward = ref("");
const filteredUsers = computed(() =>
    filteredTable(rows.value, searchKeyward.value)
);

const sortedUsers = computed(() =>
    sortedTable(sort_key.value, sort_asc.value, filteredUsers.value)
);
</script>
