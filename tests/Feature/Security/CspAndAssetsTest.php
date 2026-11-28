<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Fase 7: SEC-12 (CSP, versi CDN terkunci + SRI), SEC-13 (.env.example), PERF-07 (gambar). */
class CspAndAssetsTest extends TestCase
{
    use RefreshDatabase;

    private const PUBLIC_PAGES = [
        '/', '/biin-gym', '/king-gym', '/kost-istana-merdeka-3', '/legal', '/survey',
        '/login', '/blogs', '/maintenance-report', '/gym/equipments',
    ];

    public function test_csp_aktif_dan_membatasi_sumber(): void
    {
        $csp = $this->get('/login')->assertOk()->headers->get('Content-Security-Policy');

        $this->assertNotNull($csp);
        foreach (["default-src 'self'", "frame-ancestors 'self'", "object-src 'none'", "base-uri 'self'", "form-action 'self'"] as $directive) {
            $this->assertStringContainsString($directive, $csp);
        }
        $this->assertStringNotContainsString('unpkg.com', $csp);
        $this->assertStringNotContainsString('cdn.tailwindcss.com', $csp);
    }

    public function test_csp_bisa_dimatikan_lewat_config(): void
    {
        config(['b1ng.csp.enabled' => false]);

        $this->get('/login')->assertOk()->assertHeaderMissing('Content-Security-Policy');
    }

    public function test_halaman_publik_hanya_memuat_pustaka_terkunci_dengan_sri(): void
    {
        foreach (self::PUBLIC_PAGES as $page) {
            $html = $this->get($page)->assertOk()->getContent();

            foreach (['cdn.tailwindcss.com', 'unpkg.com', '@3.x.x', '5.3.0-alpha3', 'swiper@11/'] as $banned) {
                $this->assertStringNotContainsString($banned, $html, "{$page} masih memuat {$banned}");
            }

            preg_match_all('/<(?:script|link)\b[^>]*(?:src|href)="https:\/\/(?:cdn\.jsdelivr\.net|cdnjs\.cloudflare\.com)[^>]*>/', $html, $tags);
            foreach ($tags[0] as $tag) {
                $this->assertMatchesRegularExpression('/integrity="sha384-[A-Za-z0-9+\/=]{64}" crossorigin="anonymous"/', $tag, "{$page}: {$tag}");
            }
        }
    }

    public function test_daftar_pustaka_cdn_lengkap(): void
    {
        foreach (config('cdn') as $name => $lib) {
            if (isset($lib['local'])) {
                $this->assertFileExists(public_path($lib['local']), $name);

                continue;
            }
            $this->assertMatchesRegularExpression('/@\d+\.\d+\.\d+|\/\d+\.\d+\.\d+\//', $lib['url'], "{$name}: versi harus pasti");
            $this->assertStringStartsWith('sha384-', $lib['integrity'], $name);
        }
    }

    public function test_gambar_yang_dirujuk_halaman_publik_ada_dan_tidak_raksasa(): void
    {
        foreach (self::PUBLIC_PAGES as $page) {
            $html = $this->get($page)->getContent();
            preg_match_all('#(?:src|href)="(?:'.preg_quote(url('/'), '#').')?/?(assets/[^"?]+\.(?:png|jpe?g|webp))"#', $html, $m);

            foreach (array_unique($m[1]) as $asset) {
                $this->assertFileExists(public_path($asset), "{$page} merujuk {$asset}");
                $this->assertLessThan(600 * 1024, filesize(public_path($asset)), "{$asset} terlalu besar untuk halaman publik");
            }
        }
    }

    public function test_env_example_mencantumkan_pengaturan_produksi(): void
    {
        $env = file_get_contents(base_path('.env.example'));

        $this->assertStringContainsString('SESSION_SECURE_COOKIE=', $env);
        $this->assertStringContainsString('CSP_ENABLED=true', $env);
        $this->assertStringContainsString('APP_DEBUG=false', $env); // disebut di catatan produksi
    }
}
