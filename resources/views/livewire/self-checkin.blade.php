<div class="card">

    <header class="head">
        <img class="logo" src="{{ asset($theme['logo']) }}" alt="Logo {{ $gymName }}">
        <div>
            <p class="eyebrow">Absen Mandiri</p>
            <h1>{{ $gymName }}</h1>
            <p class="tagline">{{ $theme['tagline'] }}</p>
        </div>
    </header>

    {{-- ============ LANGKAH 1: PILIH MEMBER / BUKAN ============ --}}
    @if ($step === 'choose')
        <section wire:key="step-choose">
            <p class="lead">Silakan pilih:</p>

            <button type="button" class="big big-primary" wire:click="chooseMember">
                Saya Member
            </button>

            <button type="button" class="big" wire:click="chooseVisitor">
                Bukan Member
                <small>Harian / Mingguan</small>
            </button>
        </section>

        {{-- ============ LANGKAH 2A: FORM MEMBER ============ --}}
    @elseif ($step === 'member')
        <section wire:key="step-member">
            <h2>Absen Member</h2>

            <form wire:submit="submitMember" novalidate>
                @error('form')
                    <p class="err">{{ $message }}</p>
                @enderror

                <label class="field" for="nickname">Nama Panggilan</label>
                <input id="nickname" type="text" wire:model="nickname" maxlength="40" autocomplete="off"
                    autocapitalize="words" placeholder="Contoh: Budi">
                @error('nickname')
                    <p class="err">{{ $message }}</p>
                @enderror

                <label class="field" for="memberCode">Member ID</label>
                <input id="memberCode" type="text" wire:model="memberCode" maxlength="20" autocomplete="off"
                    autocapitalize="characters" spellcheck="false" placeholder="Contoh: B-00123">
                <p class="hint">Huruf B atau K, lalu angka. Contoh: B-00123 atau K-02541.</p>
                @error('memberCode')
                    <p class="err">{{ $message }}</p>
                @enderror

                <button type="submit" class="big big-primary" wire:loading.attr="disabled" wire:target="submitMember">
                    <span wire:loading.remove wire:target="submitMember">Absen Sekarang</span>
                    <span wire:loading wire:target="submitMember">Memproses…</span>
                </button>

                <button type="button" class="link" wire:click="backToStart">← Kembali</button>
            </form>
        </section>

        {{-- ============ LANGKAH 2B: FORM TAMU ============ --}}
    @elseif ($step === 'visitor')
        <section wire:key="step-visitor">
            <h2>Absen Tamu</h2>

            <form wire:submit="submitVisitor" novalidate>
                @error('form')
                    <p class="err">{{ $message }}</p>
                @enderror

                <label class="field" for="visitorName">Nama</label>
                <input id="visitorName" type="text" wire:model="visitorName" maxlength="60" autocomplete="off"
                    autocapitalize="words" placeholder="Nama Anda">
                @error('visitorName')
                    <p class="err">{{ $message }}</p>
                @enderror

                <p class="group-title">Paket Kunjungan</p>
                <div class="choices">
                    <label class="choice">
                        <input type="radio" wire:model="visitType" value="daily">
                        <span>Harian</span>
                    </label>
                    <label class="choice">
                        <input type="radio" wire:model="visitType" value="weekly">
                        <span>Mingguan</span>
                    </label>
                </div>
                @error('visitType')
                    <p class="err">{{ $message }}</p>
                @enderror

                <p class="group-title">Pembayaran</p>
                <div class="choices">
                    @foreach ($paymentMethods as $value => $label)
                        <label class="choice">
                            <input type="radio" wire:model="paymentMethod" value="{{ $value }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('paymentMethod')
                    <p class="err">{{ $message }}</p>
                @enderror

                <button type="submit" class="big big-primary" wire:loading.attr="disabled" wire:target="submitVisitor">
                    <span wire:loading.remove wire:target="submitVisitor">Absen Sekarang</span>
                    <span wire:loading wire:target="submitVisitor">Memproses…</span>
                </button>

                <button type="button" class="link" wire:click="backToStart">← Kembali</button>
            </form>
        </section>

        {{-- ============ HASIL ============ --}}
    @else
        <section wire:key="step-result" class="result {{ $resultType }}" x-data="{ t: null, init() { this.t = setTimeout(() => $wire.finish(), 20000) }, destroy() { clearTimeout(this.t) } }">
            <div class="icon" aria-hidden="true">
                @if ($resultType === 'success')
                    ✓
                @elseif ($resultType === 'warning')
                    !
                @else
                    ✕
                @endif
            </div>

            <h2>{{ $resultTitle }}</h2>
            <p>{{ $resultText }}</p>

            <button type="button" class="big big-primary" wire:click="finish">Selesai</button>
        </section>
    @endif

    <p class="foot">Ada kendala? Tanyakan ke kasir.</p>
</div>
