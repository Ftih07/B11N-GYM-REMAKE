{{--
    Cek berkala apakah admin sudah menautkan email akun ini ke data member (PERF-02).
    Dulu: tiap 3 detik tanpa batas. Kini: tiap 15 detik selama maks. 5 menit, dijeda saat tab
    tidak terlihat; setelah itu pengguna cukup memuat ulang halaman.
--}}
<script>
    (() => {
        const INTERVAL_MS = 15000;
        const MAX_CHECKS = 20; // 20 x 15 detik = 5 menit
        let checks = 0;

        const timer = setInterval(() => {
            if (document.hidden) return;
            if (++checks > MAX_CHECKS) return clearInterval(timer);

            fetch('/api/check-membership-status', { headers: { 'Accept': 'application/json' } })
                .then(response => response.json())
                .then(data => {
                    if (data.status === true) {
                        clearInterval(timer);
                        window.location.reload();
                    }
                })
                .catch(() => {});
        }, INTERVAL_MS);
    })();
</script>
