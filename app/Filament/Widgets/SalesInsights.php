<?php

namespace App\Filament\Widgets;

use App\Services\SalesAnalytics;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class SalesInsights extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;
    protected static string $view = 'filament.widgets.sales-insights';
    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return ['insights' => SalesAnalytics::make($this->filters)->insights()];
    }
}