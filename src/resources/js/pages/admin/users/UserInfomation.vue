<template>
    <div>
        <DashBoardTitle :breadCrumbs="breadCrumbs" title="ユーザー情報" />
        <div class="py-3">
            <form class="m-auto w-[90%]" @submit.prevent="register">
                <div v-if="errorMessage">
                    <div v-for="error in errorMessage" :key="error.id">
                        <DangerMessage :message="error[0]" />
                    </div>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="ユーザー名" />
                    <BaseInput
                        type="text"
                        name="name"
                        v-model="name"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="氏名" />
                    <BaseInput
                        type="text"
                        name="realname"
                        v-model="user_detail.realname"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="フリガナ" />
                    <BaseInput
                        type="text"
                        name="furigana"
                        v-model="user_detail.furigana"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="電話番号" />
                    <BaseInput
                        type="text"
                        name="tel"
                        v-model="user_detail.tel"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="郵便番号" />
                    <div class="flex">
                        <ZipInput
                            type="text"
                            name="zip"
                            v-model="user_detail.zip"
                            :disabled="disabled"
                        />
                        <SearchZipButton
                            @click.prevent="searchAddress()"
                            name="住所検索"
                            :disabled="disabled"
                        />
                    </div>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="都道府県" />
                    <BaseSelect
                        name="prefecture_id"
                        v-model="user_detail.prefecture_id"
                        :options="PREFECTURES"
                        :disabled="disabled"
                        defaultName="都道府県選択"
                    ></BaseSelect>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="市区町村" />
                    <BaseInput
                        type="text"
                        name="city"
                        v-model="user_detail.city"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="住所" />
                    <BaseInput
                        type="text"
                        name="address"
                        v-model="user_detail.address"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="建物" />
                    <BaseInput
                        type="text"
                        name="building"
                        v-model="user_detail.building"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="年代" />
                    <BaseSelect
                        name="available"
                        v-model="user_detail.age"
                        :options="AGES"
                        :disabled="disabled"
                        defaultName="選択してください"
                    ></BaseSelect>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="アイコン" />

                    <div class="col-sm-10">
                        <input
                            type="file"
                            id="icon_image"
                            class="form-control"
                            @change="onFileChange"
                            :disabled="disabled"
                        />

                        <output class="drop_area my-3" v-if="preview">
                            <img :src="preview" alt="" />
                        </output>
                        <output
                            class="drop_area my-3"
                            v-else-if="icon_image && !preview"
                        >
                            <img :src="icon_image_url" alt="" />
                        </output>
                        <output class="drop_area my-3" v-else-if="!preview">
                        </output>
                    </div>
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="登録日" />
                    <BaseInput
                        type="date"
                        name="created_at"
                        v-model="created_at"
                        :disabled="disabled"
                    />
                </div>
                <div class="mt-2">
                    <BaseLabel labelName="利用フラグ" />
                    <BaseSelect
                        labelName="利用フラグ"
                        name="available"
                        class="md:w-6/12"
                        v-model="available"
                        :options="options"
                        :disabled="disabled"
                    ></BaseSelect>
                </div>
                <div class="mt-5 flex justify-center align-center">
                    <CancelButton
                        name="キャンセル"
                        class="mr-2"
                        :to="`/admin/users`"
                    />
                    <UpdateButton
                        class="w-[80px] text-center"
                        type="submit"
                        name="登録"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import { OK } from "@/util";
import { PREFECTURES, AGES } from "@/user-constant";
import UpdateMessage from "@/components/parts/admin/UpdateMessage.vue";
import { Core as YubinBangoCore } from "yubinbango-core2";
import BaseInput from "@/components/parts/admin/BaseInput.vue";
import BaseLabel from "@/components/parts/admin/BaseLabel.vue";
import BaseSelect from "@/components/parts/admin/BaseSelect.vue";
import BreadCrumb from "@/components/parts/admin/BreadCrumb.vue";
import CancelButton from "@/components/parts/admin/button/CancelButton.vue";
import UpdateButton from "@/components/parts/admin/button/UpdateButton.vue";
import DashBoardTitle from "@/components/templates/admin/DashBoardTitle.vue";
import DangerMessage from "@/components/parts/admin/DangerMessage.vue";
import ZipInput from "@/components/parts/admin/ZipInput.vue";
import SearchZipButton from "@/components/parts/admin/button/SearchZipButton.vue";

const store = useStore();
const router = useRouter();
const route = useRoute();
const id = ref(route.params.id);

const breadCrumbs = [
    {
        name: "ダッシュボード",
        path: "/admin",
    },
    {
        name: "ユーザー一覧",
        path: "/admin/users",
    },
    {
        name: "ユーザーページ",
        path: `/admin/users/${id.value}/userpage`,
    },
    {
        name: "ユーザー情報",
    },
];

const name = ref("");
const available = ref("");
const created_at = ref("");
const deleted_at = ref("");
const icon_image_url = ref(""); //S3の画像URL
const user_detail = ref({
    furigana: "",
    realname: "",
    tel: "",
    age: "",
    zip: "",
    prefecture_id: "",
    city: "",
    address: "",
    building: "",
    icon_image: null,
});

const errorMessage = ref("");
const preview = ref("");

const options = [
    { name: "利用可能", value: 1 },
    { name: "利用不可", value: 0 },
];

const apiStatus = computed(() => store.state.admin.apiStatus);
const registerErrors = computed(() => store.state.admin.registerErrorMessages);
const disabled = computed(() => (deleted_at.value ? "disabled" : false));

