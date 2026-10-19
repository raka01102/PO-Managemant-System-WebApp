window.customerManager = () => ({
    customer: {},
    actionUrl: '',

    initDelete(customer) {
        this.customer = customer;
        this.actionUrl = `/customers/${customer.id}`;
        this.$dispatch('open-modal', 'delete-customer-modal');
    }
})
