window.productManager = () => ({
    product: {},
    actionUrl: '',

    initDelete(product) {
        this.product = product;
        this.actionUrl = `/products/${product.id}`;
        this.$dispatch('open-modal', 'delete-product-modal')
    }
})
