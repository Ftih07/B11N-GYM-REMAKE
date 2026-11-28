<?php

namespace Tests\Feature\Privacy;

use App\Filament\Pages\AbsensiPage;
use App\Filament\Resources\SurveyResource;
use App\Models\Member;
use App\Models\User;
use App\Models\WebVisitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Fase 5: SEC-10, SEC-17, PRV-01, PRV-02, PRV-03, PRV-05, MNT-05, INT-16. */
class PrivacyAndFilesTest extends TestCase
{
    use RefreshDatabase;

    private const PNG_1PX = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');
        DB::table('gymkos')->insert(['id' => 1, 'name' => 'B11N Gym', 'created_at' => now(), 'updated_at' => now()]);
    }

    private function member(array $attrs = []): Member
    {
        $id = DB::table('members')->insertGetId(array_merge([
            'gymkos_id' => 1, 'name' => 'Budi', 'join_date' => '2026-01-01', 'status' => 'active',
            'membership_end_date' => now()->addMonth()->toDateString(), 'created_at' => now(), 'updated_at' => now(),
        ], $attrs));

        return Member::findOrFail($id);
    }

    // --- SEC-10 / SEC-17: foto wajah member ---

    public function test_foto_webcam_disimpan_di_disk_privat_dan_hanya_admin_yang_bisa_membuka(): void
    {
        $member = $this->member();
        $member->update(['picture' => 'data:image/png;base64,'.self::PNG_1PX]);

        $path = $member->fresh()->picture;
        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);
        $this->assertSame(route('media.member-photo', $member), $member->fresh()->photo_url);

        $this->get($member->photo_url)->assertRedirect(route('filament.admin.auth.login'));
        $this->actingAs(User::factory()->create(['role' => 'customer']))->get($member->photo_url)->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get($member->photo_url)->assertOk();
    }

    public function test_data_webcam_yang_bukan_gambar_diabaikan(): void
    {
        $member = $this->member(['picture' => 'member-photos/lama.jpg']);
        $member->update(['picture' => 'data:image/png;base64,'.base64_encode('<?php echo "bukan gambar";')]);

        $this->assertSame('member-photos/lama.jpg', $member->fresh()->picture);
        $this->assertCount(0, Storage::disk('local')->allFiles());
    }

    public function test_berkas_lama_di_disk_public_tetap_tampil_selama_transisi(): void
    {
        Storage::disk('public')->put('member-photos/lama.jpg', 'isi');
        $member = $this->member(['picture' => 'member-photos/lama.jpg']);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get($member->photo_url)
            ->assertOk();
    }

    // --- PRV-02: foto progres ---

    public function test_foto_progres_privat_dan_hanya_untuk_pemilik_atau_admin(): void
    {
        $owner = User::factory()->create(['role' => 'customer', 'email' => 'pemilik@example.test']);
        $member = $this->member(['email' => 'pemilik@example.test', 'user_id' => $owner->id]);

        $this->actingAs($owner)->post('/measurements', [
            'weight' => 70, 'progress_photo' => UploadedFile::fake()->image('progres.jpg'),
        ])->assertRedirect();

        $measurement = DB::table('member_measurements')->first();
        Storage::disk('local')->assertExists($measurement->progress_photo);
        Storage::disk('public')->assertMissing($measurement->progress_photo);

        $url = route('media.progress', $measurement->id);
        $this->actingAs($owner)->get($url)->assertOk();
        $this->actingAs(User::factory()->create(['role' => 'customer']))->get($url)->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'super_admin']))->get($url)->assertOk();
    }

    // --- SEC-10: bukti transaksi ---

    public function test_bukti_transaksi_hanya_untuk_admin(): void
    {
        Storage::disk('local')->put('transaction-proofs/bukti.jpg', 'isi');
        $trxId = DB::table('transactions')->insertGetId([
            'code' => 'TRX-T-1', 'gymkos_id' => 1, 'payment_method' => 'transfer', 'status' => 'paid',
            'proof_of_payment' => 'transaction-proofs/bukti.jpg', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $url = route('media.transaction-proof', $trxId);
        $this->actingAs(User::factory()->create(['role' => 'customer']))->get($url)->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get($url)->assertOk();
    }

    public function test_perintah_pindah_privat_memindahkan_berkas_lama(): void
    {
        Storage::disk('public')->put('member-photos/a.jpg', 'a');
        Storage::disk('public')->put('progress_photos/b.jpg', 'b');
        Storage::disk('public')->put('payment_receipts/c.jpg', 'c');
        Storage::disk('public')->put('product/biarkan.jpg', 'd'); // foto produk tetap publik

        Artisan::call('media:pindah-privat', ['--dry-run' => true]);
        Storage::disk('public')->assertExists('member-photos/a.jpg');

        $this->assertSame(0, Artisan::call('media:pindah-privat'));
        Storage::disk('local')->assertExists(['member-photos/a.jpg', 'progress_photos/b.jpg', 'arsip/payment_receipts/c.jpg']);
        Storage::disk('public')->assertMissing(['member-photos/a.jpg', 'progress_photos/b.jpg', 'payment_receipts/c.jpg']);
        Storage::disk('public')->assertExists('product/biarkan.jpg');

        $this->assertSame(0, Artisan::call('media:pindah-privat')); // aman diulang
    }

    public function test_form_edit_member_dan_transaksi_menampilkan_berkas_privat(): void
    {
        Storage::disk('local')->put('member-photos/a.jpg', 'isi');
        Storage::disk('local')->put('transaction-proofs/b.jpg', 'isi');
        $member = $this->member(['picture' => 'member-photos/a.jpg', 'member_code' => 'B-00001']);
        $trxId = DB::table('transactions')->insertGetId([
            'code' => 'TRX-T-2', 'gymkos_id' => 1, 'payment_method' => 'transfer', 'status' => 'pending',
            'proof_of_payment' => 'transaction-proofs/b.jpg', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(\App\Filament\Resources\MemberResource::getUrl('edit', ['record' => $member]))
            ->assertOk()
            ->assertSee(str_replace('/', '\\/', route('media.member-photo', $member)), false);
        $this->get(\App\Filament\Resources\TransactionResource::getUrl('edit', ['record' => $trxId]))->assertOk();
        $this->get(\App\Filament\Resources\MemberResource::getUrl('index'))->assertOk()->assertSee(route('media.member-photo', $member), false);
    }

    // --- PRV-01: absensi & kebijakan privasi ---

    public function test_halaman_absensi_tidak_lagi_memuat_model_usia_gender_ekspresi(): void
    {
        $html = $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(AbsensiPage::getUrl())
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('ageGenderNet', $html);
        $this->assertStringNotContainsString('faceExpressionNet', $html);
        $this->assertFileDoesNotExist(public_path('models/age_gender_model-shard1'));
    }

    public function test_kebijakan_privasi_menyebut_data_wajah_ip_dan_hak_hapus(): void
    {
        $this->get('/legal')
            ->assertOk()
            ->assertSee('data pengenal wajah')
            ->assertSee('hash')
            ->assertSee('13 bulan')
            ->assertSee(config('b1ng.contact.phone'))
            ->assertDontSee('standar keamanan enkripsi');
    }

    // --- PRV-05: IP pengunjung ---

    public function test_ip_pengunjung_disimpan_sebagai_hash_dan_data_lama_dipangkas(): void
    {
        DB::table('web_visitors')->insert([
            'ip_address' => 'lama', 'visit_date' => now()->subMonths(14)->toDateString(), 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])->get('/legal')->assertOk();

        $row = DB::table('web_visitors')->where('visit_date', today()->toDateString())->first();
        $this->assertSame(WebVisitor::hashIp('203.0.113.7'), $row->ip_address);
        $this->assertSame(64, strlen($row->ip_address));
        $this->assertDatabaseMissing('web_visitors', ['ip_address' => 'lama']);
    }

    public function test_migration_hash_ip_lama_memakai_rumus_yang_sama(): void
    {
        DB::table('web_visitors')->insert([
            ['ip_address' => '198.51.100.1', 'visit_date' => '2026-11-01', 'created_at' => now(), 'updated_at' => now()],
            ['ip_address' => WebVisitor::hashIp('198.51.100.2'), 'visit_date' => '2026-11-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        (require database_path('migrations/2026_11_28_200000_hash_web_visitor_ips.php'))->up();

        $this->assertDatabaseHas('web_visitors', ['ip_address' => WebVisitor::hashIp('198.51.100.1')]);
        $this->assertDatabaseHas('web_visitors', ['ip_address' => WebVisitor::hashIp('198.51.100.2')]); // tidak di-hash dua kali
    }

    // --- PRV-03, MNT-05, INT-16: bersih-bersih ---

    public function test_berkas_dan_tabel_yatim_sudah_dibersihkan(): void
    {
        foreach (['testimoni.json', 'testimoni_kinggym.json', 'reviewkost.json', 'assets/js/script.js', 'assets/js/home.js'] as $file) {
            $this->assertFileDoesNotExist(public_path($file));
        }
        $this->assertFileExists(database_path('seeders/data/reviewkost.json'));
        $this->assertFileDoesNotExist(base_path('index.html'));
        $this->assertFileDoesNotExist(base_path('test-unit.php'));
        $this->assertFalse(Schema::hasTable('events'));

        $this->actingAs(User::factory()->create(['role' => 'super_admin']))
            ->get(SurveyResource::getUrl('index'))
            ->assertOk();
    }
}
