<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $managerRole = Role::create(['name' => 'manager']);

        // 2. Seed Users
        User::create([
            'name' => 'Admin Telkomsel',
            'email' => 'admin@telkomsel.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Staff Inventaris',
            'email' => 'staff@telkomsel.com',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
        ]);

        User::create([
            'name' => 'Manager Laporan',
            'email' => 'manager@telkomsel.com',
            'password' => Hash::make('password'),
            'role_id' => $managerRole->id,
        ]);

        // 3. Seed Categories
        $elektronik = Category::create(['name' => 'Elektronik']);
        $atk = Category::create(['name' => 'Alat Tulis Kantor']);
        $furnitur = Category::create(['name' => 'Furnitur']);
        $perlengkapan = Category::create(['name' => 'Perlengkapan Rapat']);

        // 4. Seed Products
        $laptop = Product::create([
            'code' => 'ELK-001',
            'name' => 'Laptop Dell Latitude 5420',
            'category_id' => $elektronik->id,
            'stock' => 15,
            'storage_location' => 'Ruang IT Lantai 3',
            'condition' => 'Baik',
        ]);

        $proyektor = Product::create([
            'code' => 'ELK-002',
            'name' => 'Proyektor Epson EB-X400',
            'category_id' => $elektronik->id,
            'stock' => 5,
            'storage_location' => 'Lemari Alat Rapat Lantai 2',
            'condition' => 'Baik',
        ]);

        $spidol = Product::create([
            'code' => 'ATK-001',
            'name' => 'Spidol Papan Tulis Snowman Black',
            'category_id' => $atk->id,
            'stock' => 100,
            'storage_location' => 'Gudang ATK Lantai 1',
            'condition' => 'Baik',
        ]);

        $kursi = Product::create([
            'code' => 'FUR-001',
            'name' => 'Kursi Kerja Ergonomis Slate Red',
            'category_id' => $furnitur->id,
            'stock' => 20,
            'storage_location' => 'Ruang Kerja Lantai 2',
            'condition' => 'Baik',
        ]);

        $meja = Product::create([
            'code' => 'FUR-002',
            'name' => 'Meja Rapat Oval Jati',
            'category_id' => $furnitur->id,
            'stock' => 2,
            'storage_location' => 'Ruang Rapat Utama Lantai 4',
            'condition' => 'Baik',
        ]);

        // 5. Seed Borrowings and Details
        // March Borrowing
        $b1 = Borrowing::create([
            'borrower_name' => 'Budi Utomo',
            'borrow_date' => Carbon::create(2026, 3, 5),
            'return_date' => Carbon::create(2026, 3, 10),
            'status' => 'Dikembalikan',
        ]);
        BorrowingDetail::create([
            'borrowing_id' => $b1->id,
            'product_id' => $laptop->id,
            'quantity' => 1,
        ]);

        // April Borrowing
        $b2 = Borrowing::create([
            'borrower_name' => 'Siti Aminah',
            'borrow_date' => Carbon::create(2026, 4, 12),
            'return_date' => Carbon::create(2026, 4, 18),
            'status' => 'Dikembalikan',
        ]);
        BorrowingDetail::create([
            'borrowing_id' => $b2->id,
            'product_id' => $proyektor->id,
            'quantity' => 1,
        ]);

        // May Borrowings
        $b3 = Borrowing::create([
            'borrower_name' => 'Agus Setiawan',
            'borrow_date' => Carbon::create(2026, 5, 1),
            'return_date' => Carbon::create(2026, 5, 5),
            'status' => 'Dikembalikan',
        ]);
        BorrowingDetail::create([
            'borrowing_id' => $b3->id,
            'product_id' => $laptop->id,
            'quantity' => 2,
        ]);

        $b4 = Borrowing::create([
            'borrower_name' => 'Dewi Lestari',
            'borrow_date' => Carbon::create(2026, 5, 20),
            'return_date' => Carbon::create(2026, 5, 25),
            'status' => 'Dikembalikan',
        ]);
        BorrowingDetail::create([
            'borrowing_id' => $b4->id,
            'product_id' => $spidol->id,
            'quantity' => 10,
        ]);

        // June Borrowings
        $b5 = Borrowing::create([
            'borrower_name' => 'Hendra Wijaya',
            'borrow_date' => Carbon::create(2026, 6, 8),
            'return_date' => Carbon::create(2026, 6, 15),
            'status' => 'Dikembalikan',
        ]);
        BorrowingDetail::create([
            'borrowing_id' => $b5->id,
            'product_id' => $kursi->id,
            'quantity' => 4,
        ]);

        $b6 = Borrowing::create([
            'borrower_name' => 'Lani Marlina',
            'borrow_date' => Carbon::create(2026, 6, 25),
            'return_date' => null,
            'status' => 'Dipinjam',
        ]);
        BorrowingDetail::create([
            'borrowing_id' => $b6->id,
            'product_id' => $laptop->id,
            'quantity' => 1,
        ]);
        // Update product stock since it is currently borrowed
        $laptop->decrement('stock', 1);

        // July Borrowings
        $b7 = Borrowing::create([
            'borrower_name' => 'Rian Hidayat',
            'borrow_date' => Carbon::create(2026, 7, 1),
            'return_date' => null,
            'status' => 'Dipinjam',
        ]);
        BorrowingDetail::create([
            'borrowing_id' => $b7->id,
            'product_id' => $proyektor->id,
            'quantity' => 1,
        ]);
        $proyektor->decrement('stock', 1);
    }
}
