<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RoleHelper;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase, RoleHelper;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        // Use product with sufficient stock
        $this->product = Product::where('code', 'ATK-001')->first();
    }

    public function test_borrowing_index_page_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('borrowings.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_borrowing_create_form(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('borrowings.create'));

        $response->assertStatus(200);
        $response->assertSee('Catat Peminjaman Baru');
    }

    public function test_admin_can_create_borrowing(): void
    {
        $admin = $this->createAdmin();
        $initialStock = $this->product->stock;

        $response = $this->actingAs($admin)->post(route('borrowings.store'), [
            'borrower_name' => 'Test Peminjam',
            'product_id' => $this->product->id,
            'borrow_date' => '2026-07-04',
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('success');

        // Check borrowing was created
        $this->assertDatabaseHas('borrowings', [
            'borrower_name' => 'Test Peminjam',
            'status' => 'Dipinjam',
        ]);

        // Check stock was decremented
        $this->assertEquals($initialStock - 2, $this->product->fresh()->stock);
    }

    public function test_cannot_borrow_more_than_available_stock(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('borrowings.store'), [
            'borrower_name' => 'Test Peminjam',
            'product_id' => $this->product->id,
            'borrow_date' => '2026-07-04',
            'quantity' => 99999, // Exceeds stock
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_borrower_name_is_required(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('borrowings.store'), [
            'borrower_name' => '',
            'product_id' => $this->product->id,
            'borrow_date' => '2026-07-04',
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors('borrower_name');
    }

    public function test_admin_can_return_borrowed_item(): void
    {
        $admin = $this->createAdmin();

        // First, create a borrowing
        $this->actingAs($admin)->post(route('borrowings.store'), [
            'borrower_name' => 'Peminjam Kembali',
            'product_id' => $this->product->id,
            'borrow_date' => '2026-07-04',
            'quantity' => 1,
        ]);

        $borrowing = Borrowing::where('borrower_name', 'Peminjam Kembali')->first();
        $stockBeforeReturn = $this->product->fresh()->stock;

        // Return the item
        $response = $this->actingAs($admin)->post(route('borrowings.return', $borrowing));

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('success');

        // Check status updated
        $this->assertEquals('Dikembalikan', $borrowing->fresh()->status);
        $this->assertNotNull($borrowing->fresh()->return_date);

        // Check stock was incremented back
        $this->assertEquals($stockBeforeReturn + 1, $this->product->fresh()->stock);
    }

    public function test_cannot_return_already_returned_item(): void
    {
        $admin = $this->createAdmin();

        // Find a borrowing that's already returned
        $borrowing = Borrowing::where('status', 'Dikembalikan')->first();

        $response = $this->actingAs($admin)->post(route('borrowings.return', $borrowing));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_admin_can_delete_borrowing(): void
    {
        $admin = $this->createAdmin();

        // Create a new borrowing
        $this->actingAs($admin)->post(route('borrowings.store'), [
            'borrower_name' => 'Pinjam Hapus',
            'product_id' => $this->product->id,
            'borrow_date' => '2026-07-04',
            'quantity' => 1,
        ]);

        $borrowing = Borrowing::where('borrower_name', 'Pinjam Hapus')->first();
        $stockBeforeDelete = $this->product->fresh()->stock;

        // Delete the borrowing (still active = Dipinjam)
        $response = $this->actingAs($admin)->delete(route('borrowings.destroy', $borrowing));

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('success');

        // Check borrowing was deleted
        $this->assertDatabaseMissing('borrowings', ['id' => $borrowing->id]);

        // Check stock was restored
        $this->assertEquals($stockBeforeDelete + 1, $this->product->fresh()->stock);
    }

    public function test_borrowing_index_can_filter_by_status(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('borrowings.index', ['status' => 'Dipinjam']));

        $response->assertStatus(200);
        // Should show active borrowings
        $response->assertSee('Lani Marlina');
    }

    public function test_borrowing_index_can_search_by_borrower(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('borrowings.index', ['search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Utomo');
    }

    public function test_borrowing_show_page_displays_details(): void
    {
        $admin = $this->createAdmin();
        $borrowing = Borrowing::where('status', 'Dipinjam')->first();

        $response = $this->actingAs($admin)->get(route('borrowings.show', $borrowing));

        $response->assertStatus(200);
        $response->assertSee($borrowing->borrower_name);
    }
}
