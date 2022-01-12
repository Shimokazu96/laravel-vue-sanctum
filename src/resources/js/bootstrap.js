import { getCookieValue } from "./util";
import Axios, { AxiosStatic } from "axios";

window._ = require("lodash");

try {
  require("bootstrap");
} catch (e) {}

// window.axios=require('axios');
window.axios = Axios;

// リクエストを送るときに実行する処理
window.axios.interceptors.request.use((config) => {
  // クッキーからトークンを取り出してヘッダーに添付する
  config.headers["X-XSRF-TOKEN"] = getCookieValue("XSRF-TOKEN");

  return config;
});
// レスポンスを受けた後の処理を上書きする
// これを書くことでstore/auth.tsのユーザー登録、ログイン、ログアウト、ログインユーザー取得で毎回
// .catch(error => error.response || error)を書かなくて済む
window.axios.interceptors.response.use(
  (response) => response,
  (error) => error.response || error
);
// Ajaxリクエストであることを示すヘッダーを付与する
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
