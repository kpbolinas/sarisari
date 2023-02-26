<template>
    <div class="flex-column nav nav-pills nav-justified" id="sidebar">
      <RouterLink class="nav-link" to="/products">Products</RouterLink>
      <template v-if="role === 3">
        <RouterLink class="nav-link" to="/cart">Cart</RouterLink>
      </template>
      <RouterLink class="nav-link" to="/account">Account</RouterLink>
      <RouterLink class="nav-link" to="/logout">Logout</RouterLink>
    </div>
  </template>
  
  <script>
  import { RouterLink } from "vue-router";
  
  export default {
    name: "SideBar",
    components: {
      RouterLink,
    },
    data() {
      return {
        role: 3, // Default to customer
      };
    },
    created() {
      const authStorage = localStorage.getItem("auth-info");
      if (authStorage) {
          const auth = JSON.parse(authStorage) ?? null;
          this.role = auth?.role ?? 3;
      }
    },
  };
  </script>
  
  <style scoped>
  #sidebar > .nav-link {
    color: #ffffff;
    border: solid 0.1px #686868;
    border-radius: 0;
  }
  
  #sidebar a.router-link-exact-active {
    color: #000000;
    background-color: #ffffff;
    border: solid 0.1px #686868;
  }
  </style>
  