<template>
  <div
    id="ui-modal"
    class="modal fade"
    aria-hidden="true"
    aria-labelledby="ui-modal-label"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-header-custom">
          <div
            id="ui-modal-label"
            class="w-100 d-flex justify-content-center modal-title h4"
          >
            UPDATE INFO
          </div>
          <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <form novalidate class="form-main" @submit.prevent="handleSubmit">
            <div class="mb-3">
              <label class="form-label" for="formGroupUsername">
                Username
              </label>
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
            <div class="mb-3">
              <label class="form-label" for="formGroupFirstname">
                First name
              </label>
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
            <div class="mb-3">
              <label class="form-label" for="formGroupLastname">
                Last name
              </label>
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
            <div class="d-grid">
              <button
                type="submit"
                class="btn-custom-color btn btn-outline-danger"
              >
                SAVE
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { watch } from "vue";
import Router from "../../router";
import UserApiService from "../services/user";

export default {
  name: "EditInfo",
  emits: ["modalShow", "reloadProfile", "displaySpinner", "displayToast"],
  props: {
    info: {
      type: [Object, String],
      required: true,
    },
  },
  data() {
    return {
      username: "",
      firstName: "",
      lastName: "",
      formErrors: null,
    };
  },
  methods: {
    async handleSubmit() {
      this.formErrors = null;
      this.$emit("displaySpinner", true);
      const formData = {
        username: this.username,
        first_name: this.firstName,
        last_name: this.lastName,
      };
      await UserApiService.editInfo(formData)
        .then((response) => {
          const { message } = response.data;
          this.$emit("displayToast", message, "text-bg-success");
          this.$emit("modalShow", false);
          this.$emit("reloadProfile");
        })
        .catch(({ response }) => {
          this.$emit("displaySpinner", false);
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
              localStorage.setItem("auth-info", "");
              Router.push("/unauthorized");
              break;
            }
            default: {
              const message = response?.error
                ? response.error?.message
                : response?.data.message;
              this.$emit("displayToast", message, "text-bg-danger");
              break;
            }
          }
        })
        .finally(() => this.$emit("displaySpinner", false));
    },
  },
  mounted() {
    watch(
      () => this.info,
      ({ username, first_name, last_name }) => {
        this.username = username;
        this.firstName = first_name;
        this.lastName = last_name;
      }
    );
  },
};
</script>

<style scoped>
.modal-header-custom {
  background-color: #a52a2a;
  color: #ffffff;
}

.form-main {
  padding: 20px;
}
</style>