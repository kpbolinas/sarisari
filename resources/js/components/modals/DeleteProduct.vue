<template>
  <div
    id="dp-modal"
    class="modal fade"
    aria-hidden="true"
    aria-labelledby="dp-modal-label"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
  >
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header modal-header-custom">
        <div
            id="dp-modal-label"
            class="w-100 d-flex justify-content-center modal-title h4"
        >
            DELETE PRODUCT
        </div>
        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">
        <div class="d-flex text-center">
            Are you sure you want to delete this product?
            <br>
            "{{ info?.name }}"
        </div>
        <br />
        <button
            type="button"
            class="btn-custom-color btn btn-outline-danger w-100"
            @click="confirm"
        >
            CONFIRM
        </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Router from "../../router";
import ProductApiService from "../services/product";

export default {
  name: "DeleteProduct",
  emits: ["modalHide", "reloadProducts", "displaySpinner", "displayToast"],
  props: {
    info: {
      type: [Object, null],
      required: true,
    },
  },
  methods: {
    async confirm() {
      if (!this.info?.id) {
        this.$emit("modalHide");
        return;
      }
      this.$emit("displaySpinner", true);
      await ProductApiService.delete(this.info?.id)
        .then((response) => {
          const { message } = response.data;
          this.$emit("displayToast", message, "text-bg-success");
          this.$emit("modalHide");
          this.$emit("reloadProducts");
        })
        .catch(({ response }) => {
          this.$emit("displaySpinner", false);
          if (response.status === 401) {
            localStorage.setItem("auth-info", "");
            Router.push("/unauthorized");
          } else {
            const message = response?.error
              ? response.error?.message
              : response?.data.message;
            this.$emit("displayToast", message, "text-bg-danger");
          }
        })
        .finally(() => this.$emit("displaySpinner", false));
    },
  },
};
</script>

<style scoped>
.modal-header-custom {
  background-color: #a52a2a;
  color: #ffffff;
}
</style>
  