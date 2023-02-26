<template>
  <div class="row account-profile-wrapper">
    <div
      class="col-4 d-flex text-center align-middle justify-content-center align-items-center"
    >
      <img
        src="../assets/images/default-profile.png"
        alt="Default Profile"
      />
    </div>
    <div class="col-8 d-flex align-middle align-items-center">
      <div class="row w-100 info-wrapper">
        <div>
          <span class="info-label">Email: </span><span>{{ info?.email }}</span>
        </div>
        <div>
          <span class="info-label">Username: </span>
          <span>{{ info?.username }}</span>
        </div>
        <div>
          <span class="info-label">First Name: </span>
          <span>{{ info?.first_name }}</span>
        </div>
        <div>
          <span class="info-label">Last Name: </span>
          <span>{{ info?.last_name }}</span>
        </div>
        <div>
          <span>
            <button
              type="button"
              class="btn-custom-color btn btn-outline-danger"
              @click="displayUpdateInfoModal(true)"
            >
              Update Info
            </button>
          </span>
          &nbsp;
          <span>
            <button
              type="button"
              class="btn-custom-color btn btn-outline-danger"
              @click="displayChangePassModal(true)"
            >
              Change Password
            </button>
          </span>
          &nbsp;
          <span>
            <button
              type="button"
              class="btn-custom-color btn btn-outline-danger"
              @click="displayDeleteAccModal(true)"
            >
              Delete Account
            </button>
          </span>
        </div>
      </div>
    </div>
  </div>
  <UpdateInfoModal
    :info="info"
    @modalShow="displayUpdateInfoModal"
    @reloadProfile="loadAccountProfile"
    @displaySpinner="displaySpinner"
    @displayToast="displayToast"
  />
  <ChangePassModal
    @modalShow="displayChangePassModal"
    @reloadProfile="loadAccountProfile"
    @displaySpinner="displaySpinner"
    @displayToast="displayToast"
  />
  <DeleteAccModal
    @modalShow="displayDeleteAccModal"
    @displaySpinner="displaySpinner"
    @displayToast="displayToast"
  />
  <Spinner :show="showSpinner" />
  <template v-if="toastMessage">
    <Toast
      :message="toastMessage"
      :background="toastType"
      @displayToast="displayToast"
    />
  </template>
</template>

<script>
import Router from "../../router";
import UserApiService from "../services/user";
import UpdateInfoModal from "../modals/UpdateInfo.vue";
import ChangePassModal from "../modals/ChangePassword.vue";
import DeleteAccModal from "../modals/DeleteAccount.vue";
import Spinner from "../common/Spinner.vue";
import Toast from "../common/Toast.vue";
import Modal from "../modals/modal";

export default {
  name: "AccountPage",
  components: {
    Spinner,
    Toast,
    UpdateInfoModal,
    ChangePassModal,
    DeleteAccModal,
  },
  data() {
    return {
      info: "",
      showSpinner: false,
      toastType: "",
      toastMessage: "",
      uiModal: null,
      cpModal: null,
      daModal: null,
    };
  },
  methods: {
    async loadAccountProfile() {
      this.showSpinner = true;
      await UserApiService.profile()
        .then((response) => {
          this.info = response.data.data;
        })
        .catch(({ response }) => {
          this.showSpinner = false;
          if (response.status === 401) {
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
    displaySpinner(value) {
      this.showSpinner = value;
    },
    displayToast(message, type) {
      this.toastMessage = message;
      this.toastType = type;
    },
    displayUpdateInfoModal(value) {
      if (value) {
        this.uiModal.dialog.show();
      } else {
        this.uiModal.dialog.hide();
      }
    },
    displayChangePassModal(value) {
      if (value) {
        this.cpModal.dialog.show();
      } else {
        this.cpModal.dialog.hide();
      }
    },
    displayDeleteAccModal(value) {
      if (value) {
        this.daModal.dialog.show();
      } else {
        this.daModal.dialog.hide();
      }
    },
  },
  created() {
    this.loadAccountProfile();
  },
  mounted() {
    this.uiModal = Modal.set("#ui-modal");
    this.cpModal = Modal.set("#cp-modal");
    this.daModal = Modal.set("#da-modal");
  },
}
</script>

<style scoped>
.account-profile-wrapper {
  border: solid 0.1px #a52a2a;
}

.info-wrapper > div {
  margin: 5px 0;
}

.info-label {
  font-weight: bold;
}
</style>