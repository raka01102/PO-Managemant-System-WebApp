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
        const dataset = this.$root.dataset

        this.routes.update = dataset.updateUrl
        this.routes.delete = dataset.deleteUrl
        this.routes.updateStatus = dataset.updateStatusUrl
        this.routes.updatePayment = dataset.updatePaymentUrl
    },

    buildRoute(url, id) {
        if(!url || !id) {
            return
        }

        return url.replace(':id', id)
    },

    async initEdit(id) {
        if(!id) {
            return
        }

        const url = this.buildRoute(this.routes.edit, id)

        if(!url) {
            return
        }

        const response = await fetch(url)

        if(!response.ok) {
            return
        }

        this.po = await response.json()

        this.$dispatch('open-modal', 'po-modal')
    },

    initDelete(id, poNumber) {

        this.po.id = id
        this.po.po_number = poNumber

        this.actionUrl = this.buildRoute(this.routes.delete, id)

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
        return this.buildRoute(this.routes.updateStatus, this.po.id)
    },

    getPaymentAction() {
        return this.buildRoute(this.routes.updatePayment, this.po.id)
    }
})
