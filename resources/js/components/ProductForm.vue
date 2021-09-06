<template>
  <div class="modal fade" tabindex="-1" role="dialog" ref="modal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="save">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editing {{ product.name }}</h5>
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
              />
            </div>

            <div class="form-group">
              <label for="year">Year</label>
              <input
                type="text"
                name="year"
                id="year"
                v-model="form.year"
                class="form-control"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">
              Save changes
            </button>
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
import productsApi from '../api/products'

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
    };
  },

  mounted() {
    this.$nextTick(() => {
      $(this.$refs.modal).modal("show");
    });

    $(this.$refs.modal).on("hidden.bs.modal", this.modelHidden);
  },

  methods: {
    close() {
      $(this.$refs.modal).modal("hide");
    },

    modelHidden() {
      this.$emit("modal-closed");
    },

    save() {
        productsApi.updateProduct(this.product.id, this.form)
            .then(updatedProduct => {
                this.$emit('product-updated', updatedProduct)
                this.close()
            })
    },
  },
};
</script>
