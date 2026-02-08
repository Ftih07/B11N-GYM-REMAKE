    <footer class="w-full bg-neutral-900/95 backdrop-blur-sm border-t border-white/5 py-6">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">

            <p class="text-gray-500 text-xs text-center md:text-left">
                Copyright &copy; {{ date('Y') }} <span class="text-red-600 font-semibold">B1NG EMPIRE</span>. All rights reserved.
            </p>

            <div class="flex items-center gap-6">
                <a href="{{ route('legal') }}" class="hover:text-primary transition-colors">Privacy Policy</a>
                <a href="{{ route('legal') }}" class="hover:text-primary transition-colors">Terms</a>
            </div>

        </div>
    </footer>