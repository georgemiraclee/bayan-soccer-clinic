<?php

namespace App\Filament\Widgets;

use App\Models\SekolahBola;
use App\Models\PemainBola;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistrationChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Akumulasi Pendaftaran';
    
    protected static ?int $sort = 2;
    
    protected static ?string $maxHeight = '700px';
    
    protected static ?string $pollingInterval = '30s';

    // Toggle untuk switching antara daily dan weekly view
    public ?string $filter = 'daily';
    
    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Per Hari (7 hari)',
            'weekly' => 'Per Minggu (4 minggu)',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;
        
        if ($filter === 'weekly') {
            return $this->getWeeklyData();
        }
        
        return $this->getDailyData();
    }
    
    private function getDailyData(): array
    {
        // Total untuk 7 hari terakhir
        $sekolahCount = SekolahBola::where('created_at', '>=', now()->subDays(6)->startOfDay())->count();
        $pemainCount = PemainBola::where('created_at', '>=', now()->subDays(6)->startOfDay())->count();
        
        return [
            'datasets' => [
                [
                    'data' => [$sekolahCount, $pemainCount],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // Green untuk SSB
                        'rgb(59, 130, 246)',  // Blue untuk Pemain
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Pendaftaran SSB', 'Pendaftaran Pemain'],
        ];
    }
    
    private function getWeeklyData(): array
    {
        // Total untuk 4 minggu terakhir
        $startDate = Carbon::now()->subWeeks(3)->startOfWeek();
        
        $sekolahCount = SekolahBola::where('created_at', '>=', $startDate)->count();
        $pemainCount = PemainBola::where('created_at', '>=', $startDate)->count();
        
        return [
            'datasets' => [
                [
                    'data' => [$sekolahCount, $pemainCount],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // Green untuk SSB
                        'rgb(59, 130, 246)',  // Blue untuk Pemain
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Pendaftaran SSB', 'Pendaftaran Pemain'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            const label = context.label || "";
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ": " + value + " (" + percentage + "%)";
                        }',
                    ],
                ],
            ],
            'cutout' => '60%', // Ini yang membuat donut hole
            'layout' => [
                'padding' => 10,
            ],
        ];
    }
}