<x-app-layout>
    @if (session('success'))
        <x-alert type="success" title="Berhasil" :message="session('success')" />
    @elseif(session('error'))
        <x-alert type="error" title="Gagal" :message="session('error')" />
    @elseif(session('warning'))
        <x-alert type="warning" title="Perhatian" message="Data belum lengkap." />
    @endif
    <div x-data="{
        step: 1,
        isLoading: false,
        processUpload() {
            this.isLoading = true;
            this.step = 2;
            // Simulasi pengiriman form ke backend via AJAX/Livewire
            this.$refs.uploadForm.submit();
        }
    }" class="py-12">

        <div class="max-w-4xl mx-auto px-4">

            <!-- STEP 1: UPLOAD ONLY -->
            <div x-show="step === 1" x-transition>
                <div
                    class="bg-white dark:bg-slate-800 p-10 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-xl text-center">
                    <h2 class="text-2xl font-black text-[#003399] dark:text-white mb-2">Input Purchase Order</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">Silakan unggah dokumen PO (JPG/PDF) untuk diproses
                        otomatis.</p>

                    <form id="uploadForm" x-ref="uploadForm" action="{{ route('purchase-orders.scan') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div
                            class="border-2 border-dashed border-blue-200 dark:border-slate-700 rounded-3xl p-12 hover:bg-blue-50 dark:hover:bg-slate-900/50 transition cursor-pointer relative">
                            <input type="file" name="document" class="absolute inset-0 opacity-0 cursor-pointer"
                                @change="processUpload()">
                            <i class="fa-solid fa-file-pdf text-5xl text-[#003399] dark:text-blue-400 mb-4"></i>
                            <p class="font-bold text-gray-700 dark:text-slate-300">Klik atau seret file ke sini</p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- STEP 2: ANIMASI LOADING -->
            <div x-show="step === 2" class="text-center py-20" x-transition>
                <div class="relative inline-block">
                    <div class="w-24 h-24 border-4 border-blue-100 border-t-[#003399] rounded-full animate-spin"></div>
                    <i
                        class="fa-solid fa-bolt absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[#003399] dark:text-blue-400 text-2xl animate-pulse"></i>
                </div>
                <h3 class="mt-8 text-xl font-black text-[#003399] dark:text-white">Sedang Membaca Dokumen...</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">AI sedang mengekstrak data dari Purchase Order Anda.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
