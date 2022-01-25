<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="max-w-md py-4 px-8 bg-white shadow-lg rounded-lg my-20">
        <div
          v-if="getMessage"
          class="bg-green-100 rounded-lg p-4 mb-4 text-sm text-green-700"
          role="alert"
        >
          <div>
            {{ getMessage }}
          </div>
        </div>
        <div>
          <p class="mt-2 text-gray-600">
            届いたメールをご確認の上、記載のリンクから登録を完了させてください。<br />
            認証メールを再送する場合はこちらをクリックしてください。
          </p>
        </div>
        <form class="flex justify-center mt-4" @submit.prevent="submit">
          <button
            type="submit"
            class="text-xl w-3/5 bg-green-800 text-white py-2 rounded"
          >
            メールを再送
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";

export default defineComponent({
  setup() {
    const getMessage = ref("");

    const closeMessage = () => {
      getMessage.value = "";
    };
    const submit = async () => {
      try {
        await axios
          .post("/api/email/verification-notification")
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
      submit,
    };
  },
});
</script>