const getUser = async () => {
    await axios.get(`/api/admin/users/${id.value}`).then((response) => {
        if (response.status !== OK) {
            store.commit("error/setCode", response.status);
            return false;
        }
        console.log(response.data);
        name.value = response.data.name;
        created_at.value = customFormat(response.data.created_at);
        deleted_at.value = response.data.deleted_at;
        available.value = response.data.available;
        user_detail.value = response.data.user_detail;
        user_detail.value.prefecture_id = response.data.user_detail
            .prefecture_id
            ? response.data.user_detail.prefecture_id
            : "";
        user_detail.value.age = response.data.user_detail.age
            ? response.data.user_detail.age
            : "";
        icon_image_url.value = response.data.user_detail.icon_image_url
            ? response.data.user_detail.icon_image_url
            : "";
        reset();
    });
};

getUser();

const closeMessage = () => {
    errorMessage.value = "";
};
const updateUser = async () => {
    let formData = new FormData();
    let config = {
        headers: {
            "content-type": "multipart/form-data", //ファイルを送れるようmultipart/form-datを指定する
            "X-HTTP-Method-Override": "PUT", // ここでPUTに置き換える
        },
    };
    //現状、スマートな書き方がわからないため各要素ごとに代入しています
    name.value ? formData.append("name", name.value) : "";
    created_at.value ? formData.append("created_at", created_at.value) : "";
    available.value ? formData.append("available", available.value) : "";
    user_detail.value.furigana
        ? formData.append("furigana", user_detail.value.furigana)
        : "";
    user_detail.value.realname
        ? formData.append("realname", user_detail.value.realname)
        : "";
    user_detail.value.tel ? formData.append("tel", user_detail.value.tel) : "";
    user_detail.value.age ? formData.append("age", user_detail.value.age) : "";
    user_detail.value.zip ? formData.append("zip", user_detail.value.zip) : "";
    user_detail.value.prefecture_id
        ? formData.append("prefecture_id", user_detail.value.prefecture_id)
        : "";
    user_detail.value.city
        ? formData.append("city", user_detail.value.city)
        : "";
    user_detail.value.address
        ? formData.append("address", user_detail.value.address)
        : "";
    user_detail.value.building
        ? formData.append("building", user_detail.value.building)
        : "";
    user_detail.value.icon_image
        ? formData.append("icon_image", user_detail.value.icon_image)
        : "";
    for (let value of formData.entries()) {
        console.log(value);
    }
    try {
        await axios
            .post(`/api/admin/users/${id.value}/infomation`, formData, config)
            .then((response) => {
                if (response.status !== OK) {
                    console.log(response);
                    errorMessage.value = response.data.errors;
                    // setTimeout(closeMessage, 6000);
                    store.commit("error/setCode", response.status);
                    return false;
                }
                console.log(response);
                name.value = response.data.name;
                user_detail.value = response.data.user_detail;
                user_detail.value.prefecture_id = response.data.user_detail
                    .prefecture_id
                    ? response.data.user_detail.prefecture_id
                    : "";
                user_detail.value.age = response.data.user_detail.age
                    ? response.data.user_detail.age
                    : "";
                icon_image_url.value = response.data.user_detail.icon_image_url
                    ? response.data.user_detail.icon_image_url
                    : "";

                store.commit("message/setContent", {
                    content: "更新しました。",
                    timeout: 6000,
                });
                errorMessage.value = "";
                reset();
            })
            .catch((err) => {
                console.log(err);
                store.commit("message/setContent", {
                    content: "更新しました。",
                    timeout: 6000,
                });
            });
    } catch (err) {
        console.log(err);
    }
};

const clearError = () => {
    store.commit("admin/setLoginErrorMessages", null);
    store.commit("admin/setRegisterErrorMessages", null);
};
clearError();

// フォームでファイルが選択されたら実行される
const onFileChange = (event) => {
    // 何も選択されていなかったら処理中断
    if (event.target.files.length === 0) {
        reset();
        return false;
    }
    // ファイルが画像ではなかったら処理中断
    if (!event.target.files[0].type.match("image.*")) {
        reset();
        return false;
    }
    // FileReaderクラスのインスタンスを取得
    const reader = new FileReader();
    // ファイルを読み込み終わったタイミングで実行する処理
    reader.onload = (e) => {
        // previewに読み込み結果（データURL）を代入する

        preview.value = e.target.result;
    };
    // ファイルを読み込む
    reader.readAsDataURL(event.target.files[0]);
    user_detail.value.icon_image = event.target.files[0];
    console.log(event.target.files[0]);
};
// 入力欄の値とプレビュー表示をクリアするメソッド
const reset = () => {
    preview.value = "";
    user_detail.value.icon_image = null;
    document.querySelector("#icon_image").value = null;
};

const searchAddress = () => {
    new YubinBangoCore(user_detail.value.zip, (value) => {
        user_detail.value.prefecture_id = value.region_id; // 都道府県
        user_detail.value.city = value.locality; // 市区町村
        user_detail.value.city += value.street; // 町域
    });
};
// 日付フォーマットを編集
const customFormat = (value) => {
    let date = new Date(value);
    let y = date.getFullYear();
    let m = ("00" + (date.getMonth() + 1)).slice(-2);
    let d = ("00" + date.getDate()).slice(-2);
    return y + "-" + m + "-" + d;
};
</script>
<style>
.drop_area {
    color: gray;
    font-weight: bold;
    font-size: 1.2em;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 200px;
    height: 150px;
    border: 5px solid gray;
    border-radius: 15px;
}
.drop_area img {
    width: 100%;
    height: 100%;
    object-fit: scale-down;
}
</style>
