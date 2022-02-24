<template>
  <header class="flex justify-between p-4 border-b items-center">
    <RouterLink class="font-semibold text-xl leading-tight" to="/">
      Laravel
    </RouterLink>
    <div>
      <RouterLink
        v-if="isLogin"
        class="font-semibold text-xl leading-tight"
        to="/user"
      >
        {{ username }}
      </RouterLink>
      <button
        v-if="isLogin"
        class="ml-3 py-1 px-4 border-2 border-green-800 rounded"
        @click="logout"
      >
        Logout
      </button>
      <RouterLink
        v-if="!isLogin"
        class="py-1 px-4 border-2 border-green-800 rounded"
        to="/login"
        >サインイン
      </RouterLink>
      <RouterLink
        v-if="!isLogin"
        class="ml-3 py-1 px-4 border-2 border-green-800 rounded"
        to="/register"
        >登録する
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
