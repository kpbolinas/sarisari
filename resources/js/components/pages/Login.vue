<template>
  <div class="form-header-custom-color"><h2>LOGIN</h2></div>
  <form
    novalidate
    @submit.prevent="handleSubmit"
  >
    <div class="mb-1 force-text-left">
      <label class="form-label" for="formGroupEmail">Email</label>
      <input
        name="email"
        placeholder="Email"
        type="email"
        id="formGroupEmail"
        class="form-control"
        v-model="email"
      />
    </div>
    <div class="mb-2 force-text-left">
      <label class="form-label" for="formGroupPassword">Password</label>
      <input
        name="password"
        placeholder="Password"
        type="password"
        id="formGroupPassword"
        class="form-control"
        v-model="password"
      />
    </div>
    <div class="d-grid">
      <button type="submit" class="btn-custom-color btn btn-outline-danger">
        LOGIN
      </button>
    </div>
  </form>
  <div class="d-flex align-items-end flex-column">
    <RouterLink class="no-side-padding btn btn-link" to="/forgot-password">Forgot Password</RouterLink>
    <RouterLink class="no-side-padding btn btn-link" to="/register">Register</RouterLink>
  </div>
  <Spinner :show="showSpinner" />
  <template v-if="toastMessage">
    <Toast :message="toastMessage" />
  </template>
</template>

<script>
import { RouterLink } from "vue-router";
import Router from "../../router";
import UserApiService from "../services/user";
import Spinner from "../common/Spinner.vue";
import Toast from "../common/Toast.vue";

export default {
  name: "LoginPage",
  components: {
    RouterLink,
    Spinner,
    Toast,
  },
  data() {
    return {
      email: "",
      password: "",
      showSpinner: false,
      toastMessage: "",
    };
  },
  methods: {
    async handleSubmit() {
      this.showSpinner = true;
      this.toastMessage = "";
      const formData = { email: this.email, password: this.password };
      await UserApiService.login(formData)
        .then((response) => {
          const { data } = response.data;
          // Set auth token
          const auth = data ? JSON.stringify(data) : null;
          localStorage.setItem("auth-info", auth);
          Router.push("/products");
        })
        .catch(({ response }) => {
          this.showSpinner = false;
          if (response?.status === 401) {
            localStorage.setItem("auth-info", "");
            Router.push("/unauthorized");
          } else {
            const message = response?.error
              ? response.error?.message
              : response?.data.message;
            this.toastMessage = message;
          }
        })
        .finally(() => (this.showSpinner = false));
    },
  },
}
</script>

<style scoped>
.form-header-custom-color {
  color: #a52a2a;
}
</style>