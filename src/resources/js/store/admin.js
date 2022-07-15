import { OK, CREATED, NO_CONTENT, UNPROCESSABLE_ENTITY } from "../util";

const state = {
    admin: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null,
    forgotPasswordErrorMessages: null,
};

const getters = {
    isAuthenticated: (state) => !!state.admin,
    emailVerified: (state) => state.admin && state.admin.email_verified_at ? true : false, //メール認証済みかチェック
    rootAdmin: (state) => state.admin && state.admin.role == 1 ? true : false, // フル権限の管理者かどうかチェック
};

const mutations = {
    setAdmin(state, admin) {
        state.admin = admin;
    },
    setApiStatus(state, status) {
        state.apiStatus = status;
    },
    setLoginErrorMessages(state, messages) {
        state.loginErrorMessages = messages;
    },
    setRegisterErrorMessages(state, messages) {
        state.registerErrorMessages = messages;
    },
    setForgotPasswordErrorMessages(state, messages) {
        state.forgotPasswordErrorMessages = messages;
    },
    setUpdateEmailErrorMessages(state, messages) {
        state.updateEmailErrorMessages = messages;
    },
    setUpdatePasswordErrorMessages(state, messages) {
        state.updatePasswordErrorMessages = messages;
    },
};

const actions = {
    // 会員登録
    async register(context, data) {
        context.commit("setApiStatus", null);
        const response = await axios.post("/api/admin/register", data);

        if (response.status === CREATED) {

            context.commit("setApiStatus", true);
            context.commit("setAdmin", response.data);
            return false;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setRegisterErrorMessages", response.data.errors);
        } else {
            // 別モジュール（ストア）のミューテーションを呼び出す場合は第三引数に{ root: true }を定義
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // ログイン(2段階認証)
    async login(context, data) {
        context.commit("setApiStatus", null);
        axios.get("/sanctum/csrf-cookie", { withCredentials: true });

        const response = await axios.post("/api/admin/second-auth", data);
        console.log(response);
        if (response.status === OK) {
            context.commit("setApiStatus", true);
            context.commit("setAdmin", response.data);
            return false;
        }

        context.commit("setApiStatus", false);
        if (response.status !== OK) {
            context.commit("setLoginErrorMessages", response.data.message);
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // ログアウト
    async logout(context) {
        context.commit("setApiStatus", null);
        const response = await axios.post("/api/admin/logout");

        if (response.status === NO_CONTENT) {
            context.commit("setApiStatus", true);
            context.commit("setAdmin", null);
            return false;
        }

        context.commit("setApiStatus", false);
        context.commit("error/setCode", response.status, { root: true });
    },

    // ログインユーザーチェック
    async currentAdmin(context) {
        context.commit("setApiStatus", null);
        const response = await axios.get("/api/admin");
        console.log(response.data);
        const admin = response.data || null;

        if (response.status === OK) {
            context.commit("setApiStatus", true);
            context.commit("setAdmin", admin);
            return false;
        }

        context.commit("setApiStatus", false);
        context.commit("error/setCode", response.status, { root: true });
    },

    // パスワードリセットメール
    // async forgotPassword(context, data) {
    //     context.commit("setApiStatus", null);

    //     const response = await axios.post("/api/admin/forgot-password", data);
    //     console.log(response);
    //     if (response.status === OK) {
    //         context.commit("setApiStatus", true);
    //         return false;
    //     }

    //     context.commit("setApiStatus", false);
    //     if (response.status === UNPROCESSABLE_ENTITY) {
    //         context.commit(
    //             "setForgotPasswordErrorMessages",
    //             response.data.errors
    //         );
    //     } else {
    //         context.commit("error/setCode", response.status, { root: true });
    //     }
    // },

    // パスワードリセット
    // async resetPassword(context, data) {
    //     context.commit("setApiStatus", null);

    //     const response = await axios.post("/api/admin/reset-password", data);
    //     console.log(response);
    //     if (response.status === OK) {
    //         context.commit("setApiStatus", true);
    //         return false;
    //     }

    //     context.commit("setApiStatus", false);
    //     if (response.status === UNPROCESSABLE_ENTITY) {
    //         context.commit("setRegisterErrorMessages", response.data.errors);
    //     } else {
    //         context.commit("error/setCode", response.status, { root: true });
    //     }
    // },
    // メールアドレス更新
    async updateEmail(context, data) {
        context.commit("setApiStatus", null);

        const response = await axios.put(`/api/admin/profile-information`, data);
        console.log(response);
        if (response.status === OK) {
            context.commit("setApiStatus", true);
            //メールアドレスに変更があった場合、認証状態を更新して認証メールを送る
            context.commit("setAdmin", response.data);
            return false;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setUpdateEmailErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },
    // パスワード更新
    async updatePassword(context, data) {
        context.commit("setApiStatus", null);

        const response = await axios.put(`/api/admin/password`, data);
        console.log(response);
        if (response.status === OK) {
            //パスワードが更新されたら強制的にログアウトする
            context.commit("setApiStatus", true);
            const admin = await axios.get("/api/admin");
            context.commit("setAdmin", null);
            return false;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setUpdatePasswordErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
