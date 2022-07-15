<template>
  <div class="h-screen flex justify-center bg-gray-100">
    <div
    v-for="user in userList" :key="user.id"
      class="
        flex
        items-center
        justify-center
        bg-gradient-to-br
        w-3/12
      "
    >
      <div
        class="
          w-full
          bg-white
          font-semibold
          text-center
          rounded-3xl
          border
          shadow-lg
          p-10
          max-w-xs
        "
      >
        <img
          class="mb-3 w-32 h-32 rounded-full shadow-lg mx-auto"
          src="/images/human.jpeg"
          alt="product designer"
        />
        <h1 class="text-lg text-gray-700">{{ user.name }}</h1>
        <button
          @click.prevent="clickFollow(user)"
          class="
            px-8
            py-2
            mt-8
            rounded-3xl
            font-semibold
            uppercase
            tracking-wide
          "
          :class=" user.followed_by_user ? 'text-gray-600 bg-gray-50 border-gray-200' : 'bg-indigo-600 text-gray-100'"
        >
          {{ user.followed_by_user ? "フォロー中" : "フォローする" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  ref,
  onMounted,
  computed,
} from "vue";
import { OK } from "../util";
import { useStore } from "vuex";

const store = useStore();
const userList = ref([]);
const isLogin = computed(() => store.getters["user/isAuthenticated"])

const getUserList = async () => {
  await axios.get("/api/user-list").then((response) => {
      if (response.status !== OK) {
        store.commit("error/setCode", response.status);
        return false;
      }
      console.log(response.data);
      userList.value = response.data;
    });
}
onMounted(() => {
  getUserList();
});

const clickFollow = async (user) => {
  if (!isLogin.value) {
  alert('フォロー機能はログイン中のみ使用できます')
  return
  }
  user.followed_by_user ? unfollow(user) : follow(user)
}
const follow = async (user) => {
  const response = await axios.put(`/api/user/${user.id}/follow`)
  user.followed_by_user = true
}
const unfollow = async (user) => {
  const response = await axios.delete(`/api/user/${user.id}/follow`)
  user.followed_by_user = false
}
</script>
