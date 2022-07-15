<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="w-2/5 border bg-white">
        <div class="my-12 text-center">
          <h2 class="text-3xl mb-2 font-bold">パスワードリセット</h2>
          <div
            v-if="getMessage"
            class="bg-green-100 rounded-lg p-4 m-auto mb-4 w-3/5 text-sm text-green-700"
            role="alert"
          >
            <div>
              {{ getMessage }}
            </div>
          </div>
          <div v-if="forgotPasswordErrors" class="my-4">
            <ul v-if="forgotPasswordErrors.email">
              <li
                v-for="msg in forgotPasswordErrors.email"
                :key="msg"
                class="flex bg-red-100 rounded-lg p-4 m-auto mb-4 w-3/5 text-sm text-red-700"
              >
                {{ msg }}
              </li>
            </ul>
          </div>
          <form class="form" @submit.prevent="submit">
            <div class="mb-2">
              <input
                type="email"
                placeholder="you@example.com"
                class="text-xl w-3/5 p-3 border rounded"
                id="email"
                v-model="emailForm.email"
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
import { defineComponent, computed, ref, reactive } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const getMessage = ref("");
    const emailForm = reactive({
      email: "",
    });
    const store = useStore();
    const router = useRouter();
    const forgotPasswordErrors = computed(
      () => store.state.user.forgotPasswordErrorMessages
    );
    const apiStatus = computed(() => store.state.user.apiStatus);

    const closeMessage = () => {
      getMessage.value = "";
    };

    const submit = async () => {
      try {
        await store.dispatch("user/forgotPassword", emailForm);
        if (apiStatus.value) {
          clearError();
          getMessage.value = "メールを送信しました。";
          setTimeout(closeMessage, 6000);
        }
      } catch (err) {
        console.log(err);
        getMessage.value = "メールを送信に失敗しました。";
        setTimeout(closeMessage, 6000);
      }
    };

    const clearError = () => {
      store.commit("user/setForgotPasswordErrorMessages", null);
    };
    clearError();

    return {
      getMessage,
      forgotPasswordErrors,
      emailForm,
      submit,
    };
  },
});
</script>
