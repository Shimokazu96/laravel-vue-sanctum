<template>
  <nav class="navbar">
    <RouterLink class="navbar__brand" to="/">
      Laravel
    </RouterLink>
    <div class="navbar__menu">
      <!-- <div v-if="isLogin" class="navbar__item">
        <button class="button">
          <i class="icon ion-md-add"></i>
          Submit a photo
        </button>
      </div> -->
      <RouterLink v-if="isLogin" class="navbar__item" to="/user">
          {{ username }}
        </RouterLink>
      <!-- <span v-if="isLogin" class="navbar__item">
        {{ username }}
      </span> -->
      <div v-else class="navbar__item">
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
    </div>
  </nav>
</template>
<script>
import { defineComponent, computed } from "vue";

import { useStore } from "vuex";

export default defineComponent({
  setup() {
    const store = useStore()

    const isLogin = computed(() => store.getters["auth/check"])
    const username = computed(() => store.getters["auth/username"])

    return {
      isLogin,
      username
    }
  }
});
</script>
