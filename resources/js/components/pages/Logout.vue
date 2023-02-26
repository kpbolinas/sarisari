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
      .then(() => {
        localStorage.clear();
        sessionStorage.clear();
        Router.push("/login");
      })
      .catch(({ response }) => {
        if (response?.status === 401) {
          localStorage.setItem("auth-info", "");
          Router.push("/unauthorized");
        } else {
          const message = response.data?.message;
          console.log(message);
        }
      });
  },
};
</script>
