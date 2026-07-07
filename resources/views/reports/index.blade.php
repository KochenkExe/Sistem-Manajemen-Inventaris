<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Inventaris & Transaksi') }}
            </h2>
            <div class="flex gap-2">
                <!-- Export Excel -->
                <a href="{{ route('reports.excel') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-xs font-semibold uppercase tracking-wider shadow transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Export Excel') }}
                </a>
                <!-- Export PDF -->
                <a href="{{ route('reports.pdf') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-xs font-semibold uppercase tracking-wider shadow transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Export PDF') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'inventory' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs Menu -->
            <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
                <button @click="activeTab = 'inventory'" :class="{ 'border-red-500 text-red-600 dark:text-red-400': activeTab === 'inventory', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'inventory' }" 
                    class="py-4 px-6 font-medium text-sm border-b-2 transition duration-150">
                    Daftar Aset Inventaris
                </button>
                <button @click="activeTab = 'borrowings'" :class="{ 'border-red-500 text-red-600 dark:text-red-400': activeTab === 'borrowings', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'borrowings' }" 
                    class="py-4 px-6 font-medium text-sm border-b-2 transition duration-150">
                    Aktivitas Peminjaman
                </button>
            </div>

            <!-- Tab 1: Inventory List -->
            <div x-show="activeTab === 'inventory'" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Laporan Stok & Kondisi Barang</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Kode Barang</th>
                                <th class="px-6 py-3">Nama Barang</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Stok Tersedia</th>
                                <th class="px-6 py-3">Lokasi</th>
                                <th class="px-6 py-3">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50/50">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ $product->code }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                                    <td class="px-6 py-4">{{ $product->category->name }}</td>
                                    <td class="px-6 py-4 font-bold">
                                        @if($product->stock == 0)
                                            <span class="text-red-500 font-bold">Habis</span>
                                        @elseif($product->stock <= 5)
                                            <span class="text-amber-500 font-bold">{{ $product->stock }} (Minis)</span>
                                        @else
                                            <span class="text-gray-900 dark:text-gray-100">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $product->storage_location }}</td>
                                    <td class="px-6 py-4">
                                        @if($product->condition == 'Baik')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Baik</span>
                                        @elseif($product->condition == 'Rusak Ringan')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Rusak Ringan</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Rusak Berat</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Borrowings List -->
            <div x-show="activeTab === 'borrowings'" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Laporan Log Transaksi Peminjaman</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Peminjam</th>
                                <th class="px-6 py-3">Barang yang Dipinjam</th>
                                <th class="px-6 py-3">Tanggal Pinjam</th>
                                <th class="px-6 py-3">Tanggal Kembali</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $borrowing)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50/50">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ $borrowing->borrower_name }}</td>
                                    <td class="px-6 py-4">
                                        @foreach($borrowing->products as $p)
                                            <div class="text-gray-900 dark:text-gray-100">{{ $p->name }} <span class="text-xs text-gray-500">({{ $p->pivot->quantity }} pcs)</span></div>
                                            <div class="text-xs text-gray-500">{{ $p->code }}</div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($borrowing->status === 'Dipinjam')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">{{ $borrowing->status }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ $borrowing->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
