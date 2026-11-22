<?php

namespace Tests\Unit;

use App\Models\MembershipRenewal;
use App\Services\MembershipRenewalService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class MembershipRenewalMonthsTest extends TestCase
{
    public function test_tiga_bulan_melewati_pergantian_tahun(): void
    {
        $this->assertSame(
            ['2026-11', '2026-12', '2027-01'],
            MembershipRenewal::expandMonths('2026-11-17', 3)
        );
    }

    public function test_minimal_satu_bulan(): void
    {
        $this->assertSame(['2026-05'], MembershipRenewal::expandMonths('2026-05-31', 0));
    }

    public function test_perpanjang_sebelum_habis_menyambung_dari_tanggal_lama(): void
    {
        $p = MembershipRenewalService::inferPeriod(Carbon::parse('2026-12-10'), Carbon::parse('2027-01-10'), Carbon::parse('2026-11-22'));
        $this->assertSame('2026-12-01', $p['period_month']->toDateString());
        $this->assertSame(1, $p['months']);
    }

    public function test_sudah_expired_mulai_dari_hari_ini(): void
    {
        $p = MembershipRenewalService::inferPeriod(Carbon::parse('2026-09-01'), Carbon::parse('2027-02-22'), Carbon::parse('2026-11-22'));
        $this->assertSame('2026-11-01', $p['period_month']->toDateString());
        $this->assertSame(3, $p['months']);
    }

    public function test_koreksi_mundur_bukan_perpanjangan(): void
    {
        $this->assertFalse(MembershipRenewalService::isExtension(Carbon::parse('2026-12-10'), Carbon::parse('2026-12-01')));
        $this->assertTrue(MembershipRenewalService::isExtension(null, Carbon::parse('2026-12-01')));
    }
}
