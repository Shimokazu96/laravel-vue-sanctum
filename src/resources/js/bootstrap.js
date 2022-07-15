import Axios, { AxiosStatic } from "axios";

window._ = require("lodash");

// window.axios=require('axios');
window.axios = Axios;

// レスポンスを受けた後の処理を上書きする
// これを書くことでstore/user.jsのユーザー登録、ログイン、ログアウト、ログインユーザー取得で毎回
// .catch(error => error.response || error)を書かなくて済む
window.axios.interceptors.response.use(
  (response) => response,
  (error) => error.response || error
);
// Ajaxリクエストであることを示すヘッダーを付与する
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;
