<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

/** UX-03: kelola akun admin dari panel Filament (khusus super_admin). */
class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_hanya_super_admin_yang_bisa_membuka_halaman(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(UserResource::getUrl('index'))
            ->assertForbidden();

        $this->actingAs($this->superAdmin)
            ->get(UserResource::getUrl('index'))
            ->assertOk();
    }

    public function test_super_admin_bisa_membuat_akun_admin(): void
    {
        $this->actingAs($this->superAdmin);

        Livewire::test(ListUsers::class)
            ->callAction('create', [
                'name' => 'Admin Baru',
                'email' => 'admin.baru@example.test',
                'role' => 'admin',
                'password' => 'password-kuat-1',
                'password_confirmation' => 'password-kuat-1',
            ])
            ->assertHasNoActionErrors();

        $user = User::where('email', 'admin.baru@example.test')->firstOrFail();
        $this->assertSame('admin', $user->role);
        $this->assertTrue(Hash::check('password-kuat-1', $user->password));
    }

    public function test_password_wajib_dikonfirmasi_dan_minimal_8_karakter(): void
    {
        $this->actingAs($this->superAdmin);

        Livewire::test(ListUsers::class)
            ->callAction('create', [
                'name' => 'X', 'email' => 'x@example.test', 'role' => 'admin',
                'password' => 'pendek', 'password_confirmation' => 'beda',
            ])
            ->assertHasActionErrors(['password']);
    }

    public function test_reset_password_dan_cabut_akses_admin(): void
    {
        $this->actingAs($this->superAdmin);
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'lama-12345']);

        Livewire::test(ListUsers::class)
            ->callTableAction(EditAction::class, $admin, [
                'role' => 'customer',
                'password' => 'baru-123456',
                'password_confirmation' => 'baru-123456',
            ])
            ->assertHasNoTableActionErrors();

        $admin->refresh();
        $this->assertSame('customer', $admin->role);
        $this->assertTrue(Hash::check('baru-123456', $admin->password));
    }

    public function test_edit_tanpa_password_tidak_mengubah_password(): void
    {
        $this->actingAs($this->superAdmin);
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'tetap-12345']);

        Livewire::test(ListUsers::class)
            ->callTableAction(EditAction::class, $admin, ['name' => 'Nama Baru', 'password' => '', 'password_confirmation' => ''])
            ->assertHasNoTableActionErrors();

        $this->assertTrue(Hash::check('tetap-12345', $admin->fresh()->password));
        $this->assertSame('Nama Baru', $admin->fresh()->name);
    }

    public function test_super_admin_tidak_bisa_mengubah_role_atau_menghapus_akunnya_sendiri(): void
    {
        $this->actingAs($this->superAdmin);

        Livewire::test(ListUsers::class)
            ->assertTableActionHidden(DeleteAction::class, $this->superAdmin)
            ->callTableAction(EditAction::class, $this->superAdmin, ['role' => 'customer'])
            ->assertHasNoTableActionErrors();

        $this->assertSame('super_admin', $this->superAdmin->fresh()->role);
    }

    public function test_migration_mengubah_employee_menjadi_customer_dan_melepas_tautan_trainer(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $admin = User::factory()->create(['role' => 'admin']);
        $gymId = DB::table('gymkos')->insertGetId(['name' => 'B11N Gym', 'created_at' => now(), 'updated_at' => now()]);
        $trainerId = DB::table('trainers')->insertGetId([
            'name' => 'Coach', 'description' => '-', 'urls' => '[]', 'image' => 'x.jpg',
            'gymkos_id' => $gymId, 'user_id' => $employee->id, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $migration = require database_path('migrations/2026_11_27_000000_convert_employee_role_to_customer.php');
        $migration->up();

        $this->assertSame('customer', $employee->fresh()->role);
        $this->assertSame('admin', $admin->fresh()->role);
        $this->assertNull(DB::table('trainers')->where('id', $trainerId)->value('user_id'));
        $this->assertSame(0, User::where('role', 'employee')->count());
    }
}
