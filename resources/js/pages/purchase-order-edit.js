window.purchaseOrderEditManager = () => ({
    customer_name: '',
    items: [],
    suggestions: [],

    async searchCustomers() {
        if (this.customer_name.length < 2) {
            this.suggestions = []
            return
        }
        const response = await fetch(
            `/customers/search?search=${this.customer_name}`
        )
        this.suggestions = await response.json()
    },

    selectCustomer(customer) {
        this.customer_name = customer.name
        this.suggestions = []
    },

    addItem() {
        this.items.push({
            name: '',
            qty: '',
            unit: '',
            price_at_time: '',
            subtotal: '',
        })
    },

    removeItem(index) {
        if (this.items.length <= 1) {
            this.$dispatch(
                'open-modal',
                'error-table-modal'
            )
            return
        }
        this.items.splice(index, 1)
    },

    get grandtotal() {
        return this.items.reduce(
            (sum, item) => {
                const qty =
                    Number(item.qty) || 0
                const price =
                    Number(item.price_at_time) || 0
                return sum + (qty * price)
            },
            0
        )
    }
})
