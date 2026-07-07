<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RoleHelper;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, RoleHelper;

    public function test_category_index_displays_categories(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Elektronik');
        $response->assertSee('Furnitur');
    }

    public function test_admin_can_create_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Perlengkapan Jaringan',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['name' => 'Perlengkapan Jaringan']);
    }

    public function test_category_name_must_be_unique(): void
    {
        $admin = $this->createAdmin();

        // First creation succeeds
        $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Kategori Unik',
        ])->assertRedirect(route('categories.index'));

        // Duplicate fails
        $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Kategori Unik',
        ])->assertSessionHasErrors('name');
    }

    public function test_category_name_is_required(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_category(): void
    {
        $admin = $this->createAdmin();
        $category = Category::where('name', 'Elektronik')->first();

        $response = $this->actingAs($admin)->put(route('categories.update', $category), [
            'name' => 'Elektronik & Gadget',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['name' => 'Elektronik & Gadget']);
        $this->assertDatabaseMissing('categories', ['name' => 'Elektronik']);
    }

    public function test_cannot_delete_category_with_products(): void
    {
        $admin = $this->createAdmin();
        $category = Category::where('name', 'Elektronik')->first();

        // Elektronik category has products (Laptop, Proyektor)
        $this->assertGreaterThan(0, $category->products()->count());

        $response = $this->actingAs($admin)->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['name' => 'Elektronik']);
    }

    public function test_can_delete_empty_category(): void
    {
        $admin = $this->createAdmin();

        // Create a category with no products
        $category = Category::factory()->create(['name' => 'Kategori Kosong']);

        $response = $this->actingAs($admin)->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['name' => 'Kategori Kosong']);
    }

    public function test_category_index_has_pagination(): void
    {
        $admin = $this->createAdmin();

        // Add more categories to trigger pagination
        Category::factory()->count(15)->create();

        $response = $this->actingAs($admin)->get(route('categories.index'));
        $response->assertStatus(200);
        // Verify pagination is rendered (nav element with pagination navigation aria-label)
        $response->assertSee('Pagination Navigation', false);
    }
}
