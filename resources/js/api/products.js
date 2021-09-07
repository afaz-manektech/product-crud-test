export default {
    getProducts() {
        return window.axios.get('/api/products')
            .then(response => response.data)
    },

    updateProduct(id, data) {
        data.append('_method', 'PUT');

        return window.axios.post(`/api/products/${id}`, data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => response.data)
    },

    createProduct(data) {
        return window.axios.post('/api/products', data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => response.data)
    }
}
