<template>
  <div class="container--small">
    <div class="panel">
      <div class="mb-4 text-sm text-gray-600">
        <p>
          認証メールを送信しました。届いたメールをご確認の上、記載のリンクから登録を完了させてください。
        </p>
        <p>
          ※メールが届かない場合は、入力したアドレスに間違いがあるか、あるいは迷惑メールフォルダに入っている可能性がありますのでご確認ください。
        </p>
      </div>
      <p>認証メールを再送する場合はこちらをクリックしてください。</p>
      <form @submit.prevent="submit">
        <div class="mt-4 flex items-center justify-between">
          <button type="submit" class="button button--inverse">
            メールを再送
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  //   data() {},
    methods: {
      submit() {
        axios.post('/api/email/verification-notification')
                    .then((res) => {
                        if( res.data.status_code == 200 ) {
                            console.log(res);
                        }
                        this.getUserMessage = 'ログインに失敗しました。'
                    })
                    .catch((err) => {
                        console.log(err);
                        this.getUserMessage = 'ログインに失敗しました。'
                    })
      },
    },
  //   computed: {
  //     verificationLinkSent() {
  //       return this.status === "verification-link-sent";
  //     },
  //   },
});
</script>
