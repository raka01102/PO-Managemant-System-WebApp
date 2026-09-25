@props(['nameModal', 'typeModal'])

<x-modal name="{{ $nameModal }}" maxWidth="md">
    <div class="flex flex-col p-4 gap-2 md:p-6 md:gap-4">
        <div class="flex gap-2 md:gap-4 items-center">
            <div
                class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400">
                <i class="fa-solid fa-trash-can text-2xl"></i>
            </div>

            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                Hapus Data {{ $typeModal }}
            </h2>
        </div>

        <p>Data yang telah dihapus tidak bisa dikembalikan. Apakah anda ingin melakukan tindakan ini?</p>

        <form :action="actionUrl" method="POST" class="flex items-center justify-end gap-2 md:gap-4">
            @csrf
            @method('DELETE')
            <x-secondary-button @click="$dispatch('close-modal', '{{ $nameModal }}')" class="btn">
                Simpan Data
            </x-secondary-button>

            <x-danger-button class="btn-left-icon">
                <i class="fa-solid fa-trash-can"></i>
                Hapus Data
            </x-danger-button>
        </form>
    </div>
</x-modal>
