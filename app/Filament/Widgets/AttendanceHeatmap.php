<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class AttendanceHeatmap extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static string $view = 'filament.widgets.sales-heatmap';
    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $a = SalesAnalytics::make($this->filters);

        $heatmaps = [];
        $minH = 5;
        $maxH = 22;
        foreach ($a->branches() as $id => $name) {
            $hm = $a->attendanceHeatmap($id);
            $heatmaps[] = ['name' => $name] + $hm;

            foreach ($hm['matrix'] as $row) {
                foreach (array_keys($row) as $h) {
                    $minH = min($minH, $h);
                    $maxH = max($maxH, $h);
                }
            }
        }

        return [
            'heatmaps' => $heatmaps,
            'days' => SalesAnalytics::DAYS,
            'hours' => range($minH, $maxH),
            'sectionTitle' => 'Heatmap Jam Ramai Kunjungan (Absensi)',
            'sectionDesc' => 'Jumlah check-in per hari dan jam, dipisah per cabang. Semakin pekat warnanya, semakin ramai.',
            'unit' => 'kunjungan',
            'rgb' => '59,130,246',
        ];
    }
}
