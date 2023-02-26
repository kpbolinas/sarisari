<template>
  <div
    id="up-modal"
    class="modal fade"
    aria-hidden="true"
    aria-labelledby="up-modal-label"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-header-custom">
          <div
            id="up-modal-label"
            class="w-100 d-flex justify-content-center modal-title h4"
          >
            UPDATE PRODUCT
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
              <label class="form-label" for="formGroupName">
                Name
              </label>
              <input
                name="name"
                placeholder="Name"
                type="text"
                id="formGroupName"
                class="form-control"
                :class="{ 'is-invalid': formErrors?.name }"
                v-model="name"
              />
              <div class="invalid-feedback">{{ formErrors?.name }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="formGroupPrice">
                Price
              </label>
              <input
                name="price"
                placeholder="Price"
                type="text"
                id="formGroupPrice"
                class="form-control"
                :class="{ 'is-invalid': formErrors?.price }"
                v-model="price"
              />
              <div class="invalid-feedback">{{ formErrors?.price }}</div>
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
import Router from "../../router";
import ProductApiService from "../services/product";

export default {
  name: "UpdateProduct",
  emits: ["modalHide", "reloadProducts", "displaySpinner", "displayToast"],
  props: {
    info: {
      type: [Object, null],
      required: true,
    },
  },
  data() {
    return {
      name: "",
      price: "",
      formErrors: null,
    };
  },
  watch: {
    info: {
      handler(data) {
        this.name = data?.name;
        this.price = data?.price;
      }
    }
  },
  methods: {
    async handleSubmit() {
      this.formErrors = null;
      this.$emit("displaySpinner", true);
      const formData = {
        name: this.name,
        price: this.price,
      };
      await ProductApiService.update(this.info?.id, formData)
        .then((response) => {
          const { message } = response.data;
          this.clearForm();
          this.$emit("displayToast", message, "text-bg-success");
          this.$emit("modalHide", false);
          this.$emit("reloadProducts");
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
    clearForm() {
      this.name = "";
      this.price = "";
    },
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
