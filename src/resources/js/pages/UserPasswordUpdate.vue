<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="w-2/5 border bg-white">
        <div class="my-12 text-center">
          <h2 class="text-3xl mb-2 font-bold">メールアドレス変更</h2>
          <form @submit.prevent="updateEmail">
            <div v-if="updateEmailErrors" class="my-4 text-red-500">
              <ul v-if="updateEmailErrors">
                <li
                  v-for="error in updateEmailErrors"
                  :key="error.id"
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
                  {{ error[0] }}
                </li>
              </ul>
            </div>
            <div v-if="updatePasswordErrors" class="my-4 text-red-500">
              <ul v-if="updatePasswordErrors">
                <li
                  v-for="error in updatePasswordErrors"
                  :key="error.id"
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
                  {{ error[0] }}
                </li>
              </ul>
            </div>
            <div class="mb-2">
              <input
                type="email"
                placeholder="you@example.com"
                class="text-xl w-3/5 p-3 border rounded"
                id="email"
                v-model="email"
              />
            </div>
            <button
              type="submit"
              class="text-xl w-3/5 bg-blue-600 text-white py-2 rounded"
            >
              メールアドレスを更新する
            </button>
          </form>
          <h2 class="text-3xl mt-2 mb-2 font-bold">パスワード変更</h2>
          <form class="form" @submit.prevent="updatePassword">
            <div class="mb-2">
              <input
                type="password"
                class="text-xl w-3/5 p-3 border rounded"
                placeholder="現在のパスワード"
                id="current_password"
                v-model="passwordForm.current_password"
              />
            </div>
            <div class="mb-2">
              <input
                type="password"
                class="text-xl w-3/5 p-3 border rounded"
                placeholder="パスワード"
                id="password"
                v-model="passwordForm.password"
              />
            </div>
            <div class="mb-2">
              <input
                type="password"
                class="text-xl w-3/5 p-3 border rounded"
                placeholder="パスワード(確認)"
                id="password-confirmation"
                v-model="passwordForm.password_confirmation"
              />
            </div>
            <button
              type="submit"
              class="text-xl w-3/5 bg-blue-600 text-white py-2 rounded"
            >
              パスワードを更新する
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref, reactive, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { OK } from "../util";
const props = defineProps({
  id: {
    type: String,
    required: true,
  },
});
const email = ref("");
const passwordForm = reactive({
  current_password: "",
  password: "",
  password_confirmation: "",
});
const router = useRouter();
const store = useStore();
const id = ref(props.id);
const updateEmailErrors = computed(
  () => store.state.user.updateEmailErrorMessages
);
const updatePasswordErrors = computed(
  () => store.state.user.updatePasswordErrorMessages
);
const apiStatus = computed(() => store.state.user.apiStatus);
const emailVerified = computed(() => store.getters["user/emailVerified"]);
const getUser = async () => {
  await axios.get(`/api/user/${id.value}`).then((response) => {
    if (response.status !== OK) {
      store.commit("error/setCode", response.status);
      return false;
    }
    //メールアドレスを変更した場合認証ページにリダイレクトさせる
    if (!response.data.email_verified_at) {
      router.push("/email/verify");
    }
    email.value = response.data.email;
  });
};
onMounted(() => {
  getUser();
});
const updateEmail = async () => {
  try {
    await store.dispatch("user/updateEmail", { email: email.value });
    if (apiStatus.value) {
      //メールアドレスを変更した場合認証ページにリダイレクトさせる
      if (!emailVerified.value) {
        router.push("/email/verify");
      }
    }
  } catch (err) {
    console.log(err);
  }
};
const updatePassword = async () => {
  try {
    await store.dispatch("user/updatePassword", passwordForm);
    if (apiStatus.value) {
      // パスワードが更新されたら強制的にログアウトする
      router.push("/login");
    }
  } catch (err) {
    console.log(err);
  }
};
const clearError = () => {
  store.commit("user/setUpdateEmailErrorMessages", null);
  store.commit("user/setUpdatePasswordErrorMessages", null);
};
clearError();
</script>
