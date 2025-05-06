<x-filament-widgets::widget>
    <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-primary-600 dark:text-primary-500">
                Laporan SLA FTTH - {{ $period }}
            </h2>
            <button wire:click="$refresh" class="p-1 text-primary-500 hover:bg-primary-100 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 p-2">
            <div class="bg-slate-50 dark:bg-gray-900/30 rounded-xl p-4 text-center shadow-sm">
                <div class="text-3xl font-bold text-slate-600 dark:text-slate-300">
                    {{ $totalTickets }}
                </div>
                <div class="text-sm mt-2 text-slate-500 dark:text-slate-400">Total Tiket</div>
            </div>
            
            <div class="bg-slate-50 dark:bg-gray-900/30 rounded-xl p-4 text-center shadow-sm">
                <div class="text-3xl font-bold text-slate-600 dark:text-slate-300">
                    {{ $closedTickets }}
                </div>
                <div class="text-sm mt-2 text-slate-500 dark:text-slate-400">Tiket Selesai</div>
            </div>
            
            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center shadow-sm">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                    {{ $meetingSla }}
                </div>
                <div class="text-sm mt-2 text-green-500 dark:text-green-400">Memenuhi SLA</div>
            </div>
            
            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center shadow-sm">
                <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                    {{ $exceedingSla }}
                </div>
                <div class="text-sm mt-2 text-red-500 dark:text-red-400">Melebihi SLA</div>
            </div>
            
            <div class="rounded-xl p-4 text-center shadow-sm {{ $compliancePercentage >= 99.5 ? 'bg-green-50 dark:bg-green-900/20' : 'bg-yellow-50 dark:bg-yellow-900/20' }}">
                <div class="text-3xl font-bold {{ $compliancePercentage >= 99.5 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                    {{ number_format($compliancePercentage, 2) }}%
                </div>
                <div class="text-sm mt-2 {{ $compliancePercentage >= 99.5 ? 'text-green-500 dark:text-green-400' : 'text-yellow-500 dark:text-yellow-400' }}">
                    Kepatuhan SLA
                </div>
                <div class="text-xs mt-1 text-slate-500 dark:text-slate-400">
                    Target: 99.5%
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>