<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RoleHelper;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase, RoleHelper;

    // ─── Category Management Access ───

    public function test_admin_can_access_category_index(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('categories.index'));
        $response->assertStatus(200);
    }

    public function test_staff_can_access_category_index(): void
    {
        $staff = $this->createStaff();
        $response = $this->actingAs($staff)->get(route('categories.index'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_access_category_index(): void
    {
        $manager = $this->createManager();
        $response = $this->actingAs($manager)->get(route('categories.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_store_category(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Test Kategori',
        ]);
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Test Kategori']);
    }

    public function test_manager_cannot_store_category(): void
    {
        $manager = $this->createManager();
        $response = $this->actingAs($manager)->post(route('categories.store'), [
            'name' => 'Test Kategori',
        ]);
        $response->assertStatus(403);
    }

    // ─── Product Management Access ───

    public function test_admin_can_access_product_create(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('products.create'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_access_product_create(): void
    {
        $manager = $this->createManager();
        $response = $this->actingAs($manager)->get(route('products.create'));
        $response->assertStatus(403);
    }

    public function test_all_roles_can_view_product_index(): void
    {
        $admin = $this->createAdmin();
        $staff = $this->createStaff();
        $manager = $this->createManager();

        $this->actingAs($admin)->get(route('products.index'))->assertStatus(200);
        $this->actingAs($staff)->get(route('products.index'))->assertStatus(200);
        $this->actingAs($manager)->get(route('products.index'))->assertStatus(200);
    }

    // ─── Borrowing Management Access ───

    public function test_admin_can_access_borrowing_create(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('borrowings.create'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_access_borrowing_create(): void
    {
        $manager = $this->createManager();
        $response = $this->actingAs($manager)->get(route('borrowings.create'));
        $response->assertStatus(403);
    }

    public function test_all_roles_can_view_borrowing_index(): void
    {
        $admin = $this->createAdmin();
        $staff = $this->createStaff();
        $manager = $this->createManager();

        $this->actingAs($admin)->get(route('borrowings.index'))->assertStatus(200);
        $this->actingAs($staff)->get(route('borrowings.index'))->assertStatus(200);
        $this->actingAs($manager)->get(route('borrowings.index'))->assertStatus(200);
    }

    // ─── Report Access ───

    public function test_admin_can_access_reports(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin)->get(route('reports.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('reports.pdf'))->assertStatus(200);
        $this->actingAs($admin)->get(route('reports.excel'))->assertStatus(200);
    }

    public function test_manager_can_access_reports(): void
    {
        $manager = $this->createManager();
        $this->actingAs($manager)->get(route('reports.index'))->assertStatus(200);
        $this->actingAs($manager)->get(route('reports.pdf'))->assertStatus(200);
        $this->actingAs($manager)->get(route('reports.excel'))->assertStatus(200);
    }

    public function test_staff_cannot_access_reports(): void
    {
        $staff = $this->createStaff();
        $this->actingAs($staff)->get(route('reports.index'))->assertStatus(403);
        $this->actingAs($staff)->get(route('reports.pdf'))->assertStatus(403);
        $this->actingAs($staff)->get(route('reports.excel'))->assertStatus(403);
    }

    // ─── Guest Access ───

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('products.index'))->assertRedirect(route('login'));
        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->get(route('borrowings.index'))->assertRedirect(route('login'));
        $this->get(route('reports.index'))->assertRedirect(route('login'));
    }
}
