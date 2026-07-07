<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Helpers\RoleHelper;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, RoleHelper;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::where('name', 'Elektronik')->first();
    }

    public function test_product_index_page_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Laptop Dell Latitude 5420');
    }

    public function test_admin_can_view_product_create_form(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Barang Baru');
    }

    public function test_admin_can_create_product(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'code' => 'TEST-001',
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'stock' => 10,
            'storage_location' => 'Gudang Uji Coba',
            'condition' => 'Baik',
        ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'code' => 'TEST-001',
            'name' => 'Test Product',
        ]);
    }

    public function test_product_code_must_be_unique(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post(route('products.store'), [
            'code' => 'UNIQUE-001',
            'name' => 'Product Pertama',
            'category_id' => $this->category->id,
            'stock' => 5,
            'storage_location' => 'Lokasi A',
            'condition' => 'Baik',
        ])->assertRedirect(route('products.index'));

        // Duplicate code
        $this->actingAs($admin)->post(route('products.store'), [
            'code' => 'UNIQUE-001',
            'name' => 'Product Kedua',
            'category_id' => $this->category->id,
            'stock' => 5,
            'storage_location' => 'Lokasi A',
            'condition' => 'Baik',
        ])->assertSessionHasErrors('code');
    }

    public function test_validation_fails_for_invalid_condition(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'code' => 'TEST-002',
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'stock' => 5,
            'storage_location' => 'Lokasi',
            'condition' => 'Invalid Condition',
        ]);

        $response->assertSessionHasErrors('condition');
    }

    public function test_admin_can_update_product(): void
    {
        $admin = $this->createAdmin();
        $product = Product::where('code', 'ELK-001')->first();

        $response = $this->actingAs($admin)->put(route('products.update', $product), [
            'code' => 'ELK-001',
            'name' => 'Laptop Dell Latitude 5420 (Updated)',
            'category_id' => $this->category->id,
            'stock' => 20,
            'storage_location' => 'Ruang IT Lantai 4',
            'condition' => 'Baik',
        ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Laptop Dell Latitude 5420 (Updated)',
            'stock' => 20,
            'storage_location' => 'Ruang IT Lantai 4',
        ]);
    }

    public function test_cannot_delete_product_with_active_borrowing(): void
    {
        $admin = $this->createAdmin();

        // Proyektor (ELK-002) has active borrowing (Dipinjam) from seeder
        $product = Product::where('code', 'ELK-002')->first();

        $response = $this->actingAs($admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['code' => 'ELK-002']);
    }

    public function test_can_delete_product_without_borrowing(): void
    {
        $admin = $this->createAdmin();

        // Create a new product with no borrowings
        $product = Product::create([
            'code' => 'DEL-001',
            'name' => 'Product To Delete',
            'category_id' => $this->category->id,
            'stock' => 1,
            'storage_location' => 'Gudang',
            'condition' => 'Baik',
        ]);

        $response = $this->actingAs($admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['code' => 'DEL-001']);
    }

    public function test_product_show_page_displays_details(): void
    {
        $admin = $this->createAdmin();
        $product = Product::where('code', 'ELK-001')->first();

        $response = $this->actingAs($admin)->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->code);
        $response->assertSee($product->category->name);
    }

    public function test_product_search_by_name(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('products.index', ['search' => 'Laptop']));

        $response->assertStatus(200);
        $response->assertSee('Laptop Dell Latitude 5420');
    }

    public function test_product_search_by_code(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('products.index', ['search' => 'ELK-001']));

        $response->assertStatus(200);
        $response->assertSee('ELK-001');
    }

    public function test_product_filter_by_category(): void
    {
        $admin = $this->createAdmin();
        $furnitur = Category::where('name', 'Furnitur')->first();

        $response = $this->actingAs($admin)->get(route('products.index', ['category_id' => $furnitur->id]));

        $response->assertStatus(200);
        $response->assertSee('Kursi Kerja Ergonomis');
        $response->assertDontSee('Laptop Dell');
    }

    public function test_product_filter_by_stock_status_low(): void
    {
        $admin = $this->createAdmin();

        // Make a product low stock
        $product = Product::where('code', 'ELK-002')->first();
        $product->update(['stock' => 3]);

        $response = $this->actingAs($admin)->get(route('products.index', ['stock_status' => 'low']));

        $response->assertStatus(200);
    }

    public function test_product_filter_by_condition(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('products.index', ['condition' => 'Baik']));

        $response->assertStatus(200);
    }

    public function test_product_upload_image(): void
    {
        $admin = $this->createAdmin();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('barang.jpg', 200, 200);

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'code' => 'IMG-001',
            'name' => 'Product With Image',
            'category_id' => $this->category->id,
            'stock' => 5,
            'storage_location' => 'Ruang Server',
            'condition' => 'Baik',
            'image' => $file,
        ]);

        $response->assertRedirect(route('products.index'));

        // Assert the image was stored
        Storage::disk('public')->assertExists('products/' . $file->hashName());

        // Assert the product has the image path
        $this->assertDatabaseHas('products', [
            'code' => 'IMG-001',
            'image_path' => 'products/' . $file->hashName(),
        ]);
    }
}
