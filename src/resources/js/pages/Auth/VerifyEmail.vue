<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="max-w-md py-4 px-8 bg-white shadow-lg rounded-lg my-20">
        <div>
          <h2 class="text-gray-800 text-2xl font-semibold">
            認証メールを送信しました。
          </h2>
          <p class="mt-2 text-gray-600">
            届いたメールをご確認の上、記載のリンクから登録を完了させてください。<br>
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
import { defineComponent } from "vue";

export default defineComponent({
  //   data() {},
  methods: {
    submit() {
      axios
        .post("/api/email/verification-notification")
        .then((res) => {
          if (res.data.status_code == 200) {
            console.log(res);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  //   computed: {
  //     verificationLinkSent() {
  //       return this.status === "verification-link-sent";
  //     },
  //   },
});
</script>
