const rupiahFormat = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
})

export function formatRupiah(value) {
    return rupiahFormat.format(value || 0)
}
