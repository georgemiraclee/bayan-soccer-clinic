<?php

namespace App\Filament\Widgets;

use App\Models\SekolahBola;
use Filament\Widgets\ChartWidget;

class PemainPerSekolahChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Pemain per Sekolah';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        foreach (SekolahBola::withCount('pemainBola')->get() as $sekolah) {
            $labels[] = $sekolah->nama;
            $data[] = $sekolah->pemain_bola_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pemain',
                    'data' => $data,
                    'backgroundColor' => ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
