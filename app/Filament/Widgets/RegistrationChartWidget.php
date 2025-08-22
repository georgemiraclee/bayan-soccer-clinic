<?php

namespace App\Filament\Widgets;

use App\Models\SekolahBola;
use App\Models\PemainBola;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistrationChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pendaftaran Sekolah Sepak Bola Per Hari (7 Hari Terakhir)';
    
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
        // Data untuk 7 hari terakhir
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i));
        }
        
        $sekolahData = SekolahBola::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();
            
        $pemainData = PemainBola::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();
        
        $labels = $dates->map(fn($date) => $date->format('d M'))->toArray();
        
        $sekolahCounts = $dates->map(function($date) use ($sekolahData) {
            return $sekolahData[$date->format('Y-m-d')] ?? 0;
        })->toArray();
        
        $pemainCounts = $dates->map(function($date) use ($pemainData) {
            return $pemainData[$date->format('Y-m-d')] ?? 0;
        })->toArray();
        
        return [
            'datasets' => [
                [
                    'label' => 'Pendaftaran SSB',
                    'data' => $sekolahCounts,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Pendaftaran Pemain',
                    'data' => $pemainCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    private function getWeeklyData(): array
    {
        // Data untuk 4 minggu terakhir
        $weeks = collect();
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            $weeks->push([
                'start' => $startOfWeek,
                'end' => $endOfWeek,
                'label' => 'Minggu ' . $startOfWeek->format('d M')
            ]);
        }
        
        $sekolahCounts = [];
        $pemainCounts = [];
        $labels = [];
        
        foreach ($weeks as $week) {
            $sekolahCount = SekolahBola::whereBetween('created_at', [$week['start'], $week['end']])->count();
            $pemainCount = PemainBola::whereBetween('created_at', [$week['start'], $week['end']])->count();
            
            $sekolahCounts[] = $sekolahCount;
            $pemainCounts[] = $pemainCount;
            $labels[] = $week['label'];
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Pendaftaran SSB',
                    'data' => $sekolahCounts,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Pendaftaran Pemain',
                    'data' => $pemainCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return $this->filter === 'weekly' ? 'bar' : 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}