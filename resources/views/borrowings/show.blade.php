<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Peminjaman') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('borrowings.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6 border-b pb-2 dark:border-gray-700">Transaksi Peminjaman</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6">
                    <!-- Borrower Details -->
                    <div>
                        <span class="block text-xs text-gray-500 uppercase font-semibold mb-1">Nama Peminjam</span>
                        <span class="text-gray-900 dark:text-gray-100 font-bold text-lg block">{{ $borrowing->borrower_name }}</span>
                    </div>

                    <!-- Status -->
                    <div>
                        <span class="block text-xs text-gray-500 uppercase font-semibold mb-1">Status Peminjaman</span>
                        @if($borrowing->status === 'Dipinjam')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                {{ $borrowing->status }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                {{ $borrowing->status }}
                            </span>
                        @endif
                    </div>

                    <!-- Date of Borrow -->
                    <div>
                        <span class="block text-xs text-gray-500 uppercase font-semibold mb-1">Tanggal Pinjam</span>
                        <span class="text-gray-900 dark:text-gray-100 font-semibold">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                    </div>

                    <!-- Date of Return -->
                    <div>
                        <span class="block text-xs text-gray-500 uppercase font-semibold mb-1">Tanggal Kembali</span>
                        @if($borrowing->return_date)
                            <span class="text-gray-900 dark:text-gray-100 font-semibold">{{ $borrowing->return_date->format('d M Y') }}</span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 italic">Belum dikembalikan</span>
                        @endif
                    </div>
                </div>

                <!-- Products in this Transaction Table -->
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">Barang yang Dipinjam</h4>
                    
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2">Gambar</th>
                                <th class="px-4 py-2">Kode</th>
                                <th class="px-4 py-2">Nama Barang</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Lokasi Penyimpanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowing->details as $detail)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50/50">
                                    <!-- Image -->
                                    <td class="px-4 py-3">
                                        @if($detail->product->image_path)
                                            <img src="{{ asset('storage/' . $detail->product->image_path) }}" alt="{{ $detail->product->name }}" class="w-12 h-12 object-cover rounded border dark:border-gray-700">
                                        @else
                                            <div class="w-12 h-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-400 rounded border dark:border-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <!-- Code -->
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $detail->product->code }}</td>
                                    <!-- Name -->
                                    <td class="px-4 py-3 font-medium text-red-600 dark:text-red-400 hover:underline">
                                        <a href="{{ route('products.show', $detail->product) }}">{{ $detail->product->name }}</a>
                                    </td>
                                    <!-- Quantity -->
                                    <td class="px-4 py-3 font-bold">{{ $detail->quantity }}</td>
                                    <!-- Storage -->
                                    <td class="px-4 py-3">{{ $detail->product->storage_location }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Action Buttons -->
                @can('isStaff')
                    @if($borrowing->status === 'Dipinjam')
                        <div class="flex justify-end gap-3 mt-8 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin barang ini sudah dikembalikan?')">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 dark:hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Kembalikan Barang
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
