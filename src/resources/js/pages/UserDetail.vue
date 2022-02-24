<template>
  <div class="bg-gray-100 flex-grow">
    <div class="container mx-auto my-5 p-5">
      <div class="md:flex no-wrap md:-mx-2">
        <!-- Left Side -->
        <div class="w-full md:w-3/12 md:mx-2">
          <!-- Profile Card -->
          <div class="bg-white p-3 border-t-4 border-green-400">
            <div class="image overflow-hidden">
              <div class="w-40 h-40 m-auto">
                <img
                  v-if="image_url && !preview"
                  class="w-40 h-40 m-auto rounded-full shadow object-cover"
                  :src="`/storage/${image_url}`"
                  alt=""
                />
                <img v-else-if="preview" :src="preview" class="w-40 h-40 m-auto rounded-full shadow object-cover" alt="" />
                <img
                  v-else
                  class="w-40 h-40 m-auto rounded-full shadow object-cover"
                  src="/images/human.jpeg"
                  alt=""
                />

              </div>

              <label class="cursor-pointer mt-6">
                <div class="items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 text-center uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-400 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 mt-2">Select Avatar</div>
                <input id="icon_image" type="file" class="hidden" @change="onFileChange" />
              </label>
            </div>
            <h1 class="text-gray-900 font-bold text-xl leading-8 my-1">
              {{ username }}
            </h1>
            <h3 class="text-gray-600 font-lg text-semibold leading-6">
              Owner at Her Company Inc.
            </h3>
            <p class="text-sm text-gray-500 hover:text-gray-600 leading-6">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Reprehenderit, eligendi dolorum sequi illum qui unde aspernatur
              non deserunt
            </p>
          </div>
          <!-- End of profile card -->
          <div class="my-4"></div>
        </div>
        <!-- Right Side -->
        <div class="w-full md:w-9/12 mx-2">
          <!-- Profile tab -->
          <!-- About Section -->
          <div class="bg-white p-3 shadow-sm rounded-sm">
            <div
              class="
                flex
                items-center
                space-x-2
                font-semibold
                text-gray-900
                leading-8
              "
            >
              <span clas="text-green-500">
                <svg
                  class="h-5"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
              </span>
              <span class="tracking-wide">About</span>
            </div>
            <UpdateMessage />
            <template v-if="errorMessage">
              <div
                v-for="error in errorMessage"
                :key="error.id"
                class="rounded-lg p-4 m-auto mb-4 w-4/5 text-sm bg-red-100 text-red-700"

              >
                {{ error[0] }}
              </div>
            </template>
            <div class="text-gray-700">
              <form @submit.prevent="updateUser">
                <div class="xl:grid xl:grid-cols-2 text-sm">
                  <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">名前</div>
                    <input
                      type="text"
                      class="w-2/3 px-4 py-2 border rounded"
                      v-model="name"
                    />
                  </div>
                  <!-- <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">名前(フリガナ)</div>
                    <input type="text" class="w-2/3 px-4 py-2 border rounded" v-model="furigana">
                  </div> -->
                  <!-- <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">
                      メールアドレス
                    </div>
                    <input
                      type="text"
                      class="w-2/3 px-4 py-2 border rounded"
                      v-model="email"
                    />
                  </div> -->
                  <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">電話番号</div>
                    <input
                      type="text"
                      class="w-2/3 px-4 py-2 border rounded"
                      v-model="user_detail.tel"
                    />
                  </div>
                  <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">生年月日</div>
                    <input
                      type="date"
                      class="w-2/3 px-4 py-2 border rounded"
                      v-model="user_detail.birthday"
                    />
                  </div>
                  <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-4/12 px-4 py-2 font-semibold">郵便番号</div>
                    <input
                      type="text"
                      class="w-3/12 mr-2 px-4 py-2 border rounded"
                      v-model="user_detail.zip"
                    />
                    <button
                      class="bg-blue-600 text-white px-4 py-2 rounded whitespace-nowrap"
                      @click.prevent="searchAddress()"
                    >
                      住所検索
                    </button>
                  </div>
                  <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">都道府県</div>
                    <select
                      id="pref"
                      class="w-2/3 px-4 py-2 border rounded"
                      v-model="user_detail.pref"
                    >
                      <option value="">都道府県選択</option>
                      <option
                        :value="pref.id"
                        :key="index"
                        v-for="(pref, index) in PREFECTURES"
                      >
                        {{ pref.name }}
                      </option>
                    </select>
                    <!-- <input type="text" class="w-2/3 px-4 py-2 border rounded" v-model="user_detail.pref"> -->
                  </div>
                  <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">住所</div>
                    <input
                      type="text"
                      class="w-2/3 px-4 py-2 border rounded"
                      v-model="user_detail.address"
                    />
                  </div>
                  <div class="flex flex-wrap flex-row mt-2">
                    <div class="w-1/3 px-4 py-2 font-semibold">建物</div>
                    <input
                      type="text"
                      class="w-2/3 px-4 py-2 border rounded"
                      v-model="user_detail.building"
                    />
                  </div>
                </div>
                <button
                  type="submit"
                  class="block w-full text-blue-800 text-sm font-semibold rounded-lg hover:bg-gray-100 focus:outline-none focus:shadow-outline focus:bg-gray-100 hover:shadow-xs p-3 my-4"
                >
                  ユーザー情報を更新する
                </button>
              </form>
            </div>
          </div>
          <!-- End of about section -->

          <div class="my-4"></div>

          <!-- Experience and education -->
          <div class="bg-white p-3 shadow-sm rounded-sm">
            <div class="grid grid-cols-2 mt-2">
              <div>
                <div
                  class="flex items-center space-x-2 font-semibold text-gray-900 leading-8 mb-3"

                >
                  <span clas="text-green-500">
                    <svg
                      class="h-5"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                      />
                    </svg>
                  </span>
                  <span class="tracking-wide">Experience</span>
                </div>
                <ul class="list-inside space-y-2">
                  <li>
                    <div class="text-teal-600">Owner at Her Company Inc.</div>
                    <div class="text-gray-500 text-xs">March 2020 - Now</div>
                  </li>
                  <li>
                    <div class="text-teal-600">Owner at Her Company Inc.</div>
                    <div class="text-gray-500 text-xs">March 2020 - Now</div>
                  </li>
                  <li>
                    <div class="text-teal-600">Owner at Her Company Inc.</div>
                    <div class="text-gray-500 text-xs">March 2020 - Now</div>
                  </li>
                  <li>
                    <div class="text-teal-600">Owner at Her Company Inc.</div>
                    <div class="text-gray-500 text-xs">March 2020 - Now</div>
                  </li>
                </ul>
              </div>
              <div>
                <div
                  class="flex items-center space-x-2 font-semibold text-gray-900 leading-8 mb-3"
                >
                  <span clas="text-green-500">
                    <svg
                      class="h-5"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path fill="#fff" d="M12 14l9-5-9-5-9 5 9 5z" />
                      <path
                        fill="#fff"
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                      />
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                      />
                    </svg>
                  </span>
                  <span class="tracking-wide">Education</span>
                </div>
                <ul class="list-inside space-y-2">
                  <li>
                    <div class="text-teal-600">Masters Degree in Oxford</div>
                    <div class="text-gray-500 text-xs">March 2020 - Now</div>
                  </li>
                  <li>
                    <div class="text-teal-600">Bachelors Degreen in LPU</div>
                    <div class="text-gray-500 text-xs">March 2020 - Now</div>
                  </li>
                </ul>
              </div>
            </div>
            <!-- End of Experience and education grid -->
          </div>
          <!-- End of profile tab -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  defineProps,
  ref,
  reactive,
  computed,
  onMounted,
} from "vue";
import { useStore } from "vuex";
import { OK } from "../util";
import { PREFECTURES } from "../user-constant";
import UpdateMessage from "../components/UpdateMessage.vue";
import { Core as YubinBangoCore } from "yubinbango-core2";

