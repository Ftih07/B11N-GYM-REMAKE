<x-filament-panels::page>
    @php
        $recap = $this->getRecap();
        $names = \App\Filament\Pages\RenewalRecap::monthNames();
    @endphp

    <x-filament::section>
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button wire:click="export" icon="heroicon-o-arrow-down-tray" color="info">
                Export Excel
            </x-filament::button>
        </div>
    </x-filament::section>

    <x-filament::section>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <th class="px-3 py-2 border border-gray-200 dark:border-white/10">Kode</th>
                        <th class="px-3 py-2 border border-gray-200 dark:border-white/10">Berlaku Sampai</th>
                        <th class="px-3 py-2 border border-gray-200 dark:border-white/10">Nama</th>
                        <th class="px-3 py-2 border border-gray-200 dark:border-white/10">No. HP</th>
                        <th class="px-3 py-2 border border-gray-200 dark:border-white/10">Tgl Join</th>
                        @foreach ($recap['months'] as $m)
                            <th class="px-3 py-2 border border-gray-200 dark:border-white/10 text-center">{{ $names[$m] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recap['rows'] as $row)
                        <tr>
                            <td class="px-3 py-2 border border-gray-200 dark:border-white/10 whitespace-nowrap">{{ $row['code'] ?? '-' }}</td>
                            <td class="px-3 py-2 border border-gray-200 dark:border-white/10 whitespace-nowrap">{{ $row['end'] ?? '-' }}</td>
                            <td class="px-3 py-2 border border-gray-200 dark:border-white/10 whitespace-nowrap">{{ $row['name'] }}</td>
                            <td class="px-3 py-2 border border-gray-200 dark:border-white/10 whitespace-nowrap">{{ $row['phone'] ?? '-' }}</td>
                            <td class="px-3 py-2 border border-gray-200 dark:border-white/10 whitespace-nowrap">{{ $row['join'] ?? '-' }}</td>
                            @foreach ($recap['months'] as $m)
                                <td class="px-3 py-2 border border-gray-200 dark:border-white/10 text-center whitespace-nowrap">
                                    @if (! empty($row['cells'][$m]))
                                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-md bg-success-50 text-success-700 ring-1 ring-inset ring-success-600/20 dark:bg-success-900/30 dark:text-success-400">{{ $row['cells'][$m] }}</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 5 + count($recap['months']) }}" class="px-3 py-6 text-center text-gray-500">
                                Belum ada perpanjangan tercatat untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
