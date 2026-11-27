<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Seluruh portal /employee (login karyawan, POS, produk, front desk member) sudah dihapus;
 * pengelolaan dipusatkan di panel Filament /admin.
 */
class EmployeePortalRemovedTest extends TestCase
{
    use RefreshDatabase;

    public function test_semua_rute_employee_tidak_ada_lagi(): void
    {
        $this->get('/employee/login')->assertNotFound();
        $this->get('/employee/dashboard')->assertNotFound();
        $this->get('/employee/products')->assertNotFound();
        $this->post('/employee/transaction')->assertNotFound();
        // Termasuk nilai token default front desk lama yang pernah ada di repo publik.
        $this->get('/employee/members?token=b1ng-empire-secret-frontdesk-2026')->assertNotFound();
    }

    public function test_cetak_struk_hanya_untuk_admin_dan_super_admin(): void
    {
        $now = now();
        $gymId = DB::table('gymkos')->insertGetId(['name' => 'B11N Gym', 'created_at' => $now, 'updated_at' => $now]);
        // Transaksi online: tanpa kasir (trainer_id NULL) — dulu membuat struk error.
        DB::table('transactions')->insert([
            'code' => 'TRX-TEST-1', 'gymkos_id' => $gymId, 'payment_method' => 'transfer',
            'total_amount' => 90000, 'status' => 'paid', 'created_at' => $now, 'updated_at' => $now,
        ]);

        foreach (['admin', 'super_admin'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get('/print/transaction/TRX-TEST-1')
                ->assertOk()
                ->assertSee('Online');
        }

        foreach (['customer'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get('/print/transaction/TRX-TEST-1')
                ->assertForbidden();
        }
    }
}
