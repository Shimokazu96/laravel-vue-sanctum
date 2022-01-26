<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="w-2/5 border bg-white">
        <div class="my-12 text-center">
          <h2 class="text-3xl mb-2 font-bold">サインイン</h2>
          <form class="form" @submit.prevent="login">
            <div v-if="loginErrors" class="my-4">
              <ul v-if="loginErrors.email">
                <li v-for="msg in loginErrors.email" :key="msg" class="flex bg-red-100 rounded-lg p-4 m-auto mb-4 w-3/5 text-sm text-red-700">{{ msg }}</li>
              </ul>
              <ul v-if="loginErrors.password">
                <li v-for="msg in loginErrors.password" :key="msg" class="flex bg-red-100 rounded-lg p-4 m-auto mb-4 w-3/5 text-sm text-red-700">
                  {{ msg }}
                </li>
              </ul>
            </div>
            <div class="mb-2">
              <input
                type="email"
                placeholder="you@example.com"
                class="text-xl w-3/5 p-3 border rounded"
                id="login-email"
                v-model="loginForm.email"
              />
            </div>
            <div class="mb-2">
              <input
                type="password"
                class="text-xl w-3/5 p-3 border rounded"
                placeholder="パスワード"
                id="login-password"
                v-model="loginForm.password"
              />
            </div>
            <button
              type="submit"
              class="text-xl w-3/5 bg-blue-600 text-white py-2 rounded"
            >
              サインイン
            </button>
          </form>
          <RouterLink class="block mt-4 mt-2 text-blue-500" to="/forgot-password">
            パスワードを忘れた場合
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const store = useStore();
    const router = useRouter();

    const loginForm = reactive({
      email: "",
      password: "",
    });

    const loginErrors = computed(() => store.state.auth.loginErrorMessages);
    const apiStatus = computed(() => store.state.auth.apiStatus);

    const login = async () => {
      try {
        await store.dispatch("auth/login", loginForm);
        if (apiStatus.value) {
          router.push("/user");
        }
      } catch (err) {
        console.log("Failure");
      }
    };

    const clearError = () => {
      store.commit("auth/setLoginErrorMessages", null);
    };
    clearError();

    return {
      loginForm,
      loginErrors,
      login,
    };
  },
});
</script>
