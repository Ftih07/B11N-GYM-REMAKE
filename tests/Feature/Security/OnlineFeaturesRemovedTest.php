<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Fitur pembayaran membership online & booking kost dihapus (2026-11-28);
 * pendaftaran, perpanjangan, dan sewa kost lewat WhatsApp ke nomor di config/b1ng.php.
 */
class OnlineFeaturesRemovedTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_publik_sudah_tidak_ada(): void
    {
        $this->post('/payment/upload')->assertNotFound();
        $this->post('/kost-istana-merdeka-3/book')->assertNotFound();
    }

    public function test_menu_admin_sudah_tidak_ada(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'super_admin']));

        $this->get('/admin/payments')->assertNotFound();
        $this->get('/admin/bookings')->assertNotFound();
    }

    public function test_tabel_sudah_dihapus(): void
    {
        $this->assertFalse(Schema::hasTable('payment_membership'));
        $this->assertFalse(Schema::hasTable('booking_kost'));
    }

    public function test_halaman_kost_memakai_nomor_whatsapp_baru(): void
    {
        $html = $this->get('/kost-istana-merdeka-3')->assertOk()->getContent();

        $this->assertStringContainsString('https://wa.me/'.config('b1ng.contact.whatsapp'), $html);
        $this->assertStringNotContainsString('83194288423', $html);
        $this->assertStringNotContainsString('89653847651', $html);
    }
}
