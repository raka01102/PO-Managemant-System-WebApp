<x-app-layout>
    <div class="content">
        <x-content-header :isIndex="false" :isShow="true" title="Detail Customer"
            unDoUrl="{{ route('customers.index') }}" buttonUrl="{{ route('customers.edit', $customer->id) }}"
            typeButtonUrl="Customer" />

        <div class="card">
            <div class="flex flex-col p-2 md:p-4 space-y-2 md:space-y-4">
                {{-- Customer Code --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Kode Customer
                    </p>

                    <p class="font-semibold">
                        {{ $customer->code }}
                    </p>
                </div>

                {{-- Customer Name --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Nama customer
                    </p>

                    <p class="font-semibold">
                        {{ $customer->name }}
                    </p>
                </div>

                {{-- Customer Address --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Alamat Customer
                    </p>

                    @if ($customer->address)
                        <p class="font-semibold capitalize">
                            {{ $customer->address }}
                        </p>
                    @else
                        <p class="text-slate-500">Belum memasukan alamat</p>
                    @endif
                </div>

                {{-- Customer Phone --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Nomor HP
                    </p>

                    @if ($customer->phone)
                        <p class="font-semibold">
                            {{ $customer->phone }}
                        </p>
                    @else
                        <p class="text-slate-500">Belum memasukan nomor telepon</p>
                    @endif
                </div>
            </div>
        </div>

        <x-bottom-action-bar type="show" buttonUrl="{{ route('customers.edit', $customer->id) }}"
            typeButtonUrl="Customer" />
    </div>
</x-app-layout>
