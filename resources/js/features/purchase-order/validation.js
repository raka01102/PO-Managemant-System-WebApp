export function validationItems(items = []) {
    const errors = {}

    items.forEach((item, index) => {
        if (!item.name || !item.name.trim()) {
            errors[`items.${index}.name`] = [
                'Nama barang wajib diisi'
            ]
        }

        if (!item.unit || !item.unit.trim()) {
            errors[`items.${index}.unit`] = [
                'Satuan wajib diisi'
            ]
        }

        if (
            item.quantity === '' ||
            Number(item.quantity) < 1
        ) {
            errors[`items.${index}.quantity`] = [
                'Jumlah barang minimal 1'
            ]
        }

        if (
            item.price_at_time === '' ||
            Number(item.price_at_time) < 0
        ) {
            errors[`items.${index}.price_at_time`] = [
                'Harga barang minimal 0'
            ]
        }
    })

    return errors
}
