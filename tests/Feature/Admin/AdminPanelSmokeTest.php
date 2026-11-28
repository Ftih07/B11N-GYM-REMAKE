<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Jaring pengaman upgrade (Fase 6, Laravel 12 + Filament 3.3): setiap halaman panel admin
 * harus bisa dibuka super_admin tanpa error.
 */
class AdminPanelSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_semua_halaman_panel_admin_terbuka(): void
    {
        DB::table('gymkos')->insert([
            ['id' => 1, 'name' => 'B11N Gym', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'K1NG Gym', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->actingAs(User::factory()->create(['role' => 'super_admin']));

        $panel = Filament::getPanel('admin');
        $urls = [$panel->getUrl()];

        foreach ($panel->getResources() as $resource) {
            foreach (['index', 'create'] as $page) {
                if ($resource::hasPage($page) && ($page !== 'create' || $resource::canCreate())) {
                    $urls[] = $resource::getUrl($page);
                }
            }
        }

        foreach ($panel->getPages() as $page) {
            if ($page::canAccess()) {
                $urls[] = $page::getUrl();
            }
        }

        $this->assertGreaterThan(25, count($urls));

        foreach (array_unique($urls) as $url) {
            $this->get($url)->assertOk();
        }
    }
}
