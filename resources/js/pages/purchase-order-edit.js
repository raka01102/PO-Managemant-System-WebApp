import { createEmptyItem, calculateItemSubTotal, calculateTotalQty, calculateGrandTotal } from "../utils/poCalculations"

function createNewItem() {
    const base = createEmptyItem()

    return {
        uid: base.id,
        id: '',
        name: base.name,
        quantity: base.quantity,
        unit: base.unit,
        price_at_time: base.price_at_time,
    }
}

window.purchaseOrderEditManager = (initialCustomerName = '', initialItems = [], initialErrors = {}) => ({
    customer_name: initialCustomerName,
    items: [],
    suggestions: [],

    errors: {},

    init() {
        if (!Array.isArray(initialItems) || initialItems.length === 0) {
            this.items = [createNewItem()]
        } else {
            this.items = initialItems.map(item => ({
                uid: crypto.randomUUID(),
                id: item.id ?? '',
                name: item.name ?? '',
                quantity: item.quantity ?? '',
                unit: item.unit ?? '',
                price_at_time: item.price_at_time ?? '',
            }))
        }

        this.errors = (initialErrors && typeof initialErrors === 'object')
            ? initialErrors
            : {}
    },

    addItem() {
        this.items.push(createNewItem())
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

    itemSubTotal(item) {
        return calculateItemSubTotal(item)
    },

    get totalItems() {
        return this.items.length
    },

    get totalQty() {
        return calculateTotalQty(this.items)
    },

    get grandTotal() {
        return calculateGrandTotal(this.items)
    },
})
