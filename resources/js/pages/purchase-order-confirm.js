import { createEmptyItem, calculateItemSubTotal, calculateGrandTotal, calculateTotalQty } from "../utils/poCalculations"

window.purchaseOrderConfirm = (initialItems = []) => ({

    items: [],

    isSubmitting: false,

    errors: {},

    init() {
        if (!Array.isArray(initialItems) || initialItems.length === 0) {
            this.items = [this.createItem()]
        } else {
            this.items = initialItems.map(item => this.createItem(item))
        }
    },

    createItem(source = null) {
        const base = createEmptyItem()

        return {
            uid: crypto.randomUUID(),
            id: source?.id ?? '',
            name: source?.name ?? '',
            quantity: source?.quantity ?? '',
            unit: source?.unit ?? '',
            price_at_time: source?.price_at_time ?? '',
        }
    },

    addItem() {
        this.items.push(this.createItem())
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

    validate() {
        this.errors = {}

        this.items.forEach((item, index) => {
            if (!item.name || !item.name.trim()) {
                this.errors[`items.${index}.name`] = [
                    'Nama barang wajib diisi'
                ]
            }

            if (!item.unit || !item.unit.trim()) {
                this.errors[`items.${index}.unit`] = [
                    'Satuan wajib diisi'
                ]
            }

            if (
                item.quantity === '' ||
                Number(item.quantity) < 1
            ) {
                this.errors[`items.${index}.quantity`] = [
                    'Jumlah barang minimal 1'
                ]
            }

            if (
                item.price_at_time === '' ||
                Number(item.price_at_time) < 0
            ) {
                this.errors[`items.${index}.price_at_time`] = [
                    'Harga barang tidak valid'
                ]
            }
        })

        return Object.keys(this.errors).length === 0
    },

    submitForm() {
        if (!this.validate()) {
            this.$dispatch(
                'open-modal',
                'validation-modal'
            )

            return
        }

        this.$dispatch(
            'open-modal',
            'confirm-save-modal'
        )
    },

    confirmSubmit() {
        this.isSubmitting = true

        document
            .getElementById('purchaseOrderForm')
            .submit()
    },

    get totalItems() {
        return this.items.length
    },

    get totalQty() {
        return calculateTotalQty(this.items)
    },

    itemSubTotal(item) {
        return calculateItemSubTotal(item)
    },

    get grandTotal() {
        return calculateGrandTotal(this.items)
    },

    formatRupiah(value) {
        return new Intl.NumberFormat(
            'id-ID',
            {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
            }
        ).format(value || 0)
    },

    previewDokumen() {
        this.$dispatch(
            'open-modal',
            'preview-file-modal'
        )
    },
})
