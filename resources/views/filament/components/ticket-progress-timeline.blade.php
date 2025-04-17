<div>
    @if(empty($actions) || (is_object($actions) && $actions->isEmpty()))
        <div class="text-center p-4">Belum ada data progress.</div>
    @else
        <div class="space-y-4">
            @foreach($actions->sortByDesc('created_at') as $action)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="px-2 py-1 text-xs font-medium rounded 
                            @switch($action->action_type)
                                @case('Open Clock')
                                    bg-blue-100 text-blue-800
                                @break
                                @case('Pending Clock')
                                    bg-yellow-100 text-yellow-800
                                @break
                                @case('Start Clock')
                                    bg-green-100 text-green-800
                                @break
                                @case('Completed')
                                    bg-gray-100 text-gray-800
                                @break
                                @default
                                    bg-gray-100 text-gray-800
                            @endswitch
                        ">
                            {{ $action->action_type }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $action->created_at->format('d M Y H:i') }}
                        </span>
                    </div>
                    <pre class="text-sm mb-2 bg-white p-3 rounded-lg border overflow-x-auto whitespace-pre-wrap break-words">{{ $action->description }}</pre>
                    <div class="text-xs text-gray-500 flex justify-between">
                        <span>oleh: {{ $action->user?->name ?? 'System' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>