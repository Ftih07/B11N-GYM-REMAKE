<x-filament-widgets::widget>
    <x-filament::section :heading="$sectionTitle ?? 'Heatmap Jam Ramai Transaksi'"
        :description="$sectionDesc ?? 'Jumlah transaksi berstatus paid per hari dan jam, dipisah per cabang. Semakin pekat warnanya, semakin ramai.'">
        <div class="space-y-8">
            @forelse ($heatmaps as $hm)
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-950 dark:text-white">
                        {{ $hm['name'] }}
                        <span class="ml-1 font-normal text-gray-500">({{ number_format($hm['total']) }} {{ $unit ?? 'transaksi' }})</span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="border-separate border-spacing-1 text-xs">
                            <thead>
                                <tr>
                                    <th></th>
                                    @foreach ($hours as $h)
                                        <th class="w-8 font-normal text-gray-500">{{ sprintf('%02d', $h) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($days as $i => $day)
                                    <tr>
                                        <td class="pr-2 text-gray-500">{{ $day }}</td>
                                        @foreach ($hours as $h)
                                            @php
                                                $v = $hm['matrix'][$i][$h] ?? 0;
                                                $ratio = $hm['max'] ? $v / $hm['max'] : 0;
                                                $alpha = $v ? max(0.15, $ratio) : 0.06;
                                            @endphp
                                            <td title="{{ $day }} {{ sprintf('%02d', $h) }}:00 — {{ $v }} {{ $unit ?? 'transaksi' }}"
                                                class="h-8 w-8 rounded text-center"
                                                style="background-color: rgba({{ $rgb ?? '245,158,11' }},{{ $alpha }}); color: {{ $ratio > 0.6 ? '#fff' : 'inherit' }}">
                                                {{ $v ?: '' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada data.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>