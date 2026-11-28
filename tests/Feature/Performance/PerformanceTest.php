<?php

namespace Tests\Feature\Performance;

use App\Filament\Resources\FinanceResource\Pages\ListFinances;
use App\Filament\Widgets\BiinIncomeChart;
use App\Models\Member;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\MembershipStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

/** Fase 4: PERF-01, PERF-02, PERF-04, PERF-05, known-issues #27. */
class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $now = now();
        DB::table('gymkos')->insert([
            ['id' => 1, 'name' => 'B11N Gym', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'K1NG Gym', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    private function member(?string $end, string $status = 'active'): Member
    {
        $id = DB::table('members')->insertGetId([
            'gymkos_id' => 1, 'name' => 'Budi', 'join_date' => '2026-01-01', 'member_code' => 'B-'.random_int(10000, 99999),
            'membership_end_date' => $end, 'status' => $status, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return Member::findOrFail($id);
    }

    // --- PERF-01: TrackVisitor ---

    public function test_pengunjung_dicatat_sekali_per_hari_dan_tidak_untuk_admin_api_polling(): void
    {
        $this->get('/legal')->assertOk();
        $this->get('/legal')->assertOk(); // sesi yang sama → tanpa query tambahan
        $this->assertDatabaseCount('web_visitors', 1);

        // Sesi baru dari IP yang sama (mis. cookie dihapus) → tetap satu baris berkat index unik
        $this->flushSession();
        $this->get('/legal')->assertOk();
        $this->assertDatabaseCount('web_visitors', 1);

        $this->flushSession();
        $this->get('/admin/login');
        $this->getJson('/api/check-membership-status');
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.9'])->post('/survey', []);
        $this->assertDatabaseCount('web_visitors', 1);
    }

    public function test_user_agent_panjang_tidak_membuat_error(): void
    {
        $this->withHeader('User-Agent', str_repeat('Mozilla/5.0 FBAN/FBIOS ', 40))
            ->get('/legal')
            ->assertOk();

        $this->assertSame(250, mb_strlen(DB::table('web_visitors')->value('user_agent')));
    }

    // --- PERF-05: status member ---

    public function test_membaca_member_tidak_lagi_menulis_ke_database(): void
    {
        $member = $this->member(now()->subDays(3)->toDateString());

        Member::query()->get(); // dulu: event retrieved langsung UPDATE status
        $this->assertSame('active', $member->fresh()->status);
    }

    public function test_member_kedaluwarsa_diubah_massal_dan_dibatasi_sekali_per_jam(): void
    {
        $expired = $this->member(now()->subDay()->toDateString());
        $endsToday = $this->member(today()->toDateString());
        $active = $this->member(now()->addDays(10)->toDateString());
        $noEnd = $this->member(null);

        $service = app(MembershipStatusService::class);
        $this->assertSame(1, $service->expireOverdueThrottled());

        $this->assertSame('inactive', $expired->fresh()->status);
        $this->assertSame('active', $endsToday->fresh()->status); // masih berlaku sampai akhir hari ini
        $this->assertSame('active', $active->fresh()->status);
        $this->assertSame('active', $noEnd->fresh()->status);

        // Dalam satu jam berikutnya tidak dijalankan lagi
        $this->member(now()->subDays(2)->toDateString());
        $this->assertNull($service->expireOverdueThrottled());
    }

    public function test_panel_admin_memicu_pembaruan_status_dan_ada_perintah_cron(): void
    {
        $expired = $this->member(now()->subDay()->toDateString());

        $this->actingAs(User::factory()->create(['role' => 'admin']))->get('/admin')->assertOk();
        $this->assertSame('inactive', $expired->fresh()->status);

        $another = $this->member(now()->subDays(5)->toDateString());
        $this->assertSame(0, Artisan::call('member:expire'));
        $this->assertSame('inactive', $another->fresh()->status);
    }

    // --- PERF-04: absen 1x/hari tetap benar dengan query rentang ---

    public function test_absen_sekali_per_hari_tetap_berlaku(): void
    {
        $member = $this->member(now()->addMonth()->toDateString());
        DB::table('attendances')->insert([
            'gymkos_id' => 1, 'member_id' => $member->id, 'check_in_time' => now()->subDay()->setTime(20, 0),
            'method' => 'manual', 'status' => 'verified', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $service = app(AttendanceService::class);

        $this->assertSame('success', $service->checkInMember($member, 1, 'manual')['status']); // kemarin tidak menghalangi
        $this->assertSame('warning', $service->checkInMember($member, 2, 'manual')['status']); // kedua kali hari ini
    }

    public function test_filter_bulan_tahun_keuangan_memakai_rentang_yang_benar(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach (['2026-01-31', '2026-02-01', '2026-02-28', '2026-03-01', '2025-02-15'] as $i => $date) {
            DB::table('finances')->insert([
                'gymkos_id' => 1, 'type' => 'income', 'amount' => 1000 * ($i + 1), 'description' => "F{$i}",
                'date' => $date, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        Livewire::test(ListFinances::class)
            ->filterTable('bulan_tahun', ['month' => 2, 'year' => 2026])
            ->assertCanSeeTableRecords(\App\Models\Finance::whereIn('description', ['F1', 'F2'])->get())
            ->assertCanNotSeeTableRecords(\App\Models\Finance::whereIn('description', ['F0', 'F3', 'F4'])->get());
    }

    // --- known-issues #27: label bulan grafik pendapatan ---

    public function test_label_bulan_grafik_tidak_bergeser_di_tanggal_31(): void
    {
        $this->travelTo(now()->setDate(2026, 1, 31)->setTime(10, 0));
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $widget = new BiinIncomeChart;
        $widget->filter = 'month'; // tampilan per bulan (Jan–Des)
        $data = (fn () => $this->getData())->call($widget);

        $this->assertSame(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], $data['labels']);
    }
}
