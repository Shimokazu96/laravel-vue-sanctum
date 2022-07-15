import "./bootstrap";

import { createApp } from "vue";
import App from "./App.vue";
import routerAdmin from "./router/admin.js";
import store from "./store";

const buildApp = async () => {
    await store.dispatch("admin/currentAdmin");
    const app = createApp(App);
    app.use(routerAdmin);
    app.use(store);

    app.mount("#appAdmin");
};

buildApp();
