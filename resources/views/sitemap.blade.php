{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ route('gym.biin') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('gym.king') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('kost') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('blogs.index') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('store.biin-king') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('gym.equipments.index') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>{{ route('survey.index') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

    <url>
        <loc>{{ route('legal') }}</loc>
        <lastmod>{{ now()->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- CONTOH UNTUK HALAMAN DINAMIS (BLOG/PRODUK) --}}
    {{-- Kalau kamu mau halaman detail blog ke-index, pastikan kamu mengirim variabel $blogs dari SitemapController --}}
    {{-- 
    @if(isset($blogs))
        @foreach($blogs as $blog)
        <url>
            <loc>{{ route('blogs.show', $blog->slug) }}</loc>
            <lastmod>{{ $blog->updated_at->tz('Asia/Jakarta')->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
        @endforeach
    @endif
    --}}
</urlset>