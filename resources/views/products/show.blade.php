<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Barang') }}: {{ $product->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-gray-700">
                    Kembali
                </a>
                @can('isStaff')
                    <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-semibold">
                        Edit Barang
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Product Information Details Card -->
                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">Informasi Barang</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <!-- Kode Barang -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded">
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Kode Barang</span>
                            <span class="text-gray-900 dark:text-gray-100 font-bold text-base">{{ $product->code }}</span>
                        </div>

                        <!-- Nama Barang -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded">
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Nama Barang</span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $product->name }}</span>
                        </div>

                        <!-- Kategori -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded">
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Kategori</span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $product->category->name }}</span>
                        </div>

                        <!-- Lokasi Penyimpanan -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded">
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Lokasi Penyimpanan</span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $product->storage_location }}</span>
                        </div>

                        <!-- Stok -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded">
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Stok Saat Ini</span>
                            @if($product->stock == 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                    Habis
                                </span>
                            @elseif($product->stock <= 5)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400">
                                    {{ $product->stock }} (Minis)
                                </span>
                            @else
                                <span class="text-gray-900 dark:text-gray-100 font-bold text-base">{{ $product->stock }}</span>
                            @endif
                        </div>

                        <!-- Kondisi -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded">
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Kondisi Barang</span>
                            @if($product->condition == 'Baik')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    {{ $product->condition }}
                                </span>
                            @elseif($product->condition == 'Rusak Ringan')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                    {{ $product->condition }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                    {{ $product->condition }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Historical Borrowings Table -->
                    <div class="mt-8">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">Riwayat Peminjaman Barang</h4>
                        
                        @if($product->borrowingDetails->isEmpty())
                            <p class="text-sm text-gray-500 dark:text-gray-400 py-4">Belum ada riwayat peminjaman untuk barang ini.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-4 py-2">Nama Peminjam</th>
                                            <th class="px-4 py-2">Tanggal Pinjam</th>
                                            <th class="px-4 py-2">Tanggal Kembali</th>
                                            <th class="px-4 py-2">Jumlah</th>
                                            <th class="px-4 py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->borrowingDetails as $detail)
                                            <tr class="border-b dark:border-gray-700">
                                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $detail->borrowing->borrower_name }}</td>
                                                <td class="px-4 py-3">{{ $detail->borrowing->borrow_date->format('d M Y') }}</td>
                                                <td class="px-4 py-3">
                                                    {{ $detail->borrowing->return_date ? $detail->borrowing->return_date->format('d M Y') : '-' }}
                                                </td>
                                                <td class="px-4 py-3 font-medium">{{ $detail->quantity }}</td>
                                                <td class="px-4 py-3">
                                                    @if($detail->borrowing->status == 'Dipinjam')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                            {{ $detail->borrowing->status }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                            {{ $detail->borrowing->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Image Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700 h-fit">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">Gambar Barang</h3>
                    
                    <div class="flex justify-center p-2 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded max-h-64">
                        @else
                            <div class="w-full h-48 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm">Tidak ada gambar</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
