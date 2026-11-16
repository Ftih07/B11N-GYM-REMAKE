<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class SalesHeatmap extends Widget
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
            $hm = $a->heatmap($id);
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
        ];
    }
}