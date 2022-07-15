<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="管理者編集" />
        <div class="py-3">
            <form class="m-auto w-[90%]" @submit.prevent="register">
                <div v-if="errorMessage">
                    <div v-for="error in errorMessage" :key="error.id">
                        <DangerMessage :message="error[0]" />
                    </div>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="ユーザー名" />
                        <BaseInput
                            type="text"
                            name="name"
                            v-model="registerForm.name"
                        />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="Email" />
                    <BaseInput
                        type="email"
                        name="email"
                        v-model="registerForm.email"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="Password" />
                    <BaseInput
                        type="password"
                        name="password"
                        v-model="registerForm.password"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="Password (confirm)" />
                    <BaseInput
                        type="password"
                        name="password-confirmation"
                        v-model="registerForm.password_confirmation"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="利用フラグ" />
                    <BaseSelect
                        labelName="利用フラグ"
                        name="available"
                        class="md:w-6/12"
                        v-model="registerForm.available"
                        :options="options"
                    ></BaseSelect>
                </div>

                <div class="mt-5 flex justify-center align-center">
                    <CancelButton
                        name="キャンセル"
                        class="mr-2"
                        :to="`/admin/admins`"
                    />
                    <UpdateButton
                        class="w-[80px] text-center"
                        type="submit"
                        name="登録"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import UpdateMessage from "@/components/parts/admin/UpdateMessage.vue";
import { OK } from "@/util";
import BaseInput from "@/components/parts/admin/BaseInput.vue";
import BaseLabel from "@/components/parts/admin/BaseLabel.vue";
import BaseSelect from "@/components/parts/admin/BaseSelect.vue";
import BreadCrumb from "@/components/parts/admin/BreadCrumb.vue";
import CancelButton from "@/components/parts/admin/button/CancelButton.vue";
import UpdateButton from "@/components/parts/admin/button/UpdateButton.vue";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";
import DangerMessage from "@/components/parts/admin/DangerMessage.vue";

const breadCrumbs = [
    {
        name: "ダッシュボード",
        path: "/admin",
    },
    {
        name: "管理者一覧",
        path: "/admin/admins",
    },
    {
        name: "管理者編集",
    },
];
const store = useStore();
const router = useRouter();
const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
});

const registerForm = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});
const options = [
    { name: "利用可能", value: 1 },
    { name: "利用不可", value: 0 },
];

const errorMessage = ref("");
const id = ref(props.id);
const apiStatus = computed(() => store.state.admin.apiStatus);
const registerErrors = computed(() => store.state.admin.registerErrorMessages);
const rootAdmin = computed(() => store.getters["admin/rootAdmin"]);

const updateAdmin = async () => {
    try {
        await axios
            .put(`/api/admin/admins/${id.value}`, registerForm.value)
            .then((response) => {
                if (response.status !== OK) {
                    errorMessage.value = response.data.errors;
                    store.commit("error/setCode", response.status);
                    return false;
                }
                console.log(response);
                getAdmin(id.value);
                clearError();
                store.commit("message/setContent", {
                    content: "更新しました。",
                    timeout: 3000,
                });
            });
    } catch (err) {
        console.log(err);
    }
};

const getAdmin = async (id) => {
    await axios
        .get(`/api/admin/admins/${id}`)
        .then((response) => {
            console.log(response);
            registerForm.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

getAdmin(id.value);

const clearError = () => {
    store.commit("admin/setLoginErrorMessages", null);
    store.commit("admin/setRegisterErrorMessages", null);
};
clearError();
</script>
