<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

/** SEC-05: login Google/Facebook hanya untuk akun customer. */
class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    private function fakeSocialUser(string $provider, string $email, array $raw = []): void
    {
        $socialUser = (new SocialiteUser)
            ->setRaw(array_merge(['email_verified' => true], $raw))
            ->map(['id' => 'sosial-123', 'name' => 'Pengguna Sosial', 'email' => $email, 'avatar' => 'https://example.test/a.png']);

        Socialite::shouldReceive('driver')->with($provider)->andReturn(
            \Mockery::mock()->shouldReceive('user')->andReturn($socialUser)->getMock()
        );
    }

    public function test_email_akun_admin_tidak_bisa_masuk_lewat_google(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@example.test']);
        $this->fakeSocialUser('google', 'admin@example.test');

        $this->get('/auth/google/callback')
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');

        $this->assertGuest();
        // Akun admin tidak ikut diubah (google_id tidak ditautkan)
        $this->assertNull($admin->fresh()->google_id);
    }

    public function test_email_super_admin_tidak_bisa_masuk_lewat_facebook(): void
    {
        User::factory()->create(['role' => 'super_admin', 'email' => 'boss@example.test']);
        $this->fakeSocialUser('facebook', 'boss@example.test');

        $this->get('/auth/facebook/callback')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_pengguna_baru_menjadi_customer_dan_tertaut_ke_member(): void
    {
        $gymId = DB::table('gymkos')->insertGetId(['name' => 'B11N Gym', 'created_at' => now(), 'updated_at' => now()]);
        $memberId = DB::table('members')->insertGetId([
            'gymkos_id' => $gymId, 'name' => 'Member', 'email' => 'member@example.test',
            'join_date' => now()->toDateString(), 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->fakeSocialUser('google', 'member@example.test');

        $this->get('/auth/google/callback')->assertRedirect('/dashboard');

        $user = User::where('email', 'member@example.test')->firstOrFail();
        $this->assertSame('customer', $user->role);
        $this->assertSame('sosial-123', $user->google_id);
        $this->assertAuthenticatedAs($user);
        $this->assertSame($user->id, DB::table('members')->where('id', $memberId)->value('user_id'));
    }

    public function test_email_google_belum_terverifikasi_ditolak(): void
    {
        $this->fakeSocialUser('google', 'baru@example.test', ['email_verified' => false]);

        $this->get('/auth/google/callback')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'baru@example.test']);
    }

    public function test_pesan_error_tampil_di_halaman_login(): void
    {
        $this->withSession(['error' => 'Akun ini adalah akun admin.'])
            ->get('/login')
            ->assertOk()
            ->assertSee('Akun ini adalah akun admin.');
    }
}
