<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\RoleHelper;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase, RoleHelper;

    public function test_report_index_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Inventaris & Transaksi');
    }

    public function test_report_index_shows_inventory_table(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('ELK-001');
        $response->assertSee('Laptop Dell Latitude 5420');
        $response->assertSee('Daftar Aset Inventaris');
    }

    public function test_report_index_shows_borrowing_log(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Aktivitas Peminjaman');
        $response->assertSee('Laporan Log Transaksi Peminjaman');
    }

    public function test_report_pdf_can_be_downloaded(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('reports.pdf'));

        $response->assertStatus(200);
        // PDF response should be a download
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_report_excel_can_be_downloaded(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('reports.excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_report_excel_contains_correct_headers(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('reports.excel'));

        $response->assertStatus(200);
        // Streamed response - check content disposition
        $response->assertHeader('Content-Disposition', 'attachment; filename=Laporan-Inventaris-Telkomsel-' . date('Y-m-d') . '.csv');
    }

    public function test_report_excel_contains_product_data(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('reports.excel'));

        $response->assertStatus(200);
        // CSV stream should contain product data (streamed response checks)
        $content = $response->streamedContent();
        $this->assertStringContainsString('Kode Barang', $content);
        $this->assertStringContainsString('ELK-001', $content);
        $this->assertStringContainsString('Laptop Dell Latitude 5420', $content);
    }

    public function test_manager_can_access_reports_page(): void
    {
        $manager = $this->createManager();
        $response = $this->actingAs($manager)->get(route('reports.index'));

        $response->assertStatus(200);
    }
}
