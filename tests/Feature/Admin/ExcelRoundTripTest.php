<?php

namespace Tests\Feature\Admin;

use App\Exports\MemberExport;
use App\Imports\MemberImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

/** Jaring pengaman upgrade Laravel Excel 3.1.70 + PhpSpreadsheet 1.30.9 (Fase 6). */
class ExcelRoundTripTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('gymkos')->insert(['id' => 1, 'name' => 'B11N Gym', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_import_member_dari_xlsx_lalu_export_lagi(): void
    {
        // Format MemberImport: A=kode, B=tgl akhir, C=nama, D=HP, E=tgl join (baris 1 = judul)
        $sheet = new class implements FromArray
        {
            public function array(): array
            {
                return [
                    ['Kode', 'Berlaku Sampai', 'Nama', 'HP', 'Tgl Join'],
                    ['B-00001', '2026-12-31', 'Andi Uji', '081200000001', '2026-01-05'],
                    ['B-00002', '2026-12-31', 'Sari Uji', '081200000002', '2026-02-10'],
                ];
            }
        };
        $path = sys_get_temp_dir().'/import-member-'.uniqid().'.xlsx';
        file_put_contents($path, Excel::raw($sheet, ExcelFormat::XLSX));

        Excel::import(new MemberImport(1), new UploadedFile($path, 'member.xlsx', null, null, true));
        @unlink($path);

        $this->assertDatabaseHas('members', ['member_code' => 'B-00001', 'name' => 'Andi Uji', 'gymkos_id' => 1]);
        $this->assertDatabaseHas('members', ['member_code' => 'B-00002', 'name' => 'Sari Uji']);

        $xlsx = Excel::raw(new MemberExport('all', null, null, 1), ExcelFormat::XLSX);
        $this->assertStringStartsWith('PK', $xlsx); // file zip/xlsx valid
    }
}
