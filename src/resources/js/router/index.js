import { createRouter, createWebHistory } from "vue-router";

// ページコンポーネントをインポートする
import PhotoList from "../pages/PhotoList.vue";
import Login from "../pages/Auth/Login.vue";
import Register from "../pages/Auth/Register.vue";
import VerifyEmail from "../pages/Auth/VerifyEmail.vue";
import User from "../pages/User.vue";
import store from "../store";
import SystemError from "../pages/errors/System.vue";
import NotFound from "../pages/errors/NotFound.vue";


const routes = [
  {
    path: "/",
    component: PhotoList,
    name: "PhotoList",
  },
  {
    path: "/register",
    beforeEnter(to, from, next) {
      if (store.getters["auth/check"]) {
        next("/");
      } else {
        next();
      }
    },
    component: Register,
    name: "Register",
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
    path: "/email/verify",
    beforeEnter(to, from, next) {
      if (store.getters["auth/check"]) {
        next("/");
      } else {
        next();
      }
    },
    component: VerifyEmail,
    name: "VerifyEmail",
  },
  {
    path: "/user",
    component: User,
    name: "user",
    meta: { requiresAuth: true },
  },
  {
    path: "/500",
    component: SystemError,
  },
  {
    path: "/:pathMatch(.*)*",
    component: NotFound,
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
