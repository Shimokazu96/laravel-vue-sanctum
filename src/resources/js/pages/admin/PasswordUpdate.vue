<template>
    <div>
        <DashBoardTitle
            :breadCrumbs="breadCrumbs"
            title="メールアドレス・パスワード設定"
        />
        <div class="py-3">
            <form class="m-auto w-[90%]" @submit.prevent="updateEmail">
                <div v-if="updateEmailErrors">
                    <div v-for="error in updateEmailErrors" :key="error.id">
                        <DangerMessage :message="error[0]" />
                    </div>
                </div>
                <div v-if="updatePasswordErrors">
                    <div v-for="error in updatePasswordErrors" :key="error.id">
                        <DangerMessage :message="error[0]" />
                    </div>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="名前" />
                    <BaseInput
                        type="email"
                        name="email"
                        v-model="adminInfo.name"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="メールアドレス" />
                    <BaseInput
                        type="email"
                        name="email"
                        v-model="adminInfo.email"
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
import { useRouter } from "vue-router";
import UpdateMessage from "@/components/parts/admin/UpdateMessage.vue";
import { OK } from "@/util";
import BaseLabel from "@/components/parts/admin/BaseLabel.vue";
import BaseInput from "@/components/parts/admin/BaseInput.vue";
import CancelButton from "@/components/parts/admin/button/CancelButton.vue";
import UpdateButton from "@/components/parts/admin/button/UpdateButton.vue";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";
import DangerMessage from "@/components/parts/admin/DangerMessage.vue";

const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
});
const router = useRouter();
const store = useStore();

const id = ref(props.id);
const breadCrumbs = [
    {
        name: "ダッシュボード",
        path: "/admin",
    },
    {
        name: "マイページ",
        path: `/admin/${id.value}`,
    },
    {
        name: "メールアドレス・パスワード設定",
    },
];
const adminInfo = ref({
    name: "",
    email: "",
});

const passwordForm = reactive({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updateEmailErrors = computed(
    () => store.state.admin.updateEmailErrorMessages
);
const updatePasswordErrors = computed(
    () => store.state.admin.updatePasswordErrorMessages
);
const apiStatus = computed(() => store.state.admin.apiStatus);
const emailVerified = computed(() => store.getters["admin/emailVerified"]);

const getUser = async () => {
    await axios.get("/api/admin").then((response) => {
        if (response.status !== OK) {
            store.commit("error/setCode", response.status);
            return false;
        }
        //メールアドレスを変更した場合認証ページにリダイレクトさせる
        if (!response.data.email_verified_at) {
            router.push("/admin/email/verify");
        }
        adminInfo.value = response.data;
    });
};
getUser();

const updateEmail = async () => {
    try {
        await store.dispatch("admin/updateEmail", adminInfo.value);
        if (apiStatus.value) {
            store.commit("message/setContent", {
                content: "更新しました。",
                timeout: 6000,
            });
            //メールアドレスを変更した場合認証ページにリダイレクトさせる
            if (!emailVerified.value) {
                router.push("/admin/email/verify");
            }
        }
    } catch (err) {
        console.log(err);
    }
};

const updatePassword = async () => {
    try {
        await store.dispatch("admin/updatePassword", passwordForm);
        if (apiStatus.value) {
            // パスワードが更新されたら強制的にログアウトする
            router.push("/admin/login");
        }
    } catch (err) {
        console.log(err);
    }
};
const clearError = () => {
    store.commit("admin/setUpdateEmailErrorMessages", null);
    store.commit("admin/setUpdatePasswordErrorMessages", null);
};
clearError();
</script>
