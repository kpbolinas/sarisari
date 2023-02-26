<template>
  <div class="container-fluid h-100">
    <div id="header-main" class="row">
      <div
        id="header-logo-container"
        class="col-2 d-flex text-center align-middle justify-content-center align-items-center"
      >
        <div id="header-logo" class="d-flex justify-content-center">
          <img
            src="../assets/icons/shop.svg"
            alt="SARI SARI STORE"
          />
          &nbsp;SARI SARI STORE
        </div>
      </div>
      <div
        id="header-title"
        class="col-10 d-flex text-center align-middle justify-content-center align-items-center"
      >
        <HeaderTitle />
      </div>
    </div>
    <div class="row h-100">
      <div id="sidebar-container" class="col-2">
        <SideBar />
      </div>
      <div class="col-10">
        <RouterView />
      </div>
    </div>
  </div>
</template>

<script>
import { RouterView } from "vue-router";
import SideBar from "./SideBar.vue";
import HeaderTitle from "./Header.vue";
import Cart from "../stores/cart";

export default {
  name: "MainLayout",
  components: {
    RouterView,
    SideBar,
    HeaderTitle,
  },
  created() {
    Cart.initSession();
  },
};
</script>

<style scoped>
#header-main {
  color: #ffffff;
  background-color: #a52a2a;
  border: solid 0.1px #686868;
}

#header-logo-container {
  border-right: solid 0.1px #686868;
  padding: 5.5px 0;
}

#header-logo {
  margin: 5px;
  font-size: larger;
  font-weight: bold;
}

#header-title {
  font-weight: bold;
  font-size: x-large;
}

#cart-container {
  border-left: solid 0.1px #686868;
}

#sidebar-container {
  background: #a52a2a;
  padding: 0;
}
</style>
