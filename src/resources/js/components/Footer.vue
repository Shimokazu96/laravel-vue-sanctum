<template>
  <footer class="flex w-full bg-grey-lighter py-8">
    <div class="container mx-auto text-center px-8">
        <p class="text-grey-dark mb-2 text-sm">This is a product of <span class="font-bold">SaunaBouya</span></p>
    </div>
  </footer>
</template>
<script>
import { defineComponent, computed } from "vue";

import { useStore } from "vuex";
import { useRouter } from 'vue-router'

export default defineComponent({
  setup() {
    const store = useStore()
    const router = useRouter()

    const isLogin = computed(() => store.getters["user/isAuthenticated"])

    const logout = async () => {
      try {
        await store.dispatch("user/logout");
          router.push("/login");
      } catch (err) {
        console.log(err);
      }
    }

    return {
      isLogin,
      logout
    }
  }
});
</script>
