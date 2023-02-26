<template>
  <div
    id="da-modal"
    class="modal fade"
    aria-hidden="true"
    aria-labelledby="da-modal-label"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
  >
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header modal-header-custom">
        <div
            id="da-modal-label"
            class="w-100 d-flex justify-content-center modal-title h4"
        >
            DELETE ACCOUNT
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
            Are you sure you want to delete your account?
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
import UserApiService from "../services/user";

export default {
  name: "DeleteAccount",
  emits: ["modalShow", "displaySpinner", "displayToast"],
  methods: {
    async confirm() {
      this.$emit("displaySpinner", true);
      await UserApiService.delete()
        .then((response) => {
          const { message } = response.data;
          this.$emit("displayToast", message, "text-bg-success");
          this.$emit("modalShow", false);
          localStorage.setItem("auth-info", "");
          Router.push("/login");
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
  