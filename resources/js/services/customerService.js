export async function searchCustomers(search, signal) {
    const response = await fetch(
        `/customers/search?search=${encodeURIComponent(search)}`, { signal }
    );

    if (!response.ok) {
        throw new Error('Gagal mengambil data customer');
    }

    return response.json();
}
