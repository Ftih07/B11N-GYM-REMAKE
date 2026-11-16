<x-filament-widgets::widget>
    <style>
        .sa-insights { list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem; }
        .sa-insights li {
            display: flex; gap: .75rem; align-items: flex-start;
            padding: .625rem .875rem; border-radius: .5rem;
            font-size: .9rem; line-height: 1.45;
            color: rgb(var(--gray-800));
            background: rgba(var(--gray-500), .08);
        }
        .sa-insights .sa-dot {
            flex: none; width: .5rem; height: .5rem; margin-top: .5rem;
            border-radius: 9999px; background: rgb(var(--primary-500));
        }
        .dark .sa-insights li {
            color: rgb(var(--gray-100));
            background: rgba(255, 255, 255, .05);
        }
        @media (min-width: 1024px) {
            .sa-insights { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
    </style>

    <x-filament::section heading="Analisis Singkat" icon="heroicon-o-light-bulb">
        <ul class="sa-insights">
            @forelse ($insights as $i)
                <li>
                    <span class="sa-dot"></span>
                    <span>{{ $i }}</span>
                </li>
            @empty
                <li>Belum ada data pada periode ini.</li>
            @endforelse
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>