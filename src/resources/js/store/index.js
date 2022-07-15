import { createStore } from "vuex";

import admin from "./admin";
import user from "./user";
import error from "./error";
import message from './message'

const store = createStore({
    modules: {
        admin,
        user,
        error,
        message
    },
});

export default store;
