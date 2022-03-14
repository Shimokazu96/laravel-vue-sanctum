import { OK, CREATED, NO_CONTENT, UNPROCESSABLE_ENTITY } from '@/const/util'
import axios from 'axios'
import { reactive, Ref, toRefs } from '@nuxtjs/composition-api'
// export const state = () => ({
//   admin: null,
//   apiStatus: null,
//   loginErrorMessages: null,
//   registerErrorMessages: null,
//   forgotPasswordErrorMessages: null,
// });

export const state = () => {
  return toRefs(
    reactive({
      admin: null,
      loginStateConfirm: null, //リロード時にログイン済みか確認する
      apiStatus: null,
      loginErrorMessages: null,
      registerErrorMessages: null,
      forgotPasswordErrorMessages: null,
    })
  )
}

// https://qiita.com/tubone/items/f5c7e8e79e21b051eec4
// StateをtoRefs化したことによりcomputeした結果がオブジェクトでかえってきてしまう
export const getters = {
  isAuthenticated: (state) => (state.admin.value ? true : false), //ユーザー登録済み
  adminName: (state) => (state.admin.value ? state.admin.value.name : ''),
  adminId: (state) => (state.admin.value ? state.admin.value.id : ''),
  // emailVerified: (state) =>
  //   state.admin.value && state.admin.value.email_verified_at ? true : false, //メール認証済み
}

export const mutations = {
  setAdministrator(state, admin) {
    state.admin.value = admin
  },
  setLoginStateConfirm(state, status) {
    state.loginStateConfirm.value = status
  },
  setApiStatus(state, status) {
    state.apiStatus.value = status
  },
  setLoginErrorMessages(state, messages) {
    state.loginErrorMessages.value = messages
  },
  setRegisterErrorMessages(state, messages) {
    state.registerErrorMessages.value = messages
  },
  setForgotPasswordErrorMessages(state, messages) {
    state.forgotPasswordErrorMessages.value = messages
  },
}

export const actions = {
  // 会員登録
  async register(context, data) {
    context.commit('setApiStatus', null)
    await axios
      .post('/api/admin/register', data)
      .then((response) => {
        console.log(response.data)
        if (response.status === CREATED) {
          context.commit('setApiStatus', true)
          context.commit('setAdministrator', response.data)
          return false
        }
      })
      .catch((err) => {
        console.log(err.response)
        context.commit('setApiStatus', false)
        if (err.response.status === UNPROCESSABLE_ENTITY) {
          context.commit('setRegisterErrorMessages', err.response.data.errors)
        } else {
          context.commit('error/setCode', err.response.status, { root: true })
        }
      })
  },
  // ログイン
  async login(context, data) {
    context.commit('setApiStatus', null)
    // ログイン処理前にCSRFトークンを初期化
    axios.get('/sanctum/csrf-cookie', { withCredentials: true })
    await axios
      .post('/api/admin/login', data)
      .then((response) => {
        console.log(response.data)
        if (response.status === OK) {
          context.commit('setApiStatus', true)
          context.commit('setAdministrator', response.data)
          return false
        }
      })
      .catch((err) => {
        console.log(err)
        context.commit('setApiStatus', false)
        if (err.response.status === UNPROCESSABLE_ENTITY) {
          context.commit('setLoginErrorMessages', err.response.data.errors)
        } else {
          context.commit('error/setCode', err.response.status, { root: true })
        }
      })
  },

  // ログアウト
  async logout(context) {
    context.commit('setApiStatus', null)
    await axios
      .post('/api/admin/logout')
      .then((response) => {
        console.log(response.data)
        if (response.status === NO_CONTENT) {
          context.commit('setApiStatus', true)
          context.commit('setAdministrator', null)
          return false
        }
      })
      .catch((err) => {
        console.log(err.response)
        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
      })
  },

  // ログインユーザーチェック
  async currentAdministrator(context) {
    context.commit('setApiStatus', null)
    await axios
      .get('/api/admin')
      .then((response) => {
        console.log(response)
        context.commit('setApiStatus', true)
        context.commit('setLoginStateConfirm', true)
        context.commit('setAdministrator', response.data)
        return false
      })
      .catch((err) => {
        console.log(err.response)
        context.commit('setApiStatus', false)
        context.commit('setLoginStateConfirm', true)
        context.commit('error/setCode', err.response.status, { root: true })
      })
  },

  // // パスワードリセットメール
  // async forgotPassword(context, data) {
  //   context.commit('setApiStatus', null)
  //   await axios
  //     .post('/api/forgot-password', data)
  //     .then((response) => {
  //       console.log(response.data)
  //       if (response.status === OK) {
  //         context.commit('setApiStatus', true)
  //         context.commit(
  //           'message/setContent',
  //           {
  //             content: response.data.message,
  //             timeout: 6000,
  //           },
  //           { root: true }
  //         )
  //         return false
  //       }
  //     })
  //     .catch((err) => {
  //       console.log(err.response)
  //       context.commit('setApiStatus', false)
  //       if (err.response.status === UNPROCESSABLE_ENTITY) {
  //         context.commit(
  //           'setForgotPasswordErrorMessages',
  //           err.response.data.errors
  //         )
  //       } else {
  //         context.commit('error/setCode', err.response.status, { root: true })
  //       }
  //     })
  // },

  // // パスワードリセット
  // async resetPassword(context, data) {
  //   context.commit('setApiStatus', null)
  //   await axios
  //     .post('/api/reset-password', data)
  //     .then((response) => {
  //       console.log(response.data)
  //       if (response.status === OK) {
  //         context.commit('setApiStatus', true)
  //         context.commit(
  //           'message/setContent',
  //           {
  //             content: response.data.message,
  //             timeout: 6000,
  //           },
  //           { root: true }
  //         )
  //         return false
  //       }
  //     })
  //     .catch((err) => {
  //       console.log(err.response)
  //       context.commit('setApiStatus', false)
  //       if (err.response.status === UNPROCESSABLE_ENTITY) {
  //         context.commit('setRegisterErrorMessages', err.response.data.errors)
  //       } else {
  //         context.commit('error/setCode', err.response.status, { root: true })
  //       }
  //     })
  // },
}
