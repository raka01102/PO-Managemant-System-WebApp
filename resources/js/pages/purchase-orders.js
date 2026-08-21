window.purchaseOrderManager = () => ({

    actionUrl: '',

    routes: {
        update: '',
        delete: '',
        updateStatus: '',
        updatePayment: '',
    },

    po: {
        id: '',
        po_number: '',
        customer_id: '',
        order_date: '',
        is_paid: 0,
        status: '',
    },

    init() {

        this.routes.update =
            this.$root.dataset.updateUrl

        this.routes.delete =
            this.$root.dataset.deleteUrl

        this.routes.updateStatus =
            this.$root.dataset.updateStatusUrl

        this.routes.updatePayment =
            this.$root.dataset.updatePaymentUrl
    },

    async initEdit(id) {

        const response =
            await fetch(`/purchase-orders/${id}/edit`)

        this.po =
            await response.json()

        this.$dispatch('open-modal', 'po-modal')
    },

    initDelete(id, poNumber) {

        this.po.id = id
        this.po.po_number = poNumber

        this.actionUrl =
            this.routes.delete.replace(':id', id)

        this.$dispatch('open-modal', 'delete-po-modal')
    },

    initProgress(data) {

        this.po = { ...data }

        this.$dispatch(
            'open-modal',
            'progress-type-modal'
        )
    },

    getStatusAction() {

        return this.routes.updateStatus
            .replace(':id', this.po.id)
    },

    getPaymentAction() {

        return this.routes.updatePayment
            .replace(':id', this.po.id)
    }

})
