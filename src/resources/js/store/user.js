import { OK, CREATED, NO_CONTENT, UNPROCESSABLE_ENTITY } from "../util";

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null,
    forgotPasswordErrorMessages: null,
};

const getters = {
    isAuthenticated: (state) => !!state.user, //仮登録状態チェック
    username: (state) => (state.user ? state.user.name : ""),
    emailVerified: (state) =>
        state.user && state.user.email_verified_at ? true : false, //メール認証済みかチェック
};

const mutations = {
    setUser(state, user) {
        state.user = user;
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
        const response = await axios.post("/api/register", data);

        if (response.status === CREATED) {

            context.commit("setApiStatus", true);
            context.commit("setUser", response.data);
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

    // ログイン
    async login(context, data) {
        context.commit("setApiStatus", null);
        axios.get("/sanctum/csrf-cookie", { withCredentials: true });

        const response = await axios.post("/api/login", data);
        console.log(response);
        if (response.status === OK) {
            context.commit("setApiStatus", true);
            context.commit("setUser", response.data);
            return false;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setLoginErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // ログアウト
    async logout(context) {
        context.commit("setApiStatus", null);
        const response = await axios.post("/api/logout");

        if (response.status === NO_CONTENT) {
            context.commit("setApiStatus", true);
            context.commit("setUser", null);
            return false;
        }

        context.commit("setApiStatus", false);
        context.commit("error/setCode", response.status, { root: true });
    },

    // ログインユーザーチェック
    async currentUser(context) {
        context.commit("setApiStatus", null);
        const response = await axios.get("/api/user");
        const user = response.data || null;

        if (response.status === OK) {
            context.commit("setApiStatus", true);
            context.commit("setUser", user);
            return false;
        }

        context.commit("setApiStatus", false);
        context.commit("error/setCode", response.status, { root: true });
    },

    // パスワードリセットメール
    async forgotPassword(context, data) {
        context.commit("setApiStatus", null);

        const response = await axios.post("/api/forgot-password", data);
        console.log(response);
        if (response.status === OK) {
            context.commit("setApiStatus", true);
            return false;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit(
                "setForgotPasswordErrorMessages",
                response.data.errors
            );
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // パスワードリセット
    async resetPassword(context, data) {
        context.commit("setApiStatus", null);

        const response = await axios.post("/api/reset-password", data);
        console.log(response);
        if (response.status === OK) {
            context.commit("setApiStatus", true);
            return false;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setRegisterErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },
    // メールアドレス更新
    async updateEmail(context, data) {
        context.commit("setApiStatus", null);

        const response = await axios.put(`/api/user/profile-information`, data);
        console.log(response);
        if (response.status === OK) {
            context.commit("setApiStatus", true);
            //メールアドレスに変更があった場合、認証状態を更新して認証メールを送る
            context.commit("setUser", response.data);
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

        const response = await axios.put(`/api/user/password`, data);
        console.log(response);
        if (response.status === OK) {
            //パスワードが更新されたら強制的にログアウトする
            context.commit("setApiStatus", true);
            const user = await axios.get("/api/user");
            context.commit("setUser", null);
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
