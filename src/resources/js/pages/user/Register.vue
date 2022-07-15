<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="w-2/5 border bg-white">
        <div class="my-12 text-center">
          <h2 class="text-3xl mb-2 font-bold">ユーザの登録</h2>
          <form @submit.prevent="register">
            <div v-if="registerErrors" class="my-4 text-red-500">
              <ul v-if="registerErrors.name">
                <li v-for="msg in registerErrors.name" :key="msg" class="flex bg-red-100 rounded-lg p-4 m-auto mb-4 w-3/5 text-sm text-red-700">{{ msg }}</li>
              </ul>
              <ul v-if="registerErrors.email">
                <li v-for="msg in registerErrors.email" :key="msg" class="flex bg-red-100 rounded-lg p-4 m-auto mb-4 w-3/5 text-sm text-red-700">
                  {{ msg }}
                </li>
              </ul>
              <ul v-if="registerErrors.password">
                <li v-for="msg in registerErrors.password" :key="msg" class="flex bg-red-100 rounded-lg p-4 m-auto mb-4 w-3/5 text-sm text-red-700">
                  {{ msg }}
                </li>
              </ul>
            </div>
            <div class="mb-2">
              <input
                type="text"
                placeholder="名前太郎"
                class="text-xl w-3/5 p-3 border rounded"
                id="username"
                v-model="registerForm.name"
              />
            </div>
            <div class="mb-2">
              <input
                type="email"
                placeholder="you@example.com"
                class="text-xl w-3/5 p-3 border rounded"
                id="email"
                v-model="registerForm.email"
              />
            </div>
            <div class="mb-2">
              <input
                type="password"
                class="text-xl w-3/5 p-3 border rounded"
                placeholder="パスワード"
                id="password"
                v-model="registerForm.password"
              />
            </div>
            <div class="mb-2">
              <input
                type="password"
                class="text-xl w-3/5 p-3 border rounded"
                placeholder="パスワード(確認)"
                id="password-confirmation"
                v-model="registerForm.password_confirmation"
              />
            </div>
            <button
              type="submit"
              class="text-xl w-3/5 bg-blue-600 text-white py-2 rounded"
            >
              ユーザの登録
            </button>
          </form>
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

    const registerForm = reactive({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    });

    const registerErrors = computed(
      () => store.state.user.registerErrorMessages
    );
    const apiStatus = computed(() => store.state.user.apiStatus);

    const register = async () => {
      try {
        await store.dispatch("user/register", registerForm);
        if (apiStatus.value) {
          router.push("/email/verify");
        }
      } catch (err) {
        console.log(err);
      }
    };

    const clearError = () => {
      store.commit("user/setRegisterErrorMessages", null);
    };
    clearError();

    return {
      registerForm,
      registerErrors,
      register,
    };
  },
});
</script>
