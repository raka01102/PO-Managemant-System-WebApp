import {
    createItems,
    addItem,
    removeItem,
} from '../features/purchase-order/items';

import {
    calculateItemSubTotal,
    calculateTotalQty,
    calculateGrandTotal,
} from '../features/purchase-order/calculations';

import { formatRupiah } from '../utils/currency';

window.purchaseOrderEditManager = (
    initialCustomerName = '',
    initialItems = [],
    initialErrors = {}
) => ({
    customer_name: initialCustomerName,
    items: [],
    suggestions: [],
    errors: {},

    init() {
        this.items = createItems(initialItems);

        this.errors =
            initialErrors &&
            typeof initialErrors === 'object'
                ? initialErrors
                : {};
    },

    addItem() {
        addItem(this.items);
    },

    removeItem(index) {
        const removed = removeItem(this.items, index);

        if (!removed) {
            this.$dispatch(
                'open-modal',
                'error-table-modal'
            );
        }
    },

    itemSubTotal(item) {
        return calculateItemSubTotal(item);
    },

    get totalItems() {
        return this.items.length;
    },

    get totalQty() {
        return calculateTotalQty(this.items);
    },

    get grandTotal() {
        return calculateGrandTotal(this.items);
    },

    formatRupiah(value) {
        return formatRupiah(value);
    },
});
