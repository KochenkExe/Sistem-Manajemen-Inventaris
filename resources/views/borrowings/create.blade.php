<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Catat Peminjaman Baru') }}
            </h2>
            <a href="{{ route('borrowings.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <form action="{{ route('borrowings.store') }}" method="POST">
                    @csrf

                    <!-- Nama Peminjam -->
                    <div class="mb-4">
                        <label for="borrower_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Peminjam</label>
                        <input type="text" name="borrower_name" id="borrower_name" value="{{ old('borrower_name') }}" required placeholder="Contoh: Ahmad Subardjo" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                        @error('borrower_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Barang -->
                    <div class="mb-4">
                        <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Barang yang Dipinjam</label>
                        <select name="product_id" id="product_id" required onchange="updateMaxQuantity()" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                            <option value="">Pilih Barang</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->code }}) - Tersedia: {{ $product->stock }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div class="mb-4">
                        <label for="borrow_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Pinjam</label>
                        <input type="date" name="borrow_date" id="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" required 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                        @error('borrow_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah Peminjaman -->
                    <div class="mb-6">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                        <span id="stock-warning" class="text-xs text-gray-500 mt-1 block">Silakan pilih barang untuk melihat kapasitas stok.</span>
                        @error('quantity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700 pt-4">
                        <a href="{{ route('borrowings.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-gray-700">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 dark:hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Catat Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateMaxQuantity() {
            const select = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const warningSpan = document.getElementById('stock-warning');
            
            if (select.selectedIndex > 0) {
                const selectedOption = select.options[select.selectedIndex];
                const stock = selectedOption.getAttribute('data-stock');
                
                quantityInput.max = stock;
                warningSpan.textContent = `Jumlah maksimal yang dapat dipinjam adalah ${stock} pcs.`;
                warningSpan.className = "text-xs text-green-600 mt-1 block font-semibold";
            } else {
                quantityInput.removeAttribute('max');
                warningSpan.textContent = "Silakan pilih barang untuk melihat kapasitas stok.";
                warningSpan.className = "text-xs text-gray-500 mt-1 block";
            }
        }
        
        // Run on page load if old value exists
        window.onload = updateMaxQuantity;
    </script>
</x-app-layout>
