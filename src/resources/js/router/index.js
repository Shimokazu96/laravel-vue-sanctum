import { createRouter, createWebHistory } from "vue-router";

// ページコンポーネントをインポートする
import Top from "../pages/Top.vue";
import Login from "../pages/auth/Login.vue";
import Register from "../pages/auth/Register.vue";
import VerifyEmail from "../pages/auth/VerifyEmail.vue";
import ForgotPassword from "../pages/auth/ForgotPassword.vue";
import ResetPassword from "../pages/auth/ResetPassword.vue";
import User from "../pages/User.vue";
import UserDetail from "../pages/UserDetail.vue";
import store from "../store";
import SystemError from "../pages/errors/System.vue";
import NotFound from "../pages/errors/NotFound.vue";

const routes = [
  {
    path: "/",
    component: Top,
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
    component: ForgotPassword,
  },
  {
    path: "/reset-password/:token",
    component: ResetPassword,
    props: true,
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
  },
  {
    path: "/user",
    component: User,
    name: "user",
    meta: { requiresAuth: true },
  },
  {
    path: "/user/:id/detail",
    component: UserDetail,
    name: "userDetail",
    props: true,
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
