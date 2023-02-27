<template>
  <div></div>
</template>

<script>
import UserApiService from "../services/user";
import Router from "../../router";

export default {
  name: "LogoutPage",
  async beforeCreate() {
    await UserApiService.logout()
      .then(() => {})
      .catch(() => {})
      .finally(() => {
        localStorage.clear();
        sessionStorage.clear();
        Router.push("/login");
      });
  },
};
</script>
