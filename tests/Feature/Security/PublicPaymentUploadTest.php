<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** SEC-04, SEC-14, INT-07, INT-09: unggah bukti bayar membership publik. */
class PublicPaymentUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'gym_id' => 1,
            'name' => 'Pengunjung',
            'email' => 'pengunjung@example.test',
            'phone' => '081200000000',
            'image' => UploadedFile::fake()->image('bukti.jpg'),
            'membership_type' => 'Member Bulanan',
            'payment' => 'transfer',
        ], $override);
    }

    public function test_tipe_paket_di_luar_daftar_ditolak(): void
    {
        $this->postJson('/payment/upload', $this->payload(['membership_type' => 'Paket Gratis Selamanya']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('membership_type');
    }

    public function test_gym_id_selain_cabang_gym_ditolak(): void
    {
        $this->postJson('/payment/upload', $this->payload(['gym_id' => 3]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('gym_id');
    }

    public function test_member_id_tanpa_login_ditolak(): void
    {
        $gymId = DB::table('gymkos')->insertGetId(['name' => 'B11N Gym', 'created_at' => now(), 'updated_at' => now()]);
        $memberId = DB::table('members')->insertGetId([
            'gymkos_id' => $gymId, 'name' => 'Member Lain', 'join_date' => now()->toDateString(),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->postJson('/payment/upload', $this->payload(['member_id' => $memberId]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('member_id');

        $this->assertDatabaseCount('payment_membership', 0);
    }

    public function test_pembayaran_dari_halaman_b11n_tercatat_ke_cabang_1(): void
    {
        $now = now();
        DB::table('gymkos')->insert([
            ['id' => 1, 'name' => 'B11N Gym', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'K1NG Gym', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('stores')->insert(['id' => 3, 'title' => 'Toko Gym', 'description' => '-', 'created_at' => $now, 'updated_at' => $now]);

        $this->postJson('/payment/upload', $this->payload())
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('payment_membership', ['membership_type' => 'Member Bulanan', 'price' => 90000, 'member_id' => null]);
        $this->assertDatabaseHas('transactions', ['gymkos_id' => 1, 'total_amount' => 90000, 'status' => 'pending']);
    }

    public function test_dibatasi_lima_kali_per_menit_per_ip(): void
    {
        // Payload sengaja tidak valid agar tidak menulis DB; throttle tetap menghitung.
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/payment/upload', ['gym_id' => 9])->assertStatus(422);
        }

        $this->postJson('/payment/upload', ['gym_id' => 9])
            ->assertStatus(429)
            ->assertJsonPath('message', 'Terlalu banyak percobaan. Silakan coba lagi beberapa menit lagi.');
    }
}
