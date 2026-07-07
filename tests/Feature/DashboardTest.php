<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RoleHelper;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, RoleHelper;

    public function test_dashboard_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard PT Telkomsel');
    }

    public function test_dashboard_displays_total_products(): void
    {
        $admin = $this->createAdmin();
        $totalProducts = Product::count();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee((string) $totalProducts);
    }

    public function test_dashboard_displays_available_stock(): void
    {
        $admin = $this->createAdmin();
        $availableCount = Product::sum('stock');

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee((string) $availableCount);
    }

    public function test_dashboard_shows_low_stock_alert(): void
    {
        $admin = $this->createAdmin();

        // Set a product to low stock
        $product = Product::where('code', 'ELK-002')->first();
        $product->update(['stock' => 2]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Stok Menipis');
        $response->assertSee($product->name);
    }

    public function test_dashboard_shows_out_of_stock_alert(): void
    {
        $admin = $this->createAdmin();

        // Set a product to out of stock
        $product = Product::where('code', 'ELK-002')->first();
        $product->update(['stock' => 0]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Stok Habis');
        $response->assertSee($product->name);
    }

    public function test_dashboard_shows_chart(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('borrowingsChart');
        $response->assertSee('Grafik Tren Peminjaman');
    }

    public function test_dashboard_shows_recent_borrowings(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Peminjaman Terkini');
    }

    public function test_dashboard_accessible_by_all_roles(): void
    {
        $admin = $this->createAdmin();
        $staff = $this->createStaff();
        $manager = $this->createManager();

        $this->actingAs($admin)->get(route('dashboard'))->assertStatus(200);
        $this->actingAs($staff)->get(route('dashboard'))->assertStatus(200);
        $this->actingAs($manager)->get(route('dashboard'))->assertStatus(200);
    }
}
