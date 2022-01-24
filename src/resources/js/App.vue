<template>
  <div>
    <header>
      <Navbar />
    </header>
    <main>
      <!-- <p>{{ isLogin }}</p> -->
      <div class="container">
        <RouterView />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from "./util";
// import { useStore } from "vuex";
// import { useRouter } from "vue-router";
// import { watch, computed } from "vue";

export default {
  components: {
    Navbar,
    Footer,
  },
  // setup() {
  //   const store = useStore();
  //   const router = useRouter();
  //   const errorCode = computed(() => store.state.error.code);

  //   watch( errorCode.value,async () => {
  //       if (errorCode.value === INTERNAL_SERVER_ERROR) {
  //         router.push("/500");
  //       } else if (errorCode.value === UNAUTHORIZED) {
  //         // トークンをリフレッシュ
  //         await axios.get("/api/refresh-token");
  //         // ストアのuserをクリア
  //         store.commit("auth/setUser", null);
  //         // ログイン画面へ
  //         router.push("/login");
  //       } else if (errorCode.value === NOT_FOUND) {
  //         router.push("/not-found");
  //       }
  //     },
  //     { immediate: true },
  //     router,
  //     () => {
  //       store.commit("error/setCode", null);
  //     }
  //   );
  // },
  computed: {
    errorCode() {
      return this.$store.state.error.code;
    },
  },
  watch: {
    errorCode: {
      async handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        } else if (val === UNAUTHORIZED) {
          // トークンをリフレッシュ
          await axios.get("/api/refresh-token");
          // ストアのuserをクリア
          this.$store.commit("auth/setUser", null);
          // ログイン画面へ
          this.$router.push("/login");
        } else if (val === NOT_FOUND) {
          this.$router.push("/not-found");
        }
      },
      immediate: true,
    },
    $route() {
      this.$store.commit("error/setCode", null);
    },
  },
};
</script>
