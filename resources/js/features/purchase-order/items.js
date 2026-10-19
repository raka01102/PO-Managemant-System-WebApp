const createEmptyItem = () => ({
    uid: crypto.randomUUID(),
    id: '',
    name: '',
    quantity: '',
    unit: '',
    price_at_time: '',
});

export function createItem(source = {}) {
    return {
        ...createEmptyItem(),
        id: source?.id ?? '',
        name: source?.name ?? '',
        quantity: source?.quantity ?? '',
        unit: source?.unit ?? '',
        price_at_time: source?.price_at_time ?? '',
    };
}

export function createItems(items = []) {
    if (!Array.isArray(items) || items.length === 0) {
        return [createItem()];
    }

    return items.map((item) => createItem(item));
}

export function addItem(items) {
    items.push(createItem());

    return items;
}

export function removeItem(items, index) {
    if (items.length <= 1) {
        return false;
    }

    items.splice(index, 1);

    return true;
}
