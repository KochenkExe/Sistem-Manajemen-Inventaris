<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display a listing of reports and charts.
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('code', 'asc')->get();
        $borrowings = Borrowing::with('products')->orderBy('created_at', 'desc')->get();
        
        return view('reports.index', compact('products', 'borrowings'));
    }

    /**
     * Export the inventory list to PDF.
     */
    public function exportPdf()
    {
        $products = Product::with('category')->orderBy('code', 'asc')->get();
        $date = date('d M Y');

        $pdf = Pdf::loadView('reports.pdf_template', compact('products', 'date'));
        return $pdf->download("Laporan-Inventaris-Telkomsel-{$date}.pdf");
    }

    /**
     * Export the inventory list to CSV (Excel compatible).
     */
    public function exportExcel()
    {
        $products = Product::with('category')->orderBy('code', 'asc')->get();
        $fileName = 'Laporan-Inventaris-Telkomsel-' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Kode Barang', 'Nama Barang', 'Kategori', 'Stok Tersedia', 'Lokasi Penyimpanan', 'Kondisi'];

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->code,
                    $product->name,
                    $product->category->name,
                    $product->stock,
                    $product->storage_location,
                    $product->condition
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
