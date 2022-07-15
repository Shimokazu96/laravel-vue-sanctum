<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="メールアドレス・パスワード設定" />
        <div class="py-3">
            <form class="m-auto w-[90%]" @submit.prevent="updateEmail">
                <div v-if="errorMessage">
                    <div v-for="error in errorMessage" :key="error.id">
                        <DangerMessage :message="error[0]" />
                    </div>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="メールアドレス" />
                    <BaseInput
                        type="email"
                        name="email"
                        v-model="email"
                        :disabled="disabled"
                    />
                </div>

                <div class="mt-5 flex justify-end align-center">
                    <UpdateButton
                        type="submit"
                        name="メールアドレスを更新する"
                        :disabled="disabled"
                    />
                </div>
            </form>
        </div>
        <div class="py-3">
            <form class="m-auto w-[90%]" @submit.prevent="updatePassword">
                <div class="mt-2">
                    <BaseLabel labelName="現在のパスワード" />
                    <BaseInput
                        type="password"
                        name="current_password"
                        id="current_password"
                        v-model="passwordForm.current_password"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="新しいパスワード" />
                    <BaseInput
                        type="password"
                        name="password"
                        v-model="passwordForm.password"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="パスワード (確認用)" />
                    <BaseInput
                        type="password"
                        name="password-confirmation"
                        v-model="passwordForm.password_confirmation"
                        :disabled="disabled"
                    />
                </div>

                <div class="mt-5 flex justify-end align-center">
                    <UpdateButton
                        type="submit"
                        name="パスワードを更新する"
                        :disabled="disabled"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref, reactive, computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import { OK } from "@/util";
import BaseLabel from "@/components/parts/admin/BaseLabel.vue";
import BaseInput from "@/components/parts/admin/BaseInput.vue";
import CancelButton from "@/components/parts/admin/button/CancelButton.vue";
import UpdateButton from "@/components/parts/admin/button/UpdateButton.vue";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";
import DangerMessage from "@/components/parts/admin/DangerMessage.vue";

const store = useStore();
const router = useRouter();
const route = useRoute();
const id = ref(route.params.id);

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
        path: `/admin/users/${id.value}/userpage`,
    },
    {
        name: "メールアドレス・パスワード設定",
    },
];
const email = ref("");
const deleted_at = ref("");

const passwordForm = ref({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const apiStatus = computed(() => store.state.user.apiStatus);
const emailVerified = computed(() => store.getters["user/emailVerified"]);
const disabled = computed(() => (deleted_at.value ? "disabled" : false));

const errorMessage = ref("");

const getUser = async () => {
    await axios.get(`/api/admin/users/${id.value}`).then((response) => {
        if (response.status !== OK) {
            store.commit("error/setCode", response.status);
            return false;
        }
        //メールアドレスを変更した場合認証ページにリダイレクトさせる
        // if (!response.data.email_verified_at) {
        //     router.push("/email/verify");
        // }
        email.value = response.data.email;
        deleted_at.value = response.data.deleted_at;
    });
};
getUser();

const updateEmail = async () => {
    await axios
        .put(`/api/admin/users/${id.value}/profile-information`, {
            email: email.value,
        })
        .then((response) => {
            if (response.status !== OK) {
                errorMessage.value = response.data.errors;

                store.commit("error/setCode", response.status);
                return false;
            }
            console.log(response.data);
            store.commit("message/setContent", {
                content: "更新しました。",
                timeout: 6000,
            });
        });
    await getUser();
};

const updatePassword = async () => {
    await axios
        .put(`/api/admin/users/${id.value}/password`, passwordForm.value)
        .then((response) => {
            if (response.status !== OK) {
                errorMessage.value = response.data.errors;

                store.commit("error/setCode", response.status);
                return false;
            }
            console.log(response.data);
            store.commit("message/setContent", {
                content: "更新しました。",
                timeout: 6000,
            });
        });
    await getUser();
};
</script>
