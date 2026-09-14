export const createEmptyItem = () => ({
    id: crypto.randomUUID(),
    name: '',
    quantity: '',
    unit: '',
    price_at_time: '',
});

export const calculateItemSubTotal = (item) => {
    return window.parseNumber(item?.quantity) * window.parseNumber(item?.price_at_time);
};

export const calculateTotalQty = (items = []) => {
    return items.reduce((sum, item) => sum + window.parseNumber(item?.quantity), 0);
};

export const calculateGrandTotal = (items = []) => {
    return items.reduce((sum,item) => sum + calculateItemSubTotal(item), 0);
};
