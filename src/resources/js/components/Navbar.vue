<template>
  <header class="flex justify-between p-4 border-b items-center">
    <RouterLink class="text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline" to="/">
      Laravel
    </RouterLink>
    <div>
      <RouterLink
        class="px-4 py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
        to="/user-list"
      >
        search
      </RouterLink>
      <RouterLink
        v-if="isLogin"
        class="px-4 py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
        to="/user"
      >
        {{ username }}
      </RouterLink>
      <button
        v-if="isLogin"
        class="md:mt-0 md:ml-4 px-4 py-2 leading-loose text-xs text-center font-semibold leading-none bg-gray-50 hover:bg-gray-100 rounded-xl"
        @click="logout"
      >
        Logout
      </button>
      <RouterLink
        v-if="!isLogin"
        class="md:mt-0 md:ml-4 px-4 py-2 leading-loose text-xs text-center font-semibold leading-none bg-gray-50 hover:bg-gray-100 rounded-xl"
        to="/login"
        >Sign in
      </RouterLink>
      <RouterLink
        v-if="!isLogin"
        class="md:mt-0 md:ml-4 px-4 py-2 leading-loose text-xs text-center font-semibold leading-none bg-blue-600 text-gray-200 hover:bg-blue-500 hover:text-gray-100 rounded-xl"
        to="/register"
        >Sign Up
      </RouterLink>
    </div>
  </header>
</template>
<script>
import { defineComponent, computed } from "vue";

import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const store = useStore();
    const router = useRouter();

    const isLogin = computed(() => store.getters["auth/check"]);
    const username = computed(() => store.getters["auth/username"]);

    const logout = async () => {
      try {
        await store.dispatch("auth/logout");
        router.push("/login");
      } catch (err) {
        console.log(err);
      }
    };

    return {
      isLogin,
      username,
      logout,
    };
  },
});
</script>
