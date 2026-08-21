window.purchaseOrderConfirm = (
    initialItems = []
) => ({

    items: initialItems,
    isSubmitting: false,
    showConfirmationModal: false,
    customer_name: '',
    po_number: '',
    po_date: '',
    suggestions: [],
    errors: {},

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

    validate() {
            this.errors = {};

            this.items.forEach((item, index) => {

                if (!item.product_name.trim()) {
                    this.errors[`items.${index}.product_name`] = ['Nama barang wajib diisi'];
                }

                if (!item.unit.trim()) {
                    this.errors[`items.${index}.unit`] = ['Satuan wajib diisi'];
                }

                if (!item.price_at_time) {
                    this.errors[`items.${index}.price_at_time`] = ['Harga wajib diisi'];
                }

                if (!item.quantity) {
                    this.errors[`items.${index}.quantity`] = ['Jumlah wajib diisi'];
                }

            });

            return Object.keys(this.errors).length === 0;
        },

    submitForm() {

            if (!this.validate()) {
                this.$dispatch('open-modal', 'validation-modal');
                return;
            }

            document.getElementById('purchaseOrderForm').submit();

        },

    selectCustomer(customer) {
        this.customer_name = customer.name
        this.suggestions = []
    },

    previewDokumen(filePath) {
        this.$dispatch(
            'open-modal',
            'preview-file-modal'
        )
    },

    addItem() {
        this.items.push({
            product_name: '',
            quantity: '',
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

    get totalItems() {
        return this.items.length
    },

    get totalQty() {
        return this.items.reduce(
            (sum, item) =>
                sum + (
                    Number(item.quantity) || 0
                ),
            0
        )
    },

    get subTotal() {
        return this.items.reduce(
            (sum, item) =>
                sum + (
                    (Number(item.quantity) || 0) *
                    (Number(item.price_at_time) || 0)
                ),
            0
        )
    },

    get grandTotal() {
        return this.items.reduce(
            (sum, item) => {
                const qty =
                    Number(item.quantity) || 0
                const price =
                    Number(item.price_at_time) || 0
                return sum + (qty * price)
            },
            0
        )
    },

    formatRupiah(value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value ?? 0);
        },

    validateItems() {
        return this.po_number?.trim() &&
            this.po_date &&
            this.customer_name?.trim() &&
            this.items.length > 0 &&
            this.items.every(item =>
            item.name?.trim() &&
            Number(item.quantity) > 0 &&
            item.unit?.trim() &&
            Number(item.price_at_time) >= 0
        )
    },

    submitForm() {
        if (!this.validateItems()) {
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
    }
})
