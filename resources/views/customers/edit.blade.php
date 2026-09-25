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

        <x-content-header :isIndex="false" title="Edit Customer"
            unDoUrl="{{ route('customers.show', $customer->id) }}" />

        <form method="POST" action="{{ route('customers.update', $customer->id) }}">
            @csrf
            @method('PUT')

            <div class="card m-4">
                <div class="flex flex-col p-2 md:p-4 space-y-2 md:space-y-4">
                    {{-- Customer Code --}}
                    <div>
                        <x-input-label for="code" :value="__('Kode Customer')" />
                        <x-text-input id="code" class="block w-full mt-1" type="text" name="code"
                            :value="old('code', $customer->code)" placeholder="Masukkan kode customer" required autocomplete="code" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    {{-- Customer Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Customer')" />
                        <x-text-input id="name " class="block w-full mt-1" type="text" name="name"
                            :value="old('name', $customer->name)" placeholder="Masukkan nama customer" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Customer Address --}}
                    <div>
                        <x-input-label for="address" :value="__('Alamat Customer')" />
                        <x-text-input id="address" class="block w-full mt-1 capitalize" type="text" name="address"
                            :value="old('address', $customer->address)" placeholder="Masukkan alamat customer" autocomplete="address" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    {{-- Customer Phone --}}
                    <div>
                        <x-input-label for="phone" :value="__('Nomor Telepon')" />
                        <x-text-input id="phone" class="block w-full mt-1" type="text" inputmode="numeric"
                            name="phone" :value="old('phone', $customer->phone)" placeholder="Masukkan nomor telepon" autocomplete="phone"
                            oninput="this.value = this.value.replace(/\D/g, '')" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>
            </div>

            <x-bottom-action-bar :showOnDesktop="true" type="edit"
                buttonUrl="{{ route('customers.show', $customer) }}" />
        </form>
    </div>
</x-app-layout>
