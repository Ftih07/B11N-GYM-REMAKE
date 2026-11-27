<?php

namespace Tests\Feature\Money;

use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\TransactionResource\Pages\EditTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

/** INT-04 (kode transaksi), INT-02 untuk form admin, transaksi lama dari fitur online yang dihapus. */
class TransactionIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $now = now();
        DB::table('gymkos')->insert([
            ['id' => 1, 'name' => 'B11N Gym', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'K1NG Gym', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Kost', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('stores')->insert(['id' => 3, 'title' => 'Toko Gym', 'description' => '-', 'created_at' => $now, 'updated_at' => $now]);
    }

    private function product(int $price = 5000): string
    {
        $id = (string) Str::uuid();
        DB::table('products')->insert([
            'id' => $id, 'name' => 'Air', 'description' => '-', 'price' => $price,
            'status' => 'ready', 'stores_id' => 3, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return $id;
    }

    public function test_kode_transaksi_unik_dan_tidak_ditimpa(): void
    {
        $a = Transaction::create(['gymkos_id' => 1, 'payment_method' => 'cash', 'status' => 'pending']);
        $b = Transaction::create(['gymkos_id' => 1, 'payment_method' => 'cash', 'status' => 'pending']);
        $c = Transaction::create(['code' => 'TRX-MANUAL-1', 'gymkos_id' => 1, 'payment_method' => 'cash', 'status' => 'pending']);

        $this->assertMatchesRegularExpression('/^TRX-\d{6}-[A-Z0-9]{8}$/', $a->code);
        $this->assertNotSame($a->code, $b->code); // dibuat di detik yang sama — dulu bentrok
        $this->assertSame('TRX-MANUAL-1', $c->code);
    }

    public function test_total_transaksi_admin_dihitung_ulang_dari_item(): void
    {
        $productId = $this->product();
        // Angka dari browser salah/dimanipulasi: total 1.000, subtotal 1
        $trx = Transaction::create(['gymkos_id' => 1, 'payment_method' => 'cash', 'status' => 'paid', 'total_amount' => 1000, 'paid_amount' => 20000]);
        $trx->items()->create(['product_id' => $productId, 'product_name' => 'Air', 'quantity' => 3, 'price' => 5000, 'subtotal' => 1]);

        TransactionResource::recalculateTotals($trx);

        $trx->refresh();
        $this->assertEquals(15000, $trx->total_amount);
        $this->assertEquals(5000, $trx->change_amount);
        $this->assertEquals(15000, $trx->items()->first()->subtotal);
        $this->assertDatabaseHas('finances', ['transaction_id' => $trx->id, 'amount' => 15000]);
    }

    public function test_transaksi_lama_dari_fitur_online_bisa_diedit_dan_dihapus(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $productId = $this->product(750000);

        // Transaksi lama hasil booking kost online: tanpa kasir, cabang kost, payable ke class yang sudah dihapus
        $trx = Transaction::create([
            'code' => 'TRX-1773065018', 'gymkos_id' => 3, 'payment_method' => 'qris', 'status' => 'pending',
            'total_amount' => 750000, 'payable_type' => 'App\Models\Booking', 'payable_id' => 38,
        ]);
        $trx->items()->create(['product_id' => $productId, 'product_name' => 'Sewa Kost', 'quantity' => 1, 'price' => 750000, 'subtotal' => 750000]);

        // Dulu: kasir wajib & cabang kost (3) tidak ada di pilihan → tidak bisa disimpan
        Livewire::test(EditTransaction::class, ['record' => $trx->getRouteKey()])
            ->assertFormSet(['gymkos_id' => 3, 'trainer_id' => null])
            ->fillForm(['status' => 'paid'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('paid', $trx->fresh()->status);
        $this->assertDatabaseHas('finances', ['transaction_id' => $trx->id, 'amount' => 750000, 'gymkos_id' => 3]);

        // Menghapus tidak lagi mencoba memuat model Booking yang sudah tidak ada
        $trx->fresh()->delete();
        $this->assertDatabaseMissing('transactions', ['id' => $trx->id]);
        $this->assertDatabaseCount('finances', 0);
        $this->assertDatabaseCount('transaction_items', 0);
    }

    public function test_daftar_transaksi_admin_tetap_terbuka_dengan_transaksi_lama(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        Transaction::create([
            'gymkos_id' => 1, 'payment_method' => 'transfer', 'status' => 'pending', 'total_amount' => 90000,
            'payable_type' => 'App\Models\Payment', 'payable_id' => 1,
        ]);

        $this->get(TransactionResource::getUrl('index'))->assertOk()->assertSee('Member Online');
    }
}
