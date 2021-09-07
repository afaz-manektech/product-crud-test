<template>
  <div class="products-component">
    <div class="d-flex justify-content-between align-items-end">
        <h1>Products</h1>

        <button class="btn btn-sm btn-outline-primary" @click="addNewProduct">New Product</button>
    </div>

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
              <button
                class="btn btn-outline-danger btn-sm"
                @click="deleteProduct(product)"
              >
                Delete
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
            this.products.push(product)
            return
        }

        this.products.splice(foundIndex, 1, product)
    },

    addNewProduct() {
        this.editingProduct = {
            name: null,
            year: null,
            image: null,
        }
    },

    deleteProduct(product) {
        if (confirm('Are you sure, you want to delete this product ?')) {
            productsApi.deleteProduct(product.id)
                .then(() => {
                    const index = this.products.findIndex((item) => item.id === product.id)

                    if (index >= 0) {
                        this.products.splice(index, 1);
                    }
                })
        }
    }
  },
};
</script>
