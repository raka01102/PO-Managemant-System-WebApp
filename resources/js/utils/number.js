export function parseNumber(value) {
    const number = Number.parseFloat(value);

    return Number.isFinite(number) ? number : 0;
}
