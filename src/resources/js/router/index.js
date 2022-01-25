import { createRouter, createWebHistory } from "vue-router";

// ページコンポーネントをインポートする
import PhotoList from "../pages/PhotoList.vue";
import Login from "../pages/auth/Login.vue";
import Register from "../pages/auth/Register.vue";
import VerifyEmail from "../pages/auth/VerifyEmail.vue";
import ForgetPassword from "../pages/auth/ForgetPassword.vue";
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
    path: "/forgot-password",
    beforeEnter(to, from, next) {
      if (store.getters["auth/check"]) {
        next("/");
      } else {
        next();
      }
    },
    component: ForgetPassword,
    name: "ForgetPassword",
  },
  {
    path: "/email/verify",
    beforeEnter(to, from, next) {
      if (store.getters["auth/verified"]) {
        next("/");
      } else if (!store.getters["auth/check"]) {
        next({
          path: "/login",
        });
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
    if (!store.getters["auth/verified"] && store.getters["auth/check"]) {
      next({
        path: "/email/verify",
      });
    } else if (!store.getters["auth/check"]) {
      next({
        path: "/login",
      });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
