<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TicketChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'ticketChart';
    protected static ?string $heading = 'LAPORAN TICKET FTTH BULAN INI!';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = null; // Set to null to disable polling
    protected static bool $deferLoading = true;

    // Make the chart span the full width
    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        // Get monthly ticket stats for past 6 months
        $monthlyData = $this->getMonthlyTicketData();
        
        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
                'fontFamily' => 'inherit',
                'width' => '100%', // Make sure chart takes full width
            ],
            'series' => [
                [
                    'name' => 'Open',
                    'data' => $monthlyData['open'],
                    'color' => '#ef4444', // Red
                ],
                [
                    'name' => 'Pending',
                    'data' => $monthlyData['pending'],
                    'color' => '#f59e0b', // Yellow/Amber
                ],
                [
                    'name' => 'Closed',
                    'data' => $monthlyData['closed'],
                    'color' => '#10b981', // Green
                ],
            ],
            'xaxis' => [
                'categories' => $monthlyData['months'],
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 3,
            ],
            'grid' => [
                'borderColor' => '#1f2937',
                'row' => [
                    'colors' => ['transparent', 'transparent'],
                    'opacity' => 0.1
                ],
                'padding' => [
                    'left' => 0,
                    'right' => 0
                ]
            ],
            'markers' => [
                'size' => 5,
                'hover' => [
                    'size' => 7,
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
                            'width' => '100%'
                        ],
                    ],
                ]
            ],
        ];
    }

    /**
     * Get monthly ticket stats for the past 6 months
     * 
     * @return array
     */
    protected function getMonthlyTicketData(): array
    {
        // Get the last 6 months
        $months = collect();
        $openTickets = collect();
        $pendingTickets = collect();
        $closedTickets = collect();

        // Extend to show more months (12 months instead of 6)
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $months->push($monthName);

            // For demo purposes, use some generated data
            // Replace this with actual database queries for your data
            if ($i === 0) { // Current month - use actual counts
                $openTickets->push(Ticket::where('status', 'OPEN')->count());
                $pendingTickets->push(Ticket::where('status', 'PENDING')->count());
                $closedTickets->push(Ticket::where('status', 'CLOSED')->count());
            } else {
                // Past months - generate meaningful demo data
                $openTickets->push(rand(0, 5));
                $pendingTickets->push(rand(0, 10));
                $closedTickets->push(rand(10, 30));
            }
        }

        return [
            'months' => $months->toArray(),
            'open' => $openTickets->toArray(),
            'pending' => $pendingTickets->toArray(),
            'closed' => $closedTickets->toArray(),
        ];
    }
}