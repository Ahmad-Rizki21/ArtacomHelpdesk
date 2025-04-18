<div class="space-y-4">
    @forelse($actions as $action)
        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
            <div class="p-3 flex items-center">
                <span class="
                    @switch($action->action_type)
                        @case('Open Clock')
                            bg-blue-600
                        @break
                        @case('Pending Clock')
                            bg-yellow-600
                        @break
                        @case('Start Clock')
                            bg-green-600
                        @break
                        @case('Completed')
                            bg-gray-600
                        @break
                        @default
                            bg-purple-600
                    @endswitch
                    text-white px-2.5 py-1 text-xs font-medium rounded">
                    {{ $action->action_type }}
                </span>
                <span class="ml-auto text-gray-400 text-sm">
                    {{ $action->created_at->format('d M Y H:i:s') }}
                </span>
            </div>
            
            <div class="px-4 py-3 bg-gray-900 border-t border-gray-700">
                <pre class="font-mono text-white text-sm whitespace-pre-wrap">{{ $action->description }}</pre>
            </div>
            
            <div class="p-2 bg-gray-800 border-t border-gray-700 text-right">
                <span class="text-gray-400 text-xs">oleh: {{ $action->user?->name ?? 'System' }}</span>
            </div>
        </div>
    @empty
        <div class="text-center p-4 text-gray-400">Belum ada data progress.</div>
    @endforelse
</div>