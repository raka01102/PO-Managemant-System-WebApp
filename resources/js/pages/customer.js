window.customerManager = () => ({
    customer: {},
    actionurl: '',

    initDelete(customer) {
        this.customer = customer;
        this.actionurl = `/customers/${customer.id}`;
        this.$dispatch('open-modal', 'delete-customer-modal');
    }
})
