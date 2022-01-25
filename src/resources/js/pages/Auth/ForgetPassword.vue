<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="w-2/5 border bg-white">
        <div class="my-12 text-center">
          <div
            v-if="getMessage"
            class="bg-green-100 rounded-lg p-4 mb-4 text-sm text-green-700"
            role="alert"
          >
            <div>
              {{ getMessage }}
            </div>
          </div>
          <h2 class="text-3xl mb-2 font-bold">パスワードリセット</h2>
          <form class="form" @submit.prevent="submit">
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
              class="text-xl w-3/5 bg-green-800 text-white py-2 rounded"
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
import { defineComponent, ref } from "vue";

export default defineComponent({
  setup() {
    const getMessage = ref("");
    const email = ref("");

    const closeMessage = () => {
      getMessage.value = "";
    };
    const submit = async () => {
      try {
        await axios
          .post("/api/forgot-password", email.value)
          .then((res) => {
            console.log(res);
            getMessage.value = "メールを送信しました。";
            setTimeout(closeMessage, 6000);
          })
          .catch((err) => {
            console.log(err);
            getMessage.value = "メールを送信に失敗しました。";
            setTimeout(closeMessage, 6000);
          });
      } catch (err) {
        console.log("Failure");
      }
    };

    return {
      getMessage,
      email,
      submit,
    };
  },
});
</script>
