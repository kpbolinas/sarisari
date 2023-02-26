<template>
  <div class="container-fluid h-100">
    <div class="h-100 d-flex text-center justify-content-center align-items-center">
      <div class="inner-container w-50">
        <div id="header-main" class="d-flex justify-content-center align-items-center">
          <img
            src="../assets/icons/shop.svg"
            alt="SARI SARI STORE"
          />
          &nbsp;SARI SARI STORE
        </div>
        <RouterView />
      </div>
    </div>
  </div>
</template>

<script>
import { RouterView } from "vue-router";

export default {
  name: "GuestLayout",
  components: {
    RouterView,
  },
};
</script>

<style scoped>
.inner-container {
  border: solid 0.1px #686868;
  border-radius: 5px;
  padding: 20px;
}

#header-main {
  color: #ffffff;
  background-color: #a52a2a;
  padding: 10px;
  font-size: larger;
  font-weight: bold;
}
</style>