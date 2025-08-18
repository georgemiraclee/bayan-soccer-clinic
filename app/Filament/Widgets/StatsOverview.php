<?php

namespace App\Filament\Widgets;

use App\Models\PemainBola;
use App\Models\SekolahBola;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Pemain', PemainBola::count())
                ->description('Jumlah pemain yang terdaftar')
                ->color('success'),

            Card::make('Total Sekolah', SekolahBola::count())
                ->description('Jumlah sekolah yang ada')
                ->color('primary'),
        ];
    }
}