const props = defineProps({
  id: {
    type: String,
    required: true,
  },
});
const name = ref("");
const image_url = ref(""); //ストレージから取得した画像のパス
const user_detail = ref({
  tel: "",
  birthday: null,
  zip: "",
  pref: null,
  address: "",
  building: "",
  image: null,
});

const id = ref(props.id);
const preview = ref("");
const store = useStore();
const errorMessage = ref({});
const username = computed(() => store.getters["auth/username"]);

const getUser = async () => {
  try {
    await axios.get(`/api/user/${id.value}`).then((response) => {
      if (response.status !== OK) {
        store.commit("error/setCode", response.status);
        return false;
      }
      console.log(response.data);
      name.value = response.data.name;
      user_detail.value = response.data.user_detail;
      user_detail.value.pref = response.data.user_detail.pref
        ? response.data.user_detail.pref
        : "";
      image_url.value = response.data.user_detail.image;
      reset()
    });
  } catch (err) {
    console.log(err);
  }
};
onMounted(() => {
  getUser();
});

const closeMessage = () => {
  errorMessage.value = "";
};
const updateUser = async () => {
  let formData = new FormData()
  let config = {
    headers: {
      'content-type': 'multipart/form-data', //ファイルを送れるようmultipart/form-datを指定する
      'X-HTTP-Method-Override': 'PUT' // ここでPUTに置き換える
    }
  };
  //現状、スマートな書き方がわからないため各要素ごとに代入
  formData.append('name', name.value)
  user_detail.value.tel ? formData.append('tel', user_detail.value.tel) : ''
  user_detail.value.birthday ? formData.append('birthday', user_detail.value.birthday) : ''
  user_detail.value.zip ? formData.append('zip', user_detail.value.zip) : ''
  user_detail.value.pref ? formData.append('pref', user_detail.value.pref) : ''
  user_detail.value.address ? formData.append('address', user_detail.value.address) : ''
  user_detail.value.building ? formData.append('building', user_detail.value.building) : ''
  user_detail.value.image ? formData.append('image', user_detail.value.image) : ''
  for (let value of formData.entries()) {
    console.log(value);
  }
  try {
    await axios
      .post(`/api/user/${id.value}/update`, formData,config) // POSTを指定する
      .then((response) => {
        if (response.status !== OK) {
          errorMessage.value = response.data.errors;
          store.commit("error/setCode", response.status);
          return false;
        }
        console.log(response);
        name.value = response.data.name;
        user_detail.value = response.data.user_detail;
        image_url.value = response.data.user_detail.image;
        reset()
        store.commit("message/setContent", {
          content: "更新しました。",
          timeout: 6000,
        });
      })
      .catch((err) => {
        store.commit("message/setContent", {
          content: "更新しました。",
          timeout: 6000,
        });
      });
  } catch (err) {
    console.log(err);
  }
};

