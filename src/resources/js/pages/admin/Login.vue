<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-gray-100"
    >
        <div
            class="flex flex-col bg-white shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-3xl w-full max-w-lg"
        >
            <div class="mt-10" v-if="auth_step == 1">
                <form class="form" @submit.prevent="firstAuth">
                    <div v-if="loginErrors">
                        <div v-for="msg in loginErrors" :key="msg">
                            <DangerMessage :message="msg[0]" />
                        </div>
                    </div>
                    <div class="flex flex-col mb-5">
                        <label
                            for="email"
                            class="mb-1 text-xs tracking-wide text-gray-600"
                            >E-Mail Address:</label
                        >
                        <div class="relative">
                            <div
                                class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400"
                            >
                                <i class="fas fa-at text-blue-500"></i>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="w-full border rounded h-12 px-4 focus:outline-none"
                                placeholder="Enter your email"
                                v-model="loginForm.email"
                            />
                        </div>
                    </div>
                    <div class="flex flex-col mb-6">
                        <label
                            for="password"
                            class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600"
                            >Password:</label
                        >
                        <div class="relative">
                            <div
                                class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400"
                            >
                                <span>
                                    <i class="fas fa-lock text-blue-500"></i>
                                </span>
                            </div>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="w-full border rounded h-12 px-4 focus:outline-none"
                                placeholder="Enter your password"
                                v-model="loginForm.password"
                            />
                        </div>
                    </div>

                    <div class="flex w-full">
                        <button
                            type="submit"
                            class="flex mt-2 items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-blue-500 hover:bg-blue-600 rounded-lg py-2 w-full transition duration-150 ease-in"
                        >
                            <span class="mr-2 uppercase">Sign In</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="mt-10" v-if="auth_step == 2">
                <form class="form" @submit.prevent="secondAuth">
                    <div v-if="loginErrors" class="text-red-500">
                        <ul v-if="loginErrors">
                            <DangerMessage :message="loginErrors" />
                        </ul>
                    </div>
                    <p class="mt-2 text-gray-600">
                        ２段階認証のパスワードをメールアドレスに送信しました。（有効時間：10分間）
                    </p>
                    <div class="flex flex-col mb-5">
                        <label
                            for="email"
                            class="mb-1 text-xs tracking-wide text-gray-600"
                            >認証コード</label
                        >
                        <div class="relative">
                            <div
                                class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400"
                            >
                                <i class="fas fa-at text-blue-500"></i>
                            </div>
                            <input
                                id="two_factor_auth_token"
                                type="text"
                                name="two_factor_auth_token"
                                class="w-full border rounded h-12 px-4 focus:outline-none"
                                placeholder="Enter your email"
                                v-model="SecondLoginForm.two_factor_auth_token"
                            />
                        </div>
                    </div>

                    <div class="flex w-full">
                        <button
                            type="submit"
                            class="flex mt-2 items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-blue-500 hover:bg-blue-600 rounded-lg py-2 w-full transition duration-150 ease-in"
                        >
                            <span class="mr-2 uppercase">Sign In</span>
                        </button>
                    </div>
                </form>
                <a
                    class="mt-2 flex justify-end text-gray-400 text-sm underline self-center md:self-auto mt-4 md:mt-0 cursor-pointer"
                    @click="resetAuth()"
                >
                    ログイン画面に戻る
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, computed, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { OK } from "@/util";
import DangerMessage from "@/components/parts/admin/DangerMessage.vue";

const store = useStore();
const router = useRouter();

const auth_step = ref(1);
const loginForm = reactive({
    email: "",
    password: "",
});
const SecondLoginForm = reactive({
    admin_id: "",
    two_factor_auth_token: "",
});

const apiStatus = computed(() => store.state.admin.apiStatus);
const loginErrors = computed(() => store.state.admin.loginErrorMessages);
const registerErrors = computed(() => store.state.admin.registerErrorMessages);

const admin = computed(() => store.state.admin.admin);

const firstAuth = async () => {
    await axios
        .post("/api/admin/first-auth", loginForm)
        .then((response) => {
            if (response.status !== OK) {
                store.commit(
                    "admin/setLoginErrorMessages",
                    response.data.errors
                );
                store.commit("error/setCode", response.status);
                return false;
            }
            console.log(response);
            clearError();
            SecondLoginForm.admin_id = response.data.admin_id;
            auth_step.value = 2;
            loginForm.email = "";
            loginForm.password = "";
        })
        .catch((err) => {
            console.log(err);
        });
};

const secondAuth = async () => {
    try {
        await store.dispatch("admin/login", SecondLoginForm);
        if (apiStatus.value) {
            router.push("/admin");
        }
    } catch (err) {
        console.log(err);
    }
};
const resetAuth = async () => {
    auth_step.value = 1;
    store.commit("admin/setLoginErrorMessages", null);
};

const clearError = () => {
    store.commit("admin/setLoginErrorMessages", null);
    store.commit("admin/setRegisterErrorMessages", null);
};
clearError();
</script>

