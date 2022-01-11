import { createRouter, createWebHistory } from "vue-router";

// ページコンポーネントをインポートする
import PhotoList from "./pages/PhotoList.vue";
import Login from "./pages/Login.vue";
import About from "./pages/About.vue";
import store from "./store";
import SystemError from "./pages/errors/System.vue";


const routes = [
  {
    path: "/",
    component: PhotoList,
    name: "PhotoList",
  },
  {
    path: "/login",
    beforeEnter(to, from, next) {
      if (store.getters["auth/check"]) {
        next("/");
      } else {
        next();
      }
    },
    component: Login,
    name: "login",
  },
  {
    path: "/about",
    component: About,
    name: "about",
    meta: { requiresAuth: true },
  },
  {
    path: "/500",
    component: SystemError,
  },
];

const router = createRouter({
  routes,
  history: createWebHistory(),
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.getters["auth/check"]) {
      next({
        path: "/login",
        // query: {
        //   redirect: to.fullPath,
        //   message: true,
        // },
      });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
