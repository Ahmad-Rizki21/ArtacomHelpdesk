<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserScoreChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'userScoreChart';
    protected static ?string $heading = 'User Score Distribution';
    protected static ?string $description = 'Bar chart menampilkan distribusi skor semua user.';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = null; // Nonaktifkan polling
    protected static bool $deferLoading = true;

    // Membuat chart memanjang penuh
    protected int | string | array $columnSpan = 'full';



    protected function getOptions(): array
{
    try {
        // Ambil semua user dengan role HELPDESK beserta skornya
        $users = User::role('HELPDESK')->select('name', 'score')
            ->orderBy('score', 'desc')
            ->get();

        $labels = $users->pluck('name')->toArray();
        $scores = $users->pluck('score')->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
                'width' => '100%',
                'toolbar' => [
                    'show' => false,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
                'fontFamily' => 'inherit',
            ],
            'series' => [
                [
                    'name' => 'Skor User HELPDESK',
                    'data' => $scores,
                    'color' => '#4CAF50',
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'title' => [
                    'text' => 'User HELPDESK',
                    'style' => [
                        'color' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                    'rotate' => -45,
                    'hideOverlappingLabels' => true,
                ],
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Skor',
                    'style' => [
                        'color' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
                'min' => 0,
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '50%',
                ],
            ],
            'grid' => [
                'borderColor' => '#1f2937',
                'row' => [
                    'colors' => ['transparent', 'transparent'],
                    'opacity' => 0.1,
                ],
                'padding' => [
                    'left' => 0,
                    'right' => 0,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'tooltip' => [
                'shared' => true,
                'intersect' => false,
                'theme' => 'dark',
            ],
            'legend' => [
                'show' => true,
                'position' => 'top',
                'horizontalAlign' => 'center',
                'labels' => [
                    'colors' => '#fff',
                ],
            ],
            'responsive' => [
                [
                    'breakpoint' => 1000,
                    'options' => [
                        'chart' => [
                            'width' => '100%',
                        ],
                        'xaxis' => [
                            'labels' => [
                                'rotate' => -45,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    } catch (\Exception $e) {
        Log::error('Error generating User Score Chart: ' . $e->getMessage());
        return [];
    }
}
}