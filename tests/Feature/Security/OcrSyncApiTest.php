<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** SEC-07: endpoint rekap OCR. */
class OcrSyncApiTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/api/v1/sync/daily-recap';

    public function test_ditolak_bila_secret_server_kosong(): void
    {
        config(['services.ocr.secret' => null]);

        // Header kosong vs secret kosong tidak boleh dianggap cocok.
        $this->postJson(self::URL, [], ['X-API-KEY' => ''])->assertUnauthorized();
    }

    public function test_ditolak_bila_kunci_salah(): void
    {
        config(['services.ocr.secret' => 'rahasia-ocr']);

        $this->postJson(self::URL, [], ['X-API-KEY' => 'salah'])->assertUnauthorized();
    }

    public function test_validasi_menolak_status_dan_relasi_tidak_valid(): void
    {
        config(['services.ocr.secret' => 'rahasia-ocr']);

        $this->postJson(self::URL, [
            'trainer_id' => 999,
            'gymkos_id' => 999,
            'customer_name' => 'Rekap',
            'payment_method' => 'cash',
            'status' => 'lunas-kok',
            'total_amount' => 1000,
            'created_at' => 'bukan-tanggal',
            'items' => [['product_id' => 'x', 'product_name' => 'x', 'quantity' => 1, 'price' => 1, 'subtotal' => 1]],
            'expense' => ['amount' => 5000],
        ], ['X-API-KEY' => 'rahasia-ocr'])
            ->assertStatus(422)
            ->assertJsonPath('status', 'validation_error')
            ->assertJsonStructure(['errors' => [
                'trainer_id', 'gymkos_id', 'status', 'created_at', 'items.0.product_id', 'expense.description',
            ]]);
    }
}
