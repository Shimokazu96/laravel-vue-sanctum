<template>
    <main>
        <div class="container--small">
            <div v-if="getMessage">
                <p>{{ getMessage }}</p>
            </div>
            <div class="panel">
                <p class="mt-2 text-gray-600">
                    届いたメールをご確認の上、記載のリンクから登録を完了させてください。<br />
                    認証メールを再送する場合はこちらをクリックしてください。
                </p>
                <form class="form" @submit.prevent="submit">
                    <div class="form__button">
                        <button type="submit" class="button button--inverse">
                            メールを再送
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>

<script setup>
import { ref } from "vue";

const getMessage = ref("");

const closeMessage = () => {
    getMessage.value = "";
};
const submit = async () => {
    try {
        await axios
            .post("/api/admin/email/verification-notification")
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
        console.log(err);
    }
};
</script>
