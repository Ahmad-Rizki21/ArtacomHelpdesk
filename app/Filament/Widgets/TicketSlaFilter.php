<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class TicketSlaFilter extends Widget
{
    protected static string $view = 'filament.widgets.ticket-sla-filter';
    
    public $year;
    public $month;
    public $isFiltered = false;
    
    public function mount()
    {
        $this->year = date('Y');
        $this->month = date('m');
    }
    
    protected function getFormSchema(): array
    {
        return [
            Split::make([
                Section::make([
                    TextInput::make('title')
                        ->label('Filter Berdasarkan Periode')
                        ->disabled()
                        ->default('Laporan SLA FTTH')
                        ->placeholder('Masukkan judul laporan'),
                        
                    TextInput::make('period')
                        ->label('Periode')
                        ->disabled()
                        ->default(Carbon::createFromDate($this->year, $this->month, 1)->translatedFormat('F Y')),
                ]),
                Section::make([
                    Toggle::make('is_ftth_only')
                        ->label('Hanya FTTH')
                        ->default(true),
                        
                    Toggle::make('include_details')
                        ->label('Tampilkan Detail')
                        ->default(false),
                ])->grow(false),
            ])->from('md'),
        ];
    }
    
    public function filter()
    {
        $this->isFiltered = true;
        $this->emit('slaFilterChanged', [
            'year' => $this->year,
            'month' => $this->month,
        ]);
    }
    
    public function resetFilter()
    {
        $this->year = date('Y');
        $this->month = date('m');
        $this->isFiltered = false;
        $this->emit('slaFilterReset');
    }
}