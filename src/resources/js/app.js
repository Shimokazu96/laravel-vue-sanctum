require("./bootstrap");

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

const buildApp = async () => {
  await store.dispatch("auth/currentUser");
  const app = createApp(App);
  app.use(router);
  app.use(store);

  app.mount("#app");
};

buildApp();
// new Vue({
//   el: "#app",
//   router,
//   store,
//   components: { App },
//   template: "<App />",
// });
