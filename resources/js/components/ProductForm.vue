<template>
  <div
    class="modal fade"
    tabindex="-1"
    role="dialog"
    data-backdrop="static"
    ref="modal"
  >
    <div class="modal-dialog" role="document">
      <form @submit.prevent="save">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-if="this.product.id">
              Editing {{ product.name }}
            </h5>
            <h5 class="modal-title" v-else>Add new product</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="name">Name</label>
              <input
                type="text"
                name="name"
                id="name"
                v-model="form.name"
                class="form-control"
                :class="{ 'is-invalid': errors && errors.name }"
              />
              <div v-if="errors && errors.name" class="invalid-feedback">
                {{ errors.name[0] }}
              </div>
            </div>

            <div class="form-group">
              <label for="year">Year</label>
              <select
                name="year"
                id="year"
                v-model="form.year"
                class="form-control"
                :class="{ 'is-invalid': errors && errors.year }"
              >
                <option value="">Select one year</option>
                <option v-for="option in yearOptions" :key="option">
                  {{ option }}
                </option>
              </select>
              <div v-if="errors && errors.year" class="invalid-feedback">
                {{ errors.year[0] }}
              </div>
            </div>

            <div class="custom-file form-group">
              <label for="photo">Photo</label>
              <input
                type="file"
                class="custom-file-input"
                id="photo"
                accept="image/*"
                :class="{ 'is-invalid': errors && errors.photo }"
                @change="handleUpload"
              />
              <label class="custom-file-label" for="photo">Choose photo</label>
              <div v-if="errors && errors.photo" class="invalid-feedback">
                {{ errors.photo[0] }}
              </div>
            </div>

            <div class="row">
              <div v-if="filePreview" class="col-4">
                <label>Image Preview</label>
                <img :src="filePreview" class="img-thumbnail" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save changes</button>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import productsApi from "../api/products";

export default {
  props: {
    product: {
      type: Object,
      default: () => ({}),
    },
  },

  data() {
    return {
      form: {
        ...this.product,
      },

      file: null,

      errors: {},
    };
  },

  mounted() {
    this.$nextTick(() => {
      $(this.$refs.modal).modal("show");
    });

    $(this.$refs.modal).on("hidden.bs.modal", this.modelHidden);
  },

  computed: {
    yearOptions() {
      const start = 1900;
      const currentYear = new Date().getFullYear();
      const options = [];

      for (let i = start; i <= currentYear; i++) {
        options.push(i);
      }

      return options;
    },

    filePreview() {
      if (!this.file) {
        return this.product.photo_url;
      }

      return URL.createObjectURL(this.file);
    },
  },

  methods: {
    close() {
      $(this.$refs.modal).modal("hide");
    },

    modelHidden() {
      this.$emit("modal-closed");
    },

    handleUpload(e) {
      this.file = e.target.files[0] || null;
    },

    save() {
      if (this.product.id) {
        this.updateProduct();
      } else {
        this.createProduct();
      }
    },

    getFormData() {
      const formData = new FormData();

      Object.keys(this.form).forEach((k) => {
        formData.append(k, this.form[k]);
      });

      if (this.file) {
        formData.append("photo", this.file);
      }

      return formData;
    },

    updateProduct() {
      this.errors = {};

      productsApi
        .updateProduct(this.product.id, this.getFormData())
        .then((updatedProduct) => {
          this.$emit("product-updated", updatedProduct);
          this.close();
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    },

    createProduct() {
      this.errors = {};
      productsApi
        .createProduct(this.getFormData())
        .then((updatedProduct) => {
          this.$emit("product-updated", updatedProduct);
          this.close();
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    },
  },
};
</script>
