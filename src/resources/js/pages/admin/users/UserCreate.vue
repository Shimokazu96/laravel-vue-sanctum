<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="ユーザー登録" />
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
                        :to="`/admin/users`"
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
import { OK } from "@/util";
import BaseInput from "@/components/parts/admin/BaseInput.vue";
import BaseLabel from "@/components/parts/admin/BaseLabel.vue";
import BaseSelect from "@/components/parts/admin/BaseSelect.vue";
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
        name: "ユーザー一覧",
        path: "/admin/users",
    },
    {
        name: "ユーザー登録",
    },
];
const store = useStore();
const router = useRouter();

const registerForm = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    available: 1,
});
const options = [
    { name: "利用可能", value: 1 },
    { name: "利用不可", value: 0 },
];
const errorMessage = ref("");

const apiStatus = computed(() => store.state.admin.apiStatus);
const registerErrors = computed(() => store.state.admin.registerErrorMessages);
const rootAdmin = computed(() => store.getters["admin/rootAdmin"]);

const admin = computed(() => store.state.admin.admin);

const register = async () => {
    try {
        await axios
            .post("/api/admin/users", registerForm.value)
            .then((response) => {
                if (response.status !== OK) {
                    errorMessage.value = response.data.errors;
                    store.commit("error/setCode", response.status);
                    return false;
                }
                console.log(response);
                router.push("/admin/users");
            });
    } catch (err) {
        console.log(err);
    }
};

const clearError = () => {
    store.commit("admin/setLoginErrorMessages", null);
    store.commit("admin/setRegisterErrorMessages", null);
};
clearError();
</script>
