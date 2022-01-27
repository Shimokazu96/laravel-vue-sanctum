<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="w-2/5 border bg-white">
        <div v-if="apiStatus" class="my-12 text-center">
          <div
            v-if="getMessage"
            class="
              bg-green-100
              rounded-lg
              p-4
              m-auto
              mb-4
              w-3/5
              text-sm text-green-700
            "
            role="alert"
          >
            <div>
              {{ getMessage }}
            </div>
          </div>
        </div>
        <div v-if="!apiStatus" class="my-12 text-center">
          <h2 class="text-3xl mb-2 font-bold">パスワード再設定</h2>
          <form @submit.prevent="register">
            <div v-if="registerErrors" class="my-4 text-red-500">
              <ul v-if="registerErrors.email">
                <li
                  v-for="msg in registerErrors.email"
                  :key="msg"
                  class="
                    flex
                    bg-red-100
                    rounded-lg
                    p-4
                    m-auto
                    mb-4
                    w-3/5
                    text-sm text-red-700
                  "
                >
                  {{ msg }}
                </li>
              </ul>
              <ul v-if="registerErrors.password">
                <li
                  v-for="msg in registerErrors.password"
                  :key="msg"
                  class="
                    flex
                    bg-red-100
                    rounded-lg
                    p-4
                    m-auto
                    mb-4
                    w-3/5
                    text-sm text-red-700
                  "
                >
                  {{ msg }}
                </li>
              </ul>
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
              パスワードリセット
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, ref, computed } from "vue";
import { useStore } from "vuex";

export default defineComponent({
  props: {
    token: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const store = useStore();

    const getMessage = ref("");

    const registerForm = reactive({
      token: props.token,
      email: "",
      password: "",
      password_confirmation: "",
    });

    const registerErrors = computed(
      () => store.state.auth.registerErrorMessages
    );
    const apiStatus = computed(() => store.state.auth.apiStatus);

    const register = async () => {
      try {
        await store.dispatch("auth/resetPassword", registerForm);
        if (apiStatus.value) {
          getMessage.value = "パスワードを更新しました。";
        }
      } catch (err) {
        console.log("Failure");
      }
    };

    const clearError = () => {
      store.commit("auth/setRegisterErrorMessages", null);
    };
    clearError();

    return {
      getMessage,
      registerForm,
      apiStatus,
      registerErrors,
      register,
    };
  },
});
</script>
