<template>
    <table class="table-fixed min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th
                    scope="col"
                    class="p-4 cursor-pointer"
                    :class="addArrowClass(sort_key, column.name, sort_asc)"
                    v-for="(column, index) in columns"
                    :key="index"
                    @click="emitSortBy(sort_key, column.name)"
                >
                    {{ column.title }}
                </th>
            </tr>
        </thead>
        <tbody v-if="rows.length" class="bg-white divide-y divide-gray-200">
            <tr v-for="row in rows" :key="row.id" class="hover:bg-gray-100">
                <td class="p-4 w-4">
                    {{ row.id }}
                </td>
                <td
                    class="p-4 whitespace-nowrap text-base font-medium text-gray-900 text-center"
                >
                    {{ row.email }}
                </td>
                <td
                    class="p-4 whitespace-nowrap text-base font-medium text-gray-900 text-center"
                >
                    {{ row.name }}
                </td>
                <td
                    class="p-4 whitespace-nowrap text-base font-medium text-gray-900 text-center"
                >
                    {{ customFormat(row.created_at) }}
                </td>
                <td
                    v-if="row.deleted_at"
                    class="p-4 whitespace-nowrap text-base font-medium text-gray-900 text-center"
                >
                    {{ customFormat(row.deleted_at) }}
                </td>
                <td
                    class="p-4 whitespace-nowrap text-base font-normal text-gray-900 text-center"
                >
                    <div v-if="row.available == 1" class="flex items-center">
                        <div
                            class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"
                        ></div>
                        利用可能
                    </div>
                    <div v-if="row.available == 0" class="flex items-center">
                        <div
                            class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"
                        ></div>
                        利用不可
                    </div>
                </td>
                <td class="p-4 whitespace-nowrap space-x-2">
                    <EditButton
                        name="Edit"
                        :link="`/${props.link}/${row.id}/${props.editRoute}`"
                    />

                    <DeleteButton
                        name="Delete"
                        @click.prevent="openDeleteModal(row.id)"
                    />
                </td>
                <DeleteModal
                    v-if="showDeleteModal"
                    @close="closeDeleteModal()"
                    @deleteFlag="deleteAction()"
                />
            </tr>
        </tbody>
        <template v-else>
            <tbody class="text-center">
                データがありません。
            </tbody>
        </template>
    </table>
</template>
<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";
import EditButton from "@/components/parts/admin/button/EditButton.vue";
import DeleteButton from "@/components/parts/admin/button/DeleteButton.vue";
import DeleteModal from "@/components/templates/admin/modal/DeleteModal.vue";
import { OK } from "@/util";
import table from "@/functions/table";

const {
    customFormat,
    addArrowClass,
    sortBy,
    sort_key,
    sort_asc,
    searchKeyward,
} = table();

const props = defineProps({
    columns: { type: Array, required: true },
    rows: { type: Array, required: true },
    link: { type: String, required: true },
    editRoute: { type: String, required: true },
});

const store = useStore();
const emit = defineEmits();

const errorMessage = ref("");
const showDeleteModal = ref(false);
const showSearchUserModal = ref(false);
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
const openSearchUserModal = () => {
    showSearchUserModal.value = true;
};
const closeSearchUserModal = () => {
    showSearchUserModal.value = false;
};
const emitSortBy = (sort_key, name) => {
    sortBy(sort_key, name);
    emit("sortBy", sort_key, name);
};

const deleteAction = async () => {
    try {
        await axios
            .delete(`/api/${props.link}/${deleteId.value}`)
            .then((response) => {
                if (response.status !== OK) {
                    store.commit("error/setCode", response.status);
                    return false;
                }
            });
        emit("updateData", true);
        await closeDeleteModal();
        await store.commit("message/setContent", {
            content: "削除しました。",
            timeout: 6000,
        });
    } catch (err) {
        console.log(err);
    }
};
</script>
<style scoped>
/* 管理画面テーブルソート */
.asc::after {
    content: "↓";
}
.desc::after {
    content: "↑";
}
</style>