// フォームでファイルが選択されたら実行される
const onFileChange = (event) => {
  // 何も選択されていなかったら処理中断
  if (event.target.files.length === 0) {
    reset()
    return false
  }
  // ファイルが画像ではなかったら処理中断
  if ( !event.target.files[0].type.match('image.*')) {
    reset()
    return false
  }
  // FileReaderクラスのインスタンスを取得
  const reader = new FileReader()
  // ファイルを読み込み終わったタイミングで実行する処理
  reader.onload = e => {
    // previewに読み込み結果（データURL）を代入する
    // previewに値が入ると<output>につけたv-ifがtrueと判定される
    // また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
    // 結果として画像が表示される
    preview.value = e.target.result
  }
  // ファイルを読み込む
  // 読み込まれたファイルはデータURL形式で受け取れる（上記onload参照）
  reader.readAsDataURL(event.target.files[0])
  user_detail.value.image = event.target.files[0]
};
// 入力欄の値とプレビュー表示をクリアするメソッド
const reset = () => {
  preview.value = ''
  user_detail.value.image = null
  document.querySelector('#icon_image').value = null
};

const searchAddress = () => {
  new YubinBangoCore(user_detail.value.zip, (value) => {
    user_detail.value.pref = value.region_id; // 都道府県
    user_detail.value.address = value.locality; // 市区町村
    user_detail.value.address += value.street; // 町域
  });
};
</script>
