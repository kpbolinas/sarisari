<template>
  <div class="form-header-custom-color"><h2>REGISTER</h2></div>
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
        :class="{ 'is-invalid': formErrors?.email }"
        v-model="email"
      />
      <div class="invalid-feedback">{{ formErrors?.email }}</div>
    </div>
    <div class="mb-1 force-text-left">
      <label class="form-label" for="formGroupUsername"> Username </label>
      <input
        name="username"
        placeholder="Username"
        type="text"
        id="formGroupUsername"
        class="form-control"
        :class="{ 'is-invalid': formErrors?.username }"
        v-model="username"
      />
      <div class="invalid-feedback">{{ formErrors?.username }}</div>
    </div>
    <div class="mb-1 force-text-left">
      <label class="form-label" for="formGroupFirstname"> First name </label>
      <input
        name="first_name"
        placeholder="First name"
        type="text"
        id="formGroupFirstname"
        class="form-control"
        :class="{ 'is-invalid': formErrors?.first_name }"
        v-model="firstName"
      />
      <div class="invalid-feedback">{{ formErrors?.first_name }}</div>
    </div>
    <div class="mb-1 force-text-left">
      <label class="form-label" for="formGroupLastname"> Last name </label>
      <input
        name="last_name"
        placeholder="Last name"
        type="text"
        id="formGroupLastname"
        class="form-control"
        :class="{ 'is-invalid': formErrors?.last_name }"
        v-model="lastName"
      />
      <div class="invalid-feedback">{{ formErrors?.last_name }}</div>
    </div>
    <div class="mb-1 force-text-left">
      <label class="form-label" for="formGroupPassword">Password</label>
      <input
        name="password"
        placeholder="Password"
        type="password"
        id="formGroupPassword"
        class="form-control"
        :class="{ 'is-invalid': formErrors?.password }"
        v-model="password"
      />
      <div class="invalid-feedback">{{ formErrors?.password }}</div>
    </div>
    <div class="mb-2 force-text-left">
      <label class="form-label" for="formGroupConfirmPassword">
        Confirm Password
      </label>
      <input
        name="confirm_password"
        placeholder="Confirm Password"
        type="password"
        id="formGroupConfirmPassword"
        class="form-control"
        :class="{ 'is-invalid': formErrors?.confirm_password }"
        v-model="confirmPassword"
      />
      <div class="invalid-feedback">{{ formErrors?.confirm_password }}</div>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn-custom-color btn btn-outline-danger">
        SUBMIT
      </button>
    </div>
  </form>
  <div class="d-flex align-items-end flex-column">
    <RouterLink class="no-side-padding btn btn-link" to="/login">Login</RouterLink>
    <RouterLink class="no-side-padding btn btn-link" to="/forgot-password">Forgot Password</RouterLink>
  </div>
  <Spinner :show="showSpinner" />
  <template v-if="toastMessage">
    <Toast :message="toastMessage" :background="toastType" />
  </template>
</template>

<script>
import { RouterLink } from "vue-router";
import Router from "../../router";
import UserApiService from "../services/user";
import Spinner from "../common/Spinner.vue";
import Toast from "../common/Toast.vue";

export default {
  name: "RegistrationPage",
  components: {
    RouterLink,
    Spinner,
    Toast,
  },
  data() {
    return {
      email: "",
      username: "",
      firstName: "",
      lastName: "",
      password: "",
      confirmPassword: "",
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
      this.toastType = "";
      this.toastMessage = "";
      const formData = {
        email: this.email,
        username: this.username,
        first_name: this.firstName,
        last_name: this.lastName,
        password: this.password,
        confirm_password: this.confirmPassword,
      };
      await UserApiService.register(formData)
        .then((response) => {
          const { message } = response.data;
          this.toastType = "text-bg-success";
          this.toastMessage = message;
          this.clearForm();
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
              Router.push("/unauthorized");
              break;
            }
            default: {
              const message = response?.error
                ? response.error?.message
                : response?.data.message;
              this.toastType = "text-bg-danger";
              this.toastMessage = message;
              break;
            }
          }
        })
        .finally(() => (this.showSpinner = false));
    },
    clearForm() {
      this.email = "";
      this.username = "";
      this.firstName = "";
      this.lastName = "";
      this.password = "";
      this.confirmPassword = "";
    },
  },
};
</script>

<style scoped>
.form-header-custom-color {
  color: #a52a2a;
}
</style>
