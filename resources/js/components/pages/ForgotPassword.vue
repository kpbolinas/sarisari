<template>
  <div class="form-header-custom-color"><h2>FORGOT PASSWORD</h2></div>
  <form
    novalidate
    class="form-main form-main-border"
    @submit.prevent="handleSubmit"
  >
    <div class="mb-2 force-text-left">
      <label class="form-label" for="formGroupEmail">Email</label>
      <input
        name="email"
        placeholder="Email"
        type="email"
        id="formGroupEmail"
        class="form-control"
        :class="{ 'is-invalid': formErrors?.email }"
        v-model="email"
      />
      <div class="invalid-feedback">{{ formErrors?.email }}</div>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn-custom-color btn btn-outline-danger">
        SUBMIT
      </button>
    </div>
  </form>
  <div class="d-flex align-items-end flex-column">
    <RouterLink class="no-side-padding btn btn-link" to="/register">Register</RouterLink>
    <RouterLink class="no-side-padding btn btn-link" to="/login">Login</RouterLink>
  </div>
  <Spinner :show="showSpinner" />
  <template v-if="toastMessage">
    <Toast :message="toastMessage" :background="toastType" />
  </template>
</template>

<script>
import { RouterLink } from "vue-router";
import Route from "../../router";
import UserApiService from "../services/user";
import Spinner from "../common/Spinner.vue";
import Toast from "../common/Toast.vue";

export default {
  name: "ForgotPasswordPage",
  components: {
    RouterLink,
    Spinner,
    Toast,
  },
  data() {
    return {
      email: "",
      showSpinner: false,
      toastType: "",
      toastMessage: "",
      formErrors: null,
    };
  },
  methods: {
    async handleSubmit() {
      this.formErrors = null;
      this.showSpinner = true;
      this.toastType = "text-bg-danger";
      this.toastMessage = "";
      const formData = { email: this.email };
      await UserApiService.forgotPassword(formData)
        .then((response) => {
          const { message } = response.data;
          this.toastType = "text-bg-success";
          this.toastMessage = message;
        })
        .catch(({ response }) => {
          this.showSpinner = false;
          switch (response.status) {
            case 422: {
              const result = response.data;
              const responseErrors = result.errors;
              const errorKeys = Object.keys(responseErrors);
              let formErrors = {};
              errorKeys?.forEach(
                (errorKey) =>
                  (formErrors[errorKey] = responseErrors[errorKey][0])
              );
              this.formErrors = formErrors;
              break;
            }
            case 401: {
              Route.push("/unauthorized");
              break;
            }
            default: {
              const message = response?.error
                ? response.error?.message
                : response?.data.message;
              this.toastMessage = message;
              break;
            }
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