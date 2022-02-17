import { createRouter, createWebHistory } from "vue-router";

// ページコンポーネントをインポートする
import Index from "../pages/Index.vue";
import Top from "../pages/Top.vue";
import Login from "../pages/auth/Login.vue";
import Register from "../pages/auth/Register.vue";
import VerifyEmail from "../pages/auth/VerifyEmail.vue";
import ForgotPassword from "../pages/auth/ForgotPassword.vue";
import ResetPassword from "../pages/auth/ResetPassword.vue";
import User from "../pages/User.vue";
import UserDetail from "../pages/UserDetail.vue";
import UserPasswordUpdate from "../pages/UserPasswordUpdate.vue";
import store from "../store";
import SystemError from "../pages/errors/System.vue";
import NotFound from "../pages/errors/NotFound.vue";

const routes = [
  {
    path: "/",
    component: Index,
    children: [
      {
        path: "",
        component: Top,
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
          if (store.getters["auth/emailVerified"]) {
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
        meta: { requiresAuth: true },
      },
      {
        path: "/user/:id",
        component: UserDetail,
        props: true,
        meta: { requiresAuth: true },
      },
      {
        path: "/user/:id/password",
        component: UserPasswordUpdate,
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
    ],
  },
];

const router = createRouter({
  routes,
  history: createWebHistory(),
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.getters["auth/emailVerified"] && store.getters["auth/check"]) {
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
