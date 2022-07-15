import { createRouter, createWebHistory } from "vue-router";
import { computed } from "vue";
import store from "@/store";

// 管理画面 /admin
import Default from "@/layouts/admin.vue";
import TheDashBoard from "@/components/templates/admin/TheDashBoard.vue";
import Login from "@/pages/admin/Login.vue";
import VerifyEmail from "@/pages/admin/VerifyEmail.vue";

import Index from "@/pages/admin/Index.vue";
import MyPage from "@/pages/admin/MyPage.vue";
import AdminList from "@/pages/admin/admins/AdminList.vue";
import AdminCreate from "@/pages/admin/admins/AdminCreate.vue";
import AdminEdit from "@/pages/admin/admins/AdminEdit.vue";
import UserList from "@/pages/admin/users/UserList.vue";
import UserCreate from "@/pages/admin/users/UserCreate.vue";
import UserPage from "@/pages/admin/users/UserPage.vue";
import UserInfomation from "@/pages/admin/users/UserInfomation.vue";
import WithdrawalUserList from "@/pages/admin/withdrawal/WithdrawalUserList.vue";
import PasswordUpdate from "@/pages/admin/users/PasswordUpdate.vue";
import AdminPasswordUpdate from "@/pages/admin/PasswordUpdate.vue";
import SystemError from "@/pages/errors/System.vue";
import NotFound from "@/pages/errors/NotFound.vue";

const stateAdmin = computed(() => store.state.admin.admin);

const routes = [
    {
        path: "/admin",
        component: Default,
        children: [
            {
                path: "/admin/login",
                beforeEnter(to, from, next) {
                    if (store.getters["admin/isAuthenticated"]) {
                        next("/admin");
                    } else {
                        next();
                    }
                },
                component: Login,
            },
            {
                path: "/admin/email/verify",
                beforeEnter(to, from, next) {
                    if (store.getters["admin/emailVerified"]) {
                        next("/admin");
                    } else if (!store.getters["admin/isAuthenticated"]) {
                        next({
                            path: "/admin/login",
                        });
                    } else {
                        next();
                    }
                },
                component: VerifyEmail,
                name: "VerifyEmail",
            },
            {
                path: "/admin",
                component: TheDashBoard,
                meta: { requiresAuthAdmin: true },
                children: [
                    {
                        path: "/admin",
                        component: Index,
                        meta: { requiresAuthAdmin: true },
                    },
                    {
                        path: "/admin/:id",
                        component: MyPage,
                        meta: { requiresAuthAdmin: true },
                        props: (route) => ({ id: Number(route.params.id) }),
                        beforeEnter: (to, from, next) => {
                            if (to.params.id == stateAdmin.value.id) {
                                next();
                            } else {
                                next("/admin");
                            }
                        },
                    },
                    {
                        path: "/admin/admins",
                        component: AdminList,
                        meta: { requiresAuthAdmin: true },
                        beforeEnter(to, from, next) {
                            if (!store.getters["admin/rootAdmin"]) {
                                next("/admin");
                            } else {
                                next();
                            }
                        },
                    },
                    {
                        path: "/admin/admins/create",
                        component: AdminCreate,
                        meta: { requiresAuthAdmin: true },
                        beforeEnter(to, from, next) {
                            if (!store.getters["admin/rootAdmin"]) {
                                next("/admin");
                            } else {
                                next();
                            }
                        },
                    },
                    {
                        path: "/admin/admins/:id/edit",
                        component: AdminEdit,
                        meta: { requiresAuthAdmin: true },
                        props: (route) => ({ id: Number(route.params.id) }),
                        beforeEnter(to, from, next) {
                            if (
                                !store.getters["admin/rootAdmin"] &&
                                to.params.id == stateAdmin.value.id
                            ) {
                                next("/admin");
                            } else {
                                next();
                            }
                        },
                    },
                    {
                        path: "/admin/users",
                        component: UserList,
                        meta: { requiresAuthAdmin: true },
                    },
                    {
                        path: "/admin/users/create",
                        component: UserCreate,
                        meta: { requiresAuthAdmin: true },
                    },
                    {
                        path: "/admin/users/create",
                        component: UserCreate,
                        meta: { requiresAuthAdmin: true },
                    },
                    {
                        path: "/admin/users/:id/userpage",
                        component: UserPage,
                        meta: { requiresAuthAdmin: true },
                    },
                    {
                        path: "/admin/users/:id/infomation",
                        component: UserInfomation,
                        meta: { requiresAuthAdmin: true },
                    },
                    {
                        path: "/admin/users/:id/password",
                        component: PasswordUpdate,
                        meta: { requiresAuthAdmin: true },
                    },
                    {
                        path: "/admin/:id/password",
                        component: AdminPasswordUpdate,
                        props: (route) => ({ id: Number(route.params.id) }),
                        meta: { requiresAuthAdmin: true },
                        beforeEnter: (to, from, next) => {
                            if (to.params.id == stateAdmin.value.id) {
                                next();
                            } else {
                                next("/admin");
                            }
                        },
                    },
                    {
                        path: "/admin/withdrawal",
                        component: WithdrawalUserList,
                        meta: { requiresAuthAdmin: true },
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
        ],
    },
];

const routerAdmin = createRouter({
    routes,
    history: createWebHistory(),
});

routerAdmin.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuthAdmin)) {
        if (
            !store.getters["admin/emailVerified"] &&
            store.getters["admin/isAuthenticated"]
        ) {
            next({
                path: "/admin/email/verify",
            });
        } else if (!store.getters["admin/isAuthenticated"]) {
            next({
                path: "/admin/login",
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default routerAdmin;
