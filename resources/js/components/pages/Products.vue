<template>
  <div class="row filter-form-container" v-if="products">
    <div
      class="col-1 d-flex justify-content-center align-items-center filter-form-label"
    >
      FILTER :
    </div>
    <div class="col-11">
      <div class="row">
        <div class="col">
          <div>
            <select
              name="sort"
              class="form-select"
              :value="sort"
              @input="(event) => setSort(event.target.value)"
            >
              <option value="1">Name - Ascending</option>
              <option value="2">Name - Descending</option>
            </select>
          </div>
        </div>
        <div class="col"></div>
        <div class="col d-flex justify-content-end">
          <button
            v-if="[1, 2].includes(role)"
            type="button"
            class="btn-custom-color btn btn-outline-danger"
            @click="showAddProductModal()"
          >
            ADD PRODUCT
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div
      class="d-flex mt-3 text-center align-middle justify-content-center align-items-center"
      v-if="!products"
    >
      No record(s) found.
    </div>
    <div class="table-responsive no-side-padding" v-if="products">
      <table class="table table-striped table-bordered table-hover w-100">
        <thead>
          <tr>
            <th class="w-50 text-center">NAME</th>
            <th class="w-25 text-center">PRICE</th>
            <th class="text-center">-</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr
            v-for="product in products"
            :key="product.id"
          >
            <td>
              {{ product.name }}
            </td>
            <td>
              P {{ product.price }}
            </td>
            <td>
              <a
                v-if="role === 3"
                class="btn btn-link"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-title="ADD TO CART"
                @click="addToCart(product)"
              >
                <img src="../assets/icons/cart-plus-fill.svg" alt="ADD TO CART" />
              </a>
              <a
                v-if="[1, 2].includes(role)"
                class="btn btn-link"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-title="UPDATE"
                @click="showUpdateProductModal(product)"
              >
                <img src="../assets/icons/pencil-fill.svg" alt="UPDATE" />
              </a>
              <a
                v-if="[1, 2].includes(role)"
                class="btn btn-link"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-title="DELETE"
                @click="showDeleteProductModal(product)"
              >
                <img src="../assets/icons/trash-fill.svg" alt="DELETE" />
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <Pagination :page="page" :lastPage="lastPage" @setPage="setPage" />
  <AddProductModal
    @modalHide="hideAddProductModal"
    @reloadProducts="loadProducts"
    @displaySpinner="displaySpinner"
    @displayToast="displayToast"
  />
  <UpdateProductModal
    :info="selectedProduct"
    @modalHide="hideUpdateProductModal"
    @reloadProducts="loadProducts"
    @displaySpinner="displaySpinner"
    @displayToast="displayToast"
  />
  <DeleteProductModal
    :info="selectedProduct"
    @modalHide="hideDeleteProductModal"
    @reloadProducts="loadProducts"
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
import ProductApiService from "../services/product";
import Pagination from "../common/Pagination.vue";
import Spinner from "../common/Spinner.vue";
import Toast from "../common/Toast.vue";
import Tooltip from "../common/tooltip";
import Modal from "../modals/modal";
import AddProductModal from "../modals/AddProduct.vue"
import UpdateProductModal from "../modals/UpdateProduct.vue"
import DeleteProductModal from "../modals/DeleteProduct.vue"
import Cart from "../stores/cart";

export default {
  name: "ProfilePage",
  components: {
    Spinner,
    Toast,
    Pagination,
    AddProductModal,
    UpdateProductModal,
    DeleteProductModal,
  },
  data() {
    return {
      role: 3,
      products: [],
      showSpinner: false,
      toastType: "",
      toastMessage: "",
      page: 1,
      lastPage: 1,
      sort: 1,
      apModal: null,
      upModal: null,
      dpModal: null,
      selectedProduct: null,
    };
  },
  methods: {
    async loadProducts() {
      this.showSpinner = true;
      let params = `/${this.page}/${this.sort}`;
      await ProductApiService.list(params)
        .then((response) => {
          const data = response.data?.data;
          this.products = data?.products;
          this.lastPage = data?.last_page;
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
    setPage(page) {
      this.page = page;
      this.setParams({ page: page });
      this.loadProducts();
    },
    setSort(value) {
      this.sort = value;
      this.setParams({ sort: value });
      this.loadProducts();
    },
    setParams(param) {
      const productParams = sessionStorage.getItem("product-params");
      let params = productParams ? JSON.parse(productParams) : {};
      params = {
        page: params.page ?? 1,
        sort: params.sort ?? 1,
        ...param,
      };
      sessionStorage.setItem("product-params", JSON.stringify(params));
    },
    showAddProductModal() {
      this.apModal.dialog.show();
    },
    hideAddProductModal() {
      this.apModal.dialog.hide();
    },
    showUpdateProductModal(value) {
      this.selectedProduct = value;
      this.upModal.dialog.show();
    },
    hideUpdateProductModal() {
      this.upModal.dialog.hide();
    },
    showDeleteProductModal(value) {
      this.selectedProduct = value;
      this.dpModal.dialog.show();
    },
    hideDeleteProductModal() {
      this.dpModal.dialog.hide();
    },
    addToCart(item) {
      Cart.add(item);
      this.displayToast(`"${item.name}" has been added to cart!`, "text-bg-success");
    }
  },
  created() {
    const authStorage = localStorage.getItem("auth-info");
    if (authStorage) {
        const auth = JSON.parse(authStorage) ?? null;
        this.role = auth?.role ?? 3;
    }
    const productParams = sessionStorage.getItem("product-params");
    let sessionParams = productParams ? JSON.parse(productParams) : {};
    const params = {
      page: sessionParams.page ?? 1,
      sort: sessionParams.sort ?? 1,
    };
    sessionStorage.setItem("product-params", JSON.stringify(params));
    this.page = params.page;
    this.sort = params.sort;
    this.loadProducts();
  },
  mounted() {
    this.apModal = Modal.set("#ap-modal");
    this.upModal = Modal.set("#up-modal");
    this.dpModal = Modal.set("#dp-modal");
    Modal.onHidden(
      this.upModal.element,
      () => (this.selectedProduct = null)
    );
    Modal.onHidden(
      this.dpModal.element,
      () => (this.selectedProduct = null)
    );
  },
  updated() {
    Tooltip.init();
  },
};
</script>

<style scoped>
.filter-form-container {
  margin-top: 10px;
  margin-bottom: 10px;
}

.filter-form-label {
  font-weight: bold;
  padding: 0 5px;
}
</style>
  