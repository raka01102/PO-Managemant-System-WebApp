import { parseNumber } from '../../utils/number';

export function calculateItemSubTotal(item) {
    return (
        parseNumber(item?.quantity) *
        parseNumber(item?.price_at_time)
    );
}

export function calculateTotalQty(items = []) {
    return items.reduce(
        (sum, item) => sum + parseNumber(item?.quantity),
        0
    );
}

export function calculateGrandTotal(items = []) {
    return items.reduce(
        (sum, item) => sum + calculateItemSubTotal(item),
        0
    );
}
