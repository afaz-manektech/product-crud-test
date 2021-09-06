export default {
    getProducts() {
        return window.axios.get('/api/products')
            .then(response => response.data)
    },

    updateProduct(id, data) {
        return window.axios.put(`/api/products/${id}`, data)
            .then(response => response.data)
    }
}
