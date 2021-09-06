<template>
  <div class="products-component">
    <h1>Products</h1>

    <div class="card mt-4">
      <table class="table m-0 table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Year of manufacturing</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="product in products" :key="product.id">
            <td>{{ product.id }}</td>
            <td>{{ product.name }}</td>
            <td>{{ product.year }}</td>
            <td>
              <button
                class="btn btn-outline-primary btn-sm"
                @click="editProduct(product)"
              >
                Edit
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <product-form v-if="editingProduct" :product="editingProduct" @modal-closed="editProduct()" @product-updated="updateProductInList" />
  </div>
</template>

<script>
import products from '../api/products';
import productsApi from "../api/products";
import ProductForm from "./ProductForm.vue";

export default {
  components: { ProductForm },

  mounted() {
    this.getProductsData().then((products) => {
      this.setProducts(products);
    });
  },

  data: () => ({
    products: [],
    editingProduct: null,
  }),

  methods: {
    setProducts(products) {
      this.products = products;
    },

    getProductsData() {
      return productsApi.getProducts();
    },

    editProduct(product) {
      this.editingProduct = product;
    },

    updateProductInList(product) {
        const foundIndex = this.products.findIndex(item => item.id === product.id)

        if (foundIndex === -1) {
            return
        }

        this.products.splice(foundIndex, 1, product)
    }
  },
};
</script>
