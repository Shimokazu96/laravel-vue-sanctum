<template>
 <div
        class="fixed w-full h-full bg-slate-800 bg-opacity-50 flex justify-center items-center top-0 left-0 z-40"
    >
        <div
            class="container mx-auto w-11/12 md:w-2/3 max-w-lg bg-white py-8 px-5 md:px-10 rounded-md z-50"
        >
            <div class="flex items-start justify-between border-b rounded-t">
                <h3 class="text-xl font-semibold">こだわり検索</h3>
            </div>
            <form class="g-3">
                <div class="mt-2">
                    <BaseLabel labelName="登録日" />
                    <div class="flex justify-center align-center">
                        <BaseInput
                            type="date"
                            name="created_at_start"
                            v-model="searchForm.created_at_start"
                        />
                        <span class="p-2.5">~</span>
                        <BaseInput
                            type="date"
                            name="created_at_end"
                            v-model="searchForm.created_at_end"
                            :disabled="
                                searchForm.created_at_start ? false : true
                            "
                        />
                    </div>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="退会日" />
                    <div class="flex justify-center align-center">
                        <BaseInput
                            type="date"
                            name="created_at_start"
                            v-model="searchForm.deleted_at_start"
                        />
                        <span class="p-2.5">~</span>
                        <BaseInput
                            type="date"
                            name="last_login_at_end"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                            v-model="searchForm.last_login_at_end"
                            :disabled="
                                searchForm.deleted_at_start ? false : true
                            "
                        />
                    </div>
                </div>
                <!-- <div class="mt-2">
                    <BaseLabel labelName="利用フラグ" />
                    <BaseSelect
                        labelName="利用フラグ"
                        name="available"
                        class="md:w-6/12"
                        v-model="searchForm.available"
                        :options="options"
                    ></BaseSelect>
                </div> -->
                <div class="mt-2">
                    <BaseLabel labelName="ユーザー名または氏名" />
                    <BaseInput
                        type="text"
                        name="name"
                        placeholder="Name"
                        v-model="searchForm.name"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="Email" />
                    <BaseInput
                        type="email"
                        name="email"
                        placeholder="Email"
                        v-model="searchForm.email"
                    />
                </div>
                <div class="mt-2 flex justify-center align-center">
                    <CloseButton
                        name="close"
                        class="mr-2"
                        @click="closeModal()"
                    />
                    <UpdateButton @click.prevent="searchClick()" name="検索" />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import BaseInput from "@/components/parts/admin/BaseInput.vue";
import CloseButton from "@/components/parts/admin/button/CloseButton.vue";
import UpdateButton from "@/components/parts/admin/button/UpdateButton.vue";

const props = defineProps({
    showModal: {
        type: Boolean,
        required: true,
    },
    link: {
        type: String,
        required: true,
    },
    per_page: {
        type: Number,
        required: true,
    },
});
const emit = defineEmits();
const rows = ref({});
const searchForm = ref({
    name: "",
    email: "",
    created_at_start: "",
    created_at_end: "",
    deleted_at_start: "",
    deleted_at_end: "",
});
// const options = [
//     { name: "利用可能", value: 1 },
//     { name: "利用不可", value: 0 },
// ];

const closeModal = () => {
    emit("close");
};
const searchClick = async () => {
    await emit("updateData", searchForm.value, 1, props.per_page);
    await emit("close");
};
</script>
