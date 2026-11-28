<?php

namespace Tests\Feature;

use App\Livewire\SelfCheckin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Tests\TestCase;

/** Absen mandiri via QR (Livewire) — jaring pengaman upgrade Livewire 3.8 (Fase 6). */
class SelfCheckinTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'abcdefghijklmnopqrstuvwx';

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('gymkos')->insert([
            ['id' => 1, 'name' => 'B11N Gym', 'absen_token' => self::TOKEN, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'K1NG Gym', 'absen_token' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('members')->insert([
            'gymkos_id' => 1, 'member_code' => 'B-00007', 'name' => 'Budi', 'join_date' => '2026-01-01',
            'membership_end_date' => now()->addMonth()->toDateString(), 'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_halaman_absen_terbuka_dan_token_salah_404(): void
    {
        $this->get('/absen/'.self::TOKEN)->assertOk()->assertSeeLivewire(SelfCheckin::class);
        $this->get('/absen/zzzzzzzzzzzzzzzzzzzzzzzz')->assertNotFound();
    }

    public function test_member_absen_mandiri_tercatat_pending_dan_tidak_bisa_dua_kali(): void
    {
        Livewire::test(SelfCheckin::class, ['token' => self::TOKEN])
            ->call('chooseMember')
            ->set('nickname', 'Budi')
            ->set('memberCode', 'b-00007') // huruf kecil tetap dikenali
            ->call('submitMember')
            ->assertSet('resultType', 'success');

        $this->assertDatabaseHas('attendances', ['gymkos_id' => 1, 'method' => 'self_scan', 'status' => 'pending', 'nickname' => 'Budi']);

        Livewire::test(SelfCheckin::class, ['token' => self::TOKEN])
            ->call('chooseMember')
            ->set('nickname', 'Budi')
            ->set('memberCode', 'B-00007')
            ->call('submitMember')
            ->assertSet('resultType', 'warning');

        $this->assertDatabaseCount('attendances', 1);
    }

    public function test_cabang_terkunci_tidak_bisa_diubah_dari_browser(): void
    {
        $this->expectException(CannotUpdateLockedPropertyException::class);

        Livewire::test(SelfCheckin::class, ['token' => self::TOKEN])->set('gymId', 2); // #[Locked]
    }
}
