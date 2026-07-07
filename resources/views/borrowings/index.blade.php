<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Peminjaman Barang') }}
            </h2>
            @can('isStaff')
                <a href="{{ route('borrowings.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 dark:hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Catat Peminjaman') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alerts -->
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

            <!-- Search and Filter Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border border-gray-100 dark:border-gray-700">
                <form action="{{ route('borrowings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="sr-only">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari peminjam atau nama barang..." 
                                class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="status" onchange="this.form.submit()" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>

                    <!-- Reset/Submit Buttons -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('borrowings.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center">
                            Reset Filter
                        </a>
                        <button type="submit" class="px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white rounded-md text-xs hover:bg-slate-700">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- Borrowings List Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                @if($borrowings->isEmpty())
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        Tidak ada catatan peminjaman yang ditemukan.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nama Peminjam</th>
                                    <th class="px-6 py-3">Barang yang Dipinjam</th>
                                    <th class="px-6 py-3">Tanggal Pinjam</th>
                                    <th class="px-6 py-3">Tanggal Kembali</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowings as $borrowing)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600/50">
                                        <!-- Borrower Name -->
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $borrowing->borrower_name }}
                                        </td>
                                        <!-- Borrowed Products -->
                                        <td class="px-6 py-4">
                                            @foreach($borrowing->products as $product)
                                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                                    {{ $product->name }} <span class="text-xs text-gray-500">({{ $product->pivot->quantity }} pcs)</span>
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $product->code }}</div>
                                            @endforeach
                                        </td>
                                        <!-- Borrow Date -->
                                        <td class="px-6 py-4">
                                            {{ $borrowing->borrow_date->format('d M Y') }}
                                        </td>
                                        <!-- Return Date -->
                                        <td class="px-6 py-4">
                                            @if($borrowing->return_date)
                                                {{ $borrowing->return_date->format('d M Y') }}
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 italic">Belum dikembalikan</span>
                                            @endif
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="px-6 py-4">
                                            @if($borrowing->status === 'Dipinjam')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                    {{ $borrowing->status }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                    {{ $borrowing->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <!-- Actions -->
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-3 items-center">
                                                <a href="{{ route('borrowings.show', $borrowing) }}" class="text-slate-600 dark:text-slate-400 hover:underline text-xs font-semibold">
                                                    Detail
                                                </a>
                                                @can('isStaff')
                                                    @if($borrowing->status === 'Dipinjam')
                                                        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin barang ini sudah dikembalikan?')">
                                                            @csrf
                                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold text-xs py-1 px-3 rounded shadow">
                                                                Kembalikan
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peminjaman ini? Jika barang belum dikembalikan, stok barang akan dikembalikan secara otomatis.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs font-semibold">
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
                        {{ $borrowings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
