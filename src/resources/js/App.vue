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
import store from "./store";
import { INTERNAL_SERVER_ERROR } from "./util";

export default {
  components: {
    Navbar,
    Footer,
  },
  computed: {
    // isLogin() {
    //   if (store.getters["auth/check"]) {
    //     console.log("ログイン済み");
    //     return "ログイン済み";
    //   } else {
    //     console.log("ログインしてません");
    //     return "ログインしてません";
    //   }
    // },
    errorCode() {
      return this.$store.state.error.code;
    },
  },
  watch: {
    errorCode: {
      handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        }
      },
      immediate: true
    },
    $route () {
      this.$store.commit('error/setCode', null)
    }
  }
};
</script>
