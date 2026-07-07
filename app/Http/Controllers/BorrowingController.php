<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with('products');

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Borrower Name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('borrower_name', 'like', "%{$search}%")
                  ->orWhereHas('products', function($pQuery) use ($search) {
                      $pQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
                  });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get products with stock > 0
        $products = Product::where('stock', '>', 0)->get();
        return view('borrowings.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrower_name' => ['required', 'string', 'max:255'],
            'product_id' => ['required', 'exists:products,id'],
            'borrow_date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if stock is sufficient
        if ($product->stock < $request->quantity) {
            return back()->withErrors(['quantity' => "Stok tidak mencukupi! Stok saat ini untuk {$product->name} adalah {$product->stock}."])->withInput();
        }

        // Perform transaction
        DB::transaction(function () use ($request, $product) {
            // Create Borrowing record
            $borrowing = Borrowing::create([
                'borrower_name' => $request->borrower_name,
                'borrow_date' => $request->borrow_date,
                'status' => 'Dipinjam',
            ]);

            // Create BorrowingDetail record
            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);

            // Decrement product stock
            $product->decrement('stock', $request->quantity);
        });

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil dicatat!');
    }

    /**
     * Return the borrowed item.
     */
    public function returnItem(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Dipinjam') {
            return back()->with('error', 'Barang sudah dikembalikan!');
        }

        // Perform transaction
        DB::transaction(function () use ($borrowing) {
            // Mark borrowing as returned
            $borrowing->update([
                'status' => 'Dikembalikan',
                'return_date' => Carbon::now()->toDateString(),
            ]);

            // Increment back the stock of each borrowed product
            foreach ($borrowing->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }
        });

        return redirect()->route('borrowings.index')
            ->with('success', 'Barang berhasil dikembalikan, stok otomatis ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['details.product']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        // If still borrowed, we must restore stock before deleting
        DB::transaction(function () use ($borrowing) {
            if ($borrowing->status === 'Dipinjam') {
                foreach ($borrowing->details as $detail) {
                    $detail->product->increment('stock', $detail->quantity);
                }
            }
            $borrowing->delete();
        });

        return redirect()->route('borrowings.index')
            ->with('success', 'Data peminjaman berhasil dihapus!');
    }
}
