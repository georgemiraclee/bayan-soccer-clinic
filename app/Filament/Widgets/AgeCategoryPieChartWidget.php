<?php

namespace App\Filament\Widgets;

use App\Models\PemainBola;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AgeCategoryPieChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Kategori Umur Pemain';
    
    protected static ?int $sort = 3;
    
    protected static ?string $maxHeight = '350px';
    
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        // Ambil data kategori umur
        $kategoriStats = PemainBola::select('umur_kategori', DB::raw('count(*) as total'))
            ->groupBy('umur_kategori')
            ->orderBy('umur_kategori')
            ->get()
            ->pluck('total', 'umur_kategori')
            ->toArray();

        // Definisi warna untuk setiap kategori
        $colors = [
            '7-8' => 'rgb(34, 197, 94)',      // Green
            '9-10' => 'rgb(59, 130, 246)',    // Blue  
            '11-12' => 'rgb(245, 158, 11)',   // Amber
        ];

        $hoverColors = [
            '7-8' => 'rgba(34, 197, 94, 0.8)',
            '9-10' => 'rgba(59, 130, 246, 0.8)', 
            '11-12' => 'rgba(245, 158, 11, 0.8)',
        ];

        $labels = [];
        $data = [];
        $backgroundColors = [];
        $hoverBackgroundColors = [];

        // Urutkan berdasarkan kategori untuk konsistensi
        $orderedCategories = ['7-8', '9-10', '11-12'];
        
        foreach ($orderedCategories as $category) {
            $count = $kategoriStats[$category] ?? 0;
            if ($count > 0) { // Hanya tampilkan kategori yang memiliki data
                $labels[] = "Kategori {$category} Tahun";
                $data[] = $count;
                $backgroundColors[] = $colors[$category];
                $hoverBackgroundColors[] = $hoverColors[$category];
            }
        }

        // Jika tidak ada data sama sekali
        if (empty($data)) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['rgb(156, 163, 175)'], // Gray
                        'hoverBackgroundColor' => ['rgba(156, 163, 175, 0.8)'],
                        'borderWidth' => 2,
                        'borderColor' => 'rgb(255, 255, 255)',
                    ],
                ],
                'labels' => ['Belum ada data'],
            ];
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'hoverBackgroundColor' => $hoverBackgroundColors,
                    'borderWidth' => 2,
                    'borderColor' => 'rgb(255, 255, 255)',
                    'hoverBorderWidth' => 3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Menggunakan doughnut chart yang lebih modern
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
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ": " + value + " pemain (" + percentage + "%)";
                        }',
                    ],
                ],
            ],
            'cutout' => '60%', // Membuat hole di tengah untuk doughnut effect
            'interaction' => [
                'intersect' => false,
            ],
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
            ],
        ];
    }
}