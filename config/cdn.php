<?php

/*
| Pustaka front-end pihak ketiga (SEC-12). Versi dikunci dan diberi Subresource Integrity:
| bila isi berkas di CDN berubah, browser menolak memuatnya. Dipakai lewat <x-cdn name="..." />.
|
| Mengganti versi: ubah url, lalu hitung ulang hash:
|   curl -s URL | openssl dgst -sha384 -binary | openssl base64 -A
| Berkas `local` di-host sendiri di public/ (Tailwind Play CDN tidak mendukung CORS sehingga SRI tidak bisa dipakai).
*/

return [
    'tailwind' => [
        'type' => 'js',
        'local' => 'js/vendor/tailwindcss-3.4.17.js',
    ],
    'alpine' => [
        'type' => 'js',
        'url' => 'https://cdn.jsdelivr.net/npm/alpinejs@3.17.5/dist/cdn.min.js',
        'integrity' => 'sha384-FYLKjwG3aohNhNZoVG8sY/5ntunm+MqylL90Q+rT3OM7AwV9/BKFjk/RjoIpIgsI',
    ],
    'bootstrap-css' => [
        'type' => 'css',
        'url' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css',
        'integrity' => 'sha384-yEPZ7jP9S0QKiUQ0m3jjsO4ProIW7wvR9gXkI7JOGa2XVsw53Yke2bsdkBbjmmiX',
    ],
    'bootstrap-js' => [
        'type' => 'js',
        'url' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/js/bootstrap.bundle.min.js',
        'integrity' => 'sha384-WyOxWvTyr1QaMPfiPJSv76iReVthorl5uWlFtZPyK0IEB0hGzo9y5Bp9mYanr8Bu',
    ],
    'swiper-css' => [
        'type' => 'css',
        'url' => 'https://cdn.jsdelivr.net/npm/swiper@11.2.10/swiper-bundle.min.css',
        'integrity' => 'sha384-gAPqlBuTCdtVcYt9ocMOYWrnBZ4XSL6q+4eXqwNycOr4iFczhNKtnYhF3NEXJM51',
    ],
    'swiper-js' => [
        'type' => 'js',
        'url' => 'https://cdn.jsdelivr.net/npm/swiper@11.2.10/swiper-bundle.min.js',
        'integrity' => 'sha384-2UI1PfnXFjVMQ7/ZDEF70CR943oH3v6uZrFQGGqJYlvhh4g6z6uVktxYbOlAczav',
    ],
    'remixicon3' => [
        'type' => 'css',
        'url' => 'https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css',
        'integrity' => 'sha384-2+8OcleUOxnP/2cYXK9FBoMe12ojS0iPrrJy9Um4ICFk2AXl8iODUaIoUKI6TmGj',
    ],
    'remixicon4' => [
        'type' => 'css',
        'url' => 'https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css',
        'integrity' => 'sha384-3IfPN7bUY9t2yZyfTCGYPxkgBPMK8qRk4VZqiW2ViOlKVGxkPJf22+zllRYbDrVj',
    ],
    'fontawesome5' => [
        'type' => 'css',
        'url' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        'integrity' => 'sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm',
    ],
    'fontawesome6' => [
        'type' => 'css',
        'url' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        'integrity' => 'sha384-iw3OoTErCYJJB9mCa8LNS2hbsQ7M3C0EpIsO/H5+EGAkPGc6rk+V8i04oW/K5xq0',
    ],
];
