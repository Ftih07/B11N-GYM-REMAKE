<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** SEC-11, SEC-12, SEC-20, REL-05. */
class PublicPagesHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_keamanan_terpasang(): void
    {
        $this->get('/login')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy');
    }

    public function test_konten_blog_disanitasi_tapi_format_tetap(): void
    {
        DB::table('blogs')->insert([
            'title' => 'Artikel', 'slug' => 'artikel', 'image' => 'x.jpg', 'status' => 'publish',
            'content' => '<h2>Judul Bagian</h2><p onclick="alert(1)">Isi <strong>tebal</strong></p>'
                .'<script>alert("xss")</script><a href="javascript:alert(1)">tautan</a>',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $html = $this->get('/blogs/artikel')->assertOk()->getContent();

        $this->assertStringContainsString('<h2>Judul Bagian</h2>', $html);
        $this->assertStringContainsString('<strong>tebal</strong>', $html);
        $this->assertStringNotContainsString('alert("xss")', $html);
        $this->assertStringNotContainsString('onclick="alert(1)"', $html);
        $this->assertStringNotContainsString('javascript:alert(1)', $html);
    }

    public function test_cetak_qr_code_hanya_untuk_admin(): void
    {
        $id = DB::table('qr_codes')->insertGetId([
            'name' => 'Tes', 'target_url' => 'https://example.test', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->get("/qr-code/print/{$id}")->assertRedirect(route('filament.admin.auth.login'));

        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->get("/qr-code/print/{$id}")->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get("/qr-code/print/{$id}")->assertOk();
    }

    public function test_pesan_flash_dengan_petik_dan_baris_baru_tidak_memecah_skrip(): void
    {
        $html = $this->withSession(['error' => "Gagal: kamar 'A'\n<b>penuh</b>"])
            ->get('/survey')->assertOk()->getContent();

        // Dulu: showModernToast('Gagal: kamar &#039;A&#039;<baris baru>...') → SyntaxError
        // Hasil @json(e(...)): HTML di-escape, lalu & / ' / < di-encode JSON (\u0026 dst.)
        $expected = 'showModernToast("Gagal: kamar AMP#039;AAMP#039;\\nAMPlt;bAMPgt;penuhAMPlt;\\/bAMPgt;", \'error\')';
        $this->assertStringContainsString(str_replace('AMP', '\\u0026', $expected), $html);
    }
}
