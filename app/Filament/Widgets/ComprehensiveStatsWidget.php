<?php

namespace App\Filament\Widgets;

use App\Models\SekolahBola;
use App\Models\PemainBola;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComprehensiveStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Tren Pendaftaran Bulanan';
    
    protected static ?int $sort = 4;
    
    protected static ?string $maxHeight = '400px';
    
    protected static ?string $pollingInterval = '60s';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Data untuk 6 bulan terakhir
        $months = collect();
        for ($i = 2; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i));
        }
        
        $sekolahData = SekolahBola::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });
            
        $pemainData = PemainBola::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        // Data kategori per bulan untuk stacked bar
        $kategoriPerBulan = PemainBola::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, umur_kategori, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month', 'umur_kategori')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->groupBy(function($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });
        
        $labels = $months->map(fn($date) => $date->format('M Y'))->toArray();
        
        $sekolahCounts = $months->map(function($date) use ($sekolahData) {
            $key = $date->format('Y-m');
            return $sekolahData->get($key)?->count ?? 0;
        })->toArray();
        
        $pemainCounts = $months->map(function($date) use ($pemainData) {
            $key = $date->format('Y-m');
            return $pemainData->get($key)?->count ?? 0;
        })->toArray();

        // Data untuk stacked bar - pemain per kategori
        $kategori7_8 = $months->map(function($date) use ($kategoriPerBulan) {
            $key = $date->format('Y-m');
            $monthData = $kategoriPerBulan->get($key, collect());
            return $monthData->where('umur_kategori', '7-8')->sum('count');
        })->toArray();

        $kategori9_10 = $months->map(function($date) use ($kategoriPerBulan) {
            $key = $date->format('Y-m');
            $monthData = $kategoriPerBulan->get($key, collect());
            return $monthData->where('umur_kategori', '9-10')->sum('count');
        })->toArray();

        $kategori11_12 = $months->map(function($date) use ($kategoriPerBulan) {
            $key = $date->format('Y-m');
            $monthData = $kategoriPerBulan->get($key, collect());
            return $monthData->where('umur_kategori', '11-12')->sum('count');
        })->toArray();
        
        return [
            'datasets' => [
                // Line untuk SSB
                [
                    'type' => 'line',
                    'label' => 'Pendaftaran SSB',
                    'data' => $sekolahCounts,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 3,
                    'fill' => false,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                    'pointBackgroundColor' => 'rgb(34, 197, 94)',
                    'pointBorderColor' => 'rgb(255, 255, 255)',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                ],
                // Stacked bars untuk kategori umur
                [
                    'type' => 'bar',
                    'label' => 'Kategori 7-8 Tahun',
                    'data' => $kategori7_8,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                    'stack' => 'pemain',
                    'yAxisID' => 'y',
                ],
                [
                    'type' => 'bar',
                    'label' => 'Kategori 9-10 Tahun',
                    'data' => $kategori9_10,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                    'stack' => 'pemain',
                    'yAxisID' => 'y',
                ],
                [
                    'type' => 'bar',
                    'label' => 'Kategori 11-12 Tahun',
                    'data' => $kategori11_12,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.7)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 1,
                    'stack' => 'pemain',
                    'yAxisID' => 'y',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Mixed chart akan dihandle oleh dataset type
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Pemain'
                    ],
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah SSB'
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'align' => 'center',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'title' => 'function(tooltipItems) {
                            return "Bulan: " + tooltipItems[0].label;
                        }',
                    ],
                ],
            ],
        ];
    }
}