<template>
  <div class="container--small">
    <ul class="tab">
      <li
        class="tab__item"
        :class="{ 'tab__item--active': data.tab === 1 }"
        @click="data.tab = 1"
      >
        Login
      </li>
      <li
        class="tab__item"
        :class="{ 'tab__item--active': data.tab === 2 }"
        @click="data.tab = 2"
      >
        Register
      </li>
    </ul>
    <div class="panel" v-show="data.tab === 1">
      <p class="mt-2 text-danger">{{ data.getUserMessage }}</p>
      <form class="form" @submit.prevent="login">
        <div v-if="loginErrors" class="errors">
          <ul v-if="loginErrors.email">
            <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="loginErrors.password">
            <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <label for="login-email">Email</label>
        <input
          type="text"
          class="form__item"
          id="login-email"
          v-model="loginForm.email"
        />
        <label for="login-password">Password</label>
        <input
          type="password"
          class="form__item"
          id="login-password"
          v-model="loginForm.password"
        />
        <div class="form__button">
          <button type="submit" class="button button--inverse">login</button>
        </div>
      </form>
    </div>
    <div class="panel" v-show="data.tab === 2">
      <form class="form" @submit.prevent="register">
        <div v-if="registerErrors" class="errors">
          <ul v-if="registerErrors.name">
            <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerErrors.email">
            <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerErrors.password">
            <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <label for="username">Name</label>
        <input
          type="text"
          class="form__item"
          id="username"
          v-model="registerForm.name"
        />
        <label for="email">Email</label>
        <input
          type="text"
          class="form__item"
          id="email"
          v-model="registerForm.email"
        />
        <label for="password">Password</label>
        <input
          type="password"
          class="form__item"
          id="password"
          v-model="registerForm.password"
        />
        <label for="password-confirmation">Password (confirm)</label>
        <input
          type="password"
          class="form__item"
          id="password-confirmation"
          v-model="registerForm.password_confirmation"
        />
        <div class="form__button">
          <button type="submit" class="button button--inverse">register</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from 'vue-router'

export default defineComponent({
  setup() {
    const store = useStore()
    const router = useRouter()

    const data = reactive({
      tab: 1,
      error: false,
      getUserMessage: "",
    })
    const loginForm = reactive({
      email: "",
      password: "",
    })
    const registerForm = reactive({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    })

    const loginErrors = computed(() => store.state.auth.loginErrorMessages)
    const registerErrors = computed(() => store.state.auth.registerErrorMessages)
    const apiStatus = computed(() => store.state.auth.apiStatus)

    const login = async () => {
      try {
        await store.dispatch("auth/login", loginForm);
        if (apiStatus.value) {
          router.push("/user");
        }
      } catch (err) {
        console.log('Failure');
      }
    }
    const register = async () => {
      try {
        await store.dispatch("auth/register", registerForm);
        // if (apiStatus.value) {
        //   router.push("/email/verify");
        // }
      } catch (err) {
        console.log('Failure');
      }
    }

    const clearError = () => {
      store.commit("auth/setLoginErrorMessages", null);
      store.commit("auth/setRegisterErrorMessages", null);
    }
    clearError()

    return {
      data,
      loginForm,
      registerForm,
      loginErrors,
      registerErrors,
      login,
      register,
    }
  },
});
</script>
