<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with statistics and charts.
     */
    public function index()
    {
        // 1. Core Metrics
        $totalProducts = Product::count();
        
        $borrowedCount = BorrowingDetail::whereHas('borrowing', function($query) {
            $query->where('status', 'Dipinjam');
        })->sum('quantity');

        $availableCount = Product::sum('stock');

        // 2. Stock Alerts
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $outOfStockProducts = Product::where('stock', 0)
            ->with('category')
            ->take(5)
            ->get();

        // 3. Chart Data (Borrowings per Month for current year)
        $currentYear = Carbon::now()->year;
        $borrowings = Borrowing::whereYear('borrow_date', $currentYear)->get();
        
        $grouped = $borrowings->groupBy(function($item) {
            return Carbon::parse($item->borrow_date)->format('n'); // Month number 1-12
        });

        $chartData = [];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        foreach (range(1, 12) as $monthNum) {
            $chartData[] = isset($grouped[$monthNum]) ? $grouped[$monthNum]->count() : 0;
        }

        // 4. Recent Activities
        $recentBorrowings = Borrowing::with('products')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'borrowedCount',
            'availableCount',
            'lowStockProducts',
            'outOfStockProducts',
            'months',
            'chartData',
            'recentBorrowings'
        ));
    }
}
