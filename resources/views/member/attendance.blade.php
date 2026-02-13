<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi - B1NG Empire</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        header: ['Oswald', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        dark: '#0a0a0a',
                        card: '#171717',
                        primary: '#DC2626',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-dark text-gray-200 font-body min-h-screen flex flex-col">
    
    @include('components.navbar')

    <div class="container mx-auto p-4 md:p-6 max-w-5xl flex-grow">

        <div class="flex items-center gap-3 mb-6 md:mb-8 border-b border-neutral-800 pb-4">
            <div class="bg-primary p-2 rounded shadow-lg shadow-red-900/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="font-header text-2xl md:text-3xl font-bold text-white uppercase tracking-wide">
                Riwayat <span class="text-primary">Kehadiran</span>
            </h2>
        </div>

        {{-- LOGIC CEK MEMBER --}}
        @if($member)

        <div class="block md:hidden space-y-4">
            @forelse($attendances as $row)
            <div class="bg-card border border-neutral-800 rounded-xl p-4 flex items-center justify-between shadow-lg relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>

                <div class="flex items-center gap-4 pl-2">
                    <div class="bg-neutral-900 border border-neutral-800 rounded-lg p-2 w-16 text-center flex flex-col justify-center">
                        <span class="font-header text-2xl font-bold text-white leading-none">
                            {{ \Carbon\Carbon::parse($row->check_in_time)->format('d') }}
                        </span>
                        <span class="text-[10px] uppercase text-gray-500 font-bold tracking-wider">
                            {{ \Carbon\Carbon::parse($row->check_in_time)->format('M') }}
                        </span>
                        <span class="text-[10px] text-gray-600">
                            {{ \Carbon\Carbon::parse($row->check_in_time)->format('Y') }}
                        </span>
                    </div>

                    <div>
                        <h4 class="font-header text-lg font-bold text-gray-100 uppercase tracking-wide mb-0.5">
                            {{ $row->visit_type }}
                        </h4>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($row->check_in_time)->format('H:i') }} WIB
                        </div>
                    </div>
                </div>

                <div>
                    @if($row->method == 'face_scan')
                    <div class="flex flex-col items-center justify-center bg-purple-900/20 border border-purple-900/50 rounded p-2 w-12 h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 14.5v.01M12 18.5v.01M12 10.5v.01M16 14.5v.01M16 18.5v.01M16 10.5v.01M8 14.5v.01M8 18.5v.01M8 10.5v.01" />
                        </svg>
                        <span class="text-[8px] font-bold text-purple-300 uppercase">QR</span>
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center bg-blue-900/20 border border-blue-900/50 rounded p-2 w-12 h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="text-[8px] font-bold text-blue-300 uppercase">MAN</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-card border border-neutral-800 border-dashed rounded-xl p-8 text-center">
                <p class="text-gray-500">Belum ada riwayat absensi.</p>
            </div>
            @endforelse
        </div>

        <div class="hidden md:block bg-card border border-neutral-800 rounded-lg shadow-2xl overflow-hidden">
            <table class="w-full text-left text-gray-400">
                <thead class="bg-neutral-900 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-5 font-header font-bold">Waktu Check-in</th>
                        <th class="p-5 font-header font-bold">Metode</th>
                        <th class="p-5 font-header font-bold">Tipe Kunjungan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800">
                    @forelse($attendances as $row)
                    <tr class="hover:bg-neutral-800/50 transition">
                        <td class="p-5 text-white font-medium text-lg">
                            {{ \Carbon\Carbon::parse($row->check_in_time)->format('d M Y') }}
                            <span class="block text-sm text-gray-500 font-normal">
                                Pukul {{ \Carbon\Carbon::parse($row->check_in_time)->format('H:i') }} WIB
                            </span>
                        </td>
                        <td class="p-5">
                            @if($row->method == 'face_scan')
                            <span class="bg-purple-900/50 text-purple-300 border border-purple-800 text-xs font-bold px-3 py-1 rounded uppercase tracking-wider">
                                FACE SCAN
                            </span>
                            @else
                            <span class="bg-blue-900/50 text-blue-300 border border-blue-800 text-xs font-bold px-3 py-1 rounded uppercase tracking-wider">
                                MANUAL
                            </span>
                            @endif
                        </td>
                        <td class="p-5 font-bold text-gray-300 uppercase font-header tracking-wide">{{ $row->visit_type }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-12 text-center border-dashed border-gray-700">
                            <p class="text-gray-500 text-lg">Belum ada data absensi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($attendances, 'links'))
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
        @endif

        @else
        {{-- ALERT JIKA BUKAN MEMBER --}}
        <div class="text-center bg-yellow-900/20 border border-yellow-700/50 p-6 rounded-lg mt-6">
            <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-yellow-500 font-bold mb-1">DATA MEMBER TIDAK DITEMUKAN</p>
            <p class="text-gray-400 text-sm">Email ini belum terdaftar di sistem gym kami. Hubungi resepsionis untuk aktivasi.</p>
        </div>
        @endif

    </div>

    @include('components.footer-compact')

</body>

</html>