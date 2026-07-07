<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard PT Telkomsel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 1. Metric Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Barang Card -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 dark:from-red-800 dark:to-red-900 rounded-lg shadow-lg p-6 border-0 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="block text-sm uppercase tracking-wider font-semibold opacity-80">Total Jenis Barang</span>
                        <span class="block text-4xl font-extrabold mt-1">{{ $totalProducts }}</span>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center text-xs mt-4 hover:underline">
                            Lihat Semua Barang
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2 z-0">
                        <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-5 14H4v-4h11v4zm0-5H4V9h11v4zm5 5h-4V9h4v9z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Barang Tersedia Card -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-lg p-6 border border-gray-700/30 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="block text-sm uppercase tracking-wider font-semibold opacity-80">Stok Barang Tersedia</span>
                        <span class="block text-4xl font-extrabold mt-1">{{ $availableCount }} <span class="text-xs font-normal">pcs</span></span>
                        <a href="{{ route('products.index', ['stock_status' => 'empty']) }}" class="inline-flex items-center text-xs mt-4 text-gray-300 hover:underline">
                            Pantau Kapasitas Stok
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2 z-0">
                        <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Barang Dipinjam Card -->
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 dark:from-amber-700 dark:to-amber-800 rounded-lg shadow-lg p-6 border-0 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="block text-sm uppercase tracking-wider font-semibold opacity-80">Barang Sedang Dipinjam</span>
                        <span class="block text-4xl font-extrabold mt-1">{{ $borrowedCount }} <span class="text-xs font-normal">pcs</span></span>
                        <a href="{{ route('borrowings.index', ['status' => 'Dipinjam']) }}" class="inline-flex items-center text-xs mt-4 hover:underline">
                            Lihat Daftar Pinjaman
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2 z-0">
                        <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.5 6a3 3 0 00-6 0v11.75a2.5 2.5 0 01-5 0V6H3.5v11.75a4.5 4.5 0 009 0V6a1 1 0 012 0v11.75h2V6z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 2. Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Grafik Peminjaman -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Grafik Tren Peminjaman per Bulan (' . date('Y') . ')') }}</h3>
                    <div class="relative h-80">
                        <canvas id="borrowingsChart"></canvas>
                    </div>
                </div>

                <!-- Notifikasi Stok Menipis -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            {{ __('Notifikasi Stok') }}
                        </h3>

                        <!-- Out of stock List -->
                        @if(!$outOfStockProducts->isEmpty())
                            <div class="mb-4">
                                <span class="text-xs uppercase font-bold text-red-600 block mb-2">Stok Habis (0)</span>
                                <div class="space-y-2">
                                    @foreach($outOfStockProducts as $prod)
                                        <div class="flex justify-between items-center bg-red-50 dark:bg-red-950/20 p-2.5 rounded border border-red-200 dark:border-red-900/40">
                                            <div class="text-xs">
                                                <a href="{{ route('products.show', $prod) }}" class="font-semibold text-gray-900 dark:text-gray-100 hover:underline">{{ $prod->name }}</a>
                                                <span class="block text-gray-500">{{ $prod->code }}</span>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-xs bg-red-600 text-white font-bold">Habis</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Low stock List -->
                        @if($lowStockProducts->isEmpty() && $outOfStockProducts->isEmpty())
                            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Seluruh stok barang aman.
                            </div>
                        @else
                            @if(!$lowStockProducts->isEmpty())
                                <div class="mb-4">
                                    <span class="text-xs uppercase font-bold text-amber-600 block mb-2">Stok Menipis (<= 5)</span>
                                    <div class="space-y-2">
                                        @foreach($lowStockProducts as $prod)
                                            <div class="flex justify-between items-center bg-amber-50 dark:bg-amber-950/20 p-2.5 rounded border border-amber-200 dark:border-amber-900/40">
                                                <div class="text-xs">
                                                    <a href="{{ route('products.show', $prod) }}" class="font-semibold text-gray-900 dark:text-gray-100 hover:underline">{{ $prod->name }}</a>
                                                    <span class="block text-gray-500">{{ $prod->code }}</span>
                                                </div>
                                                <span class="px-2.5 py-0.5 rounded text-xs bg-amber-500 text-white font-bold">Sisa {{ $prod->stock }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="text-xs text-center text-red-600 dark:text-red-400 hover:underline font-bold block mt-4 border-t dark:border-gray-700 pt-4">
                        Lihat Seluruh Laporan Stok
                    </a>
                </div>
            </div>

            <!-- 3. Recent Activity Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Peminjaman Terkini') }}</h3>
                
                @if($recentBorrowings->isEmpty())
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        Belum ada aktivitas transaksi peminjaman.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Peminjam</th>
                                    <th class="px-6 py-3">Barang</th>
                                    <th class="px-6 py-3">Tanggal Pinjam</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBorrowings as $borrowing)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50/50 dark:hover:bg-gray-600/30">
                                        <td class="px-6 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $borrowing->borrower_name }}</td>
                                        <td class="px-6 py-3">
                                            @foreach($borrowing->products as $product)
                                                {{ $product->name }} <span class="text-xs text-gray-500">({{ $product->pivot->quantity }} pcs)</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-3">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                        <td class="px-6 py-3">
                                            @if($borrowing->status === 'Dipinjam')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                    {{ $borrowing->status }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                    {{ $borrowing->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <a href="{{ route('borrowings.show', $borrowing) }}" class="text-red-600 dark:text-red-400 hover:underline font-semibold text-xs">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart JS Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('borrowingsChart').getContext('2d');
        const months = @json($months);
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Jumlah Transaksi Peminjaman',
                    data: chartData,
                    borderColor: '#ef4444', // Red-500 (Telkomsel red)
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#ef4444'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#9ca3af'
                        },
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af'
                        },
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
