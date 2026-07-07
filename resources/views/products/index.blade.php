<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Master Data Barang') }}
            </h2>
            @can('isStaff')
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 dark:hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Tambah Barang') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                    <span class="font-medium">Sukses!</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif

            <!-- Search and Filter Bar Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border border-gray-100 dark:border-gray-700">
                <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label for="search" class="sr-only">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Kode, Nama, Lokasi..." 
                                class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select name="category_id" onchange="this.form.submit()" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Condition Filter -->
                    <div>
                        <select name="condition" onchange="this.form.submit()" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ request('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ request('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    <!-- Stock Status Filter -->
                    <div>
                        <select name="stock_status" onchange="this.form.submit()" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                            <option value="">Semua Status Stok</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis (<= 5)</option>
                            <option value="empty" {{ request('stock_status') == 'empty' ? 'selected' : '' }}>Stok Habis (0)</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-gray-700">
                            Reset Filter
                        </a>
                        <button type="submit" class="px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white rounded-md text-xs hover:bg-slate-700">
                            Terapkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Products List Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                @if($products->isEmpty())
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        Tidak ada barang yang ditemukan.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Gambar</th>
                                    <th scope="col" class="px-6 py-3">Kode</th>
                                    <th scope="col" class="px-6 py-3">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3">Kategori</th>
                                    <th scope="col" class="px-6 py-3">Stok</th>
                                    <th scope="col" class="px-6 py-3">Lokasi</th>
                                    <th scope="col" class="px-6 py-3">Kondisi</th>
                                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600/50">
                                        <!-- Image Cell -->
                                        <td class="px-6 py-4">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded border dark:border-gray-700">
                                            @else
                                                <div class="w-12 h-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-400 rounded border dark:border-gray-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <!-- Code Cell -->
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $product->code }}
                                        </td>
                                        <!-- Name Cell -->
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('products.show', $product) }}" class="hover:underline text-red-600 dark:text-red-400">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <!-- Category Cell -->
                                        <td class="px-6 py-4">
                                            {{ $product->category->name }}
                                        </td>
                                        <!-- Stock Cell -->
                                        <td class="px-6 py-4">
                                            @if($product->stock == 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                                    Habis
                                                </span>
                                            @elseif($product->stock <= 5)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400" title="Stok menipis!">
                                                    {{ $product->stock }} (Minis)
                                                </span>
                                            @else
                                                <span class="text-gray-900 dark:text-gray-100 font-semibold">{{ $product->stock }}</span>
                                            @endif
                                        </td>
                                        <!-- Storage Cell -->
                                        <td class="px-6 py-4">
                                            {{ $product->storage_location }}
                                        </td>
                                        <!-- Condition Cell -->
                                        <td class="px-6 py-4">
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
                                        </td>
                                         <!-- Actions Cell -->
                                         <td class="px-6 py-4 text-right">
                                             <div class="flex justify-end items-center gap-3">
                                                 <a href="{{ route('products.show', $product) }}" class="text-slate-600 dark:text-slate-400 hover:underline text-xs font-semibold">
                                                     Detail
                                                 </a>
                                                 @can('isStaff')
                                                     <a href="{{ route('products.edit', $product) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs font-semibold">
                                                         Edit
                                                     </a>
                                                     <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')" class="inline-flex">
                                                         @csrf
                                                         @method('DELETE')
                                                         <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs font-semibold p-0 border-0 bg-transparent cursor-pointer align-baseline">
                                                             Hapus
                                                         </button>
                                                     </form>
                                                 @endcan
                                             </div>
                                         </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
