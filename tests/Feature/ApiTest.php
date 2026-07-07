<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_products_returns_json(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id',
                    'code',
                    'name',
                    'category_id',
                    'stock',
                    'storage_location',
                    'condition',
                    'category',
                ],
            ],
        ]);
    }

    public function test_api_products_status_is_success(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_api_products_returns_multiple_items(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_api_product_detail_returns_correct_item(): void
    {
        $response = $this->getJson('/api/products/1');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => 1,
            ],
        ]);
    }

    public function test_api_product_detail_includes_category(): void
    {
        $response = $this->getJson('/api/products/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'category' => [
                    'id',
                    'name',
                ],
            ],
        ]);
    }

    public function test_api_product_detail_returns_404_for_nonexistent(): void
    {
        $response = $this->getJson('/api/products/999');

        $response->assertStatus(404);
    }

    public function test_api_borrowings_returns_json(): void
    {
        $response = $this->getJson('/api/borrowings');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id',
                    'borrower_name',
                    'borrow_date',
                    'status',
                ],
            ],
        ]);
    }

    public function test_api_borrowings_status_is_success(): void
    {
        $response = $this->getJson('/api/borrowings');

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_api_borrowings_includes_products(): void
    {
        $response = $this->getJson('/api/borrowings');

        $response->assertStatus(200);
        // At least one borrowing should have products relation
        $firstBorrowing = $response->json('data.0');
        $this->assertArrayHasKey('products', $firstBorrowing);
    }

    public function test_api_borrowings_ordered_by_latest(): void
    {
        $response = $this->getJson('/api/borrowings');

        $response->assertStatus(200);
        $data = $response->json('data');

        // Items should be ordered by created_at desc
        if (count($data) >= 2) {
            $this->assertGreaterThanOrEqual(
                strtotime($data[1]['created_at']),
                strtotime($data[0]['created_at'])
            );
        }
    }
}
