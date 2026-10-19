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

import { validationItems } from '../features/purchase-order/validation';

import { formatRupiah } from '../utils/currency';

window.purchaseOrderConfirm = (initialItems = []) => ({
    items: [],
    isSubmitting: false,
    errors: {},

    init() {
        this.items = createItems(initialItems);
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

    validate() {
        this.errors = validationItems(this.items);

        return Object.keys(this.errors).length === 0;
    },

    submitForm() {
        if (!this.validate()) {
            this.$dispatch(
                'open-modal',
                'validation-modal'
            );

            return;
        }

        this.$dispatch(
            'open-modal',
            'confirm-save-modal'
        );
    },

    confirmSubmit() {
        this.isSubmitting = true;

        document
            .getElementById('purchaseOrderForm')
            .submit();
    },

    get totalItems() {
        return this.items.length;
    },

    get totalQty() {
        return calculateTotalQty(this.items);
    },

    itemSubTotal(item) {
        return calculateItemSubTotal(item);
    },

    get grandTotal() {
        return calculateGrandTotal(this.items);
    },

    formatRupiah(value) {
        return formatRupiah(value);
    },

    previewDokumen() {
        this.$dispatch(
            'open-modal',
            'preview-file-modal'
        );
    },
});
