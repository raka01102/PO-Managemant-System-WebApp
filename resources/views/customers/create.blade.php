<x-app-layout>
    <div class="content">
        @if ($errors->any())
            <div class="bg-red-100 p-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-content-header :isIndex="false" title="Buat Customer Baru" unDoUrl="{{ route('customers.index') }}" />
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf

            <div class="card">
                <div class="flex flex-col p-2 md:p-4 space-y-2 md:space-y-4">
                    {{-- Customer Code --}}
                    <div>
                        <x-input-label for="code" :value="__('Kode Customer')" />
                        <x-text-input id="code" class="block w-full mt-1" type="text" name="code"
                            :value="old('code', $newCode)" placeholder="Masukkan kode customer" required />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    {{-- Customer Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Customer')" />
                        <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                            :value="old('name')" placeholder="Masukkan nama customer" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Customer Address --}}
                    <div>
                        <x-input-label for="address" :value="__('Alamat Customer')" />
                        <x-text-input id="address" class="block w-full mt-1" type="text" name="address"
                            :value="old('address')" placeholder="Masukkan alamat customer" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    {{-- Customer Phone --}}
                    <div>
                        <x-input-label for="phone" :value="__('Nomor Telepon')" />
                        <x-text-input id="phone" class="block w-full mt-1" type="text" inputmode="numeric"
                            name="phone" :value="old('phone')" placeholder="Masukkan nomor telepon"
                            oninput="this.value = this.value.replace(/\D/g, '')" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>
            </div>

            <x-bottom-action-bar :showOnDesktop="true" type="create" buttonUrl="{{ route('customers.index') }}" />
        </form>
    </div>
</x-app-layout>
