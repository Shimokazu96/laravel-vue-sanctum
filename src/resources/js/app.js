import "./bootstrap";

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router/index.js";
import store from "./store";

const buildApp = async () => {
    await store.dispatch("user/currentUser");
    const app = createApp(App);
    app.use(router);
    app.use(store);

    app.mount("#app");
};

buildApp();
