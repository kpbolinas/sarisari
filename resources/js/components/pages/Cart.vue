<template>
  <div class="row">
    <div
      class="d-flex mt-3 text-center align-middle justify-content-center align-items-center"
      v-if="!Object.keys(products.items).length"
    >
      <div>Cart is empty.</div>
    </div>
    <div class="table-responsive no-side-padding" v-if="Object.keys(products.items).length">
      <table class="table table-striped table-bordered table-hover w-100">
        <thead>
          <tr>
            <th class="text-center">QUANTITY</th>
            <th class="w-25 text-center">NAME</th>
            <th class="w-25 text-center">PRICE</th>
            <th class="w-25 text-center">TOTAL</th>
            <th class="text-center">-</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr
            v-for="product in products.items"
            :key="product.id"
          >
          <td>
              {{ product?.quantity }}
            </td>
            <td>
              {{ product?.item.name }}
            </td>
            <td>
              P {{ product?.item.price }}
            </td>
            <td>
              P {{ product?.total }}
            </td>
            <td>
              <a
                class="btn btn-link"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-title="ADD"
                @click="addToCart(product?.item)"
              >
                <img src="../assets/icons/plus-square-fill.svg" alt="ADD" />
              </a>
              <a
                class="btn btn-link"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-title="DEDUCT"
                @click="deductToCart(product?.item)"
              >
                <img src="../assets/icons/dash-square-fill.svg" alt="DEDUCT" />
              </a>
              <a
                class="btn btn-link"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-title="REMOVE"
                @click="removeToCart(product?.item)"
              >
                <img src="../assets/icons/trash-fill.svg" alt="REMOVE" />
              </a>
            </td>
          </tr>
          <tr>
            <td colspan="3"></td>
            <td><span class="fw-bold">TOTAL PRICE:</span> P {{ products.totalPrice }}</td>
            <td>
              <button
                type="button"
                class="btn-custom-color btn btn-outline-danger"
                @click="checkout()"
              >
                CHECKOUT
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
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
import OrderApiService from "../services/order";
import Cart from '../stores/cart';
import Spinner from "../common/Spinner.vue";
import Toast from "../common/Toast.vue";
import Tooltip from "../common/tooltip";

export default {
  name: "CartPage",
  components: {
    Spinner,
    Toast,
  },
  data() {
    return {
      products: {
        items: {},
        totalPrice: 0,
      },
      showSpinner: false,
      toastType: "",
      toastMessage: "",
    }
  },
  created() {
    this.products = Cart.data;
  },
  methods: {
    async checkout() {
      this.showSpinner = true;
      const formData = {
        items: this.products.items,
        total_price: this.products.totalPrice,
      };
      await OrderApiService.checkout(formData)
        .then((response) => {
          const { data, message } = response.data;
          this.displayToast(message, "text-bg-success");
          Cart.clear();
          this.products = Cart.data;
        })
        .catch(({ response }) => {
            this.showSpinner = false;
          switch (response.status) {
            case 401: {
              localStorage.setItem("auth-info", "");
              Router.push("/unauthorized");
              break;
            }
            default: {
              const message = response?.error
                ? response.error?.message
                : response?.data.message;
              this.displayToast(message, "text-bg-danger");
              break;
            }
          }
        })
        .finally(() => this.showSpinner = false);
    },
    displayToast(message, type) {
      this.toastMessage = message;
      this.toastType = type;
    },
    addToCart(item) {
      Cart.add(item);
    },
    deductToCart(item) {
      Cart.deduct(item);
    },
    removeToCart(item) {
      Cart.remove(item);
    },
  },
  mounted() {
    Tooltip.init();
  },
}
</script>
