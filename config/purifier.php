<?php

/**
 * Konfigurasi mews/purifier (SEC-11): sanitasi HTML dari RichEditor Filament
 * (Blog::content, TrainingProgram::description) sebelum dirender di halaman publik.
 *
 * Profil `default` mengizinkan tag yang dihasilkan RichEditor (judul, daftar, kutipan,
 * kode, tautan, gambar lampiran). Script, iframe, atribut on*, dan javascript: dibuang.
 *
 * @link http://htmlpurifier.org/live/configdoc/plain.html
 */

return [
    'encoding' => 'UTF-8',
    'finalize' => true,
    'ignoreNonStrings' => false,
    'cachePath' => storage_path('app/purifier'),
    'cacheFileMode' => 0755,

    'settings' => [
        'default' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => implode(',', [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'p', 'br', 'hr', 'div', 'span[style]',
                'strong', 'b', 'em', 'i', 'u', 's', 'del', 'strike', 'sub', 'sup',
                'a[href|title|target]',
                'ul', 'ol', 'li',
                'blockquote', 'pre', 'code',
                'img[src|alt|title|width|height]',
                'table', 'thead', 'tbody', 'tr', 'th', 'td',
            ]),
            'CSS.AllowedProperties' => 'font-weight,font-style,text-decoration,color,background-color,text-align',
            'Attr.AllowedFrameTargets' => ['_blank'],
            'HTML.TargetNoopener' => true,
            'HTML.TargetNoreferrer' => true,
            'URI.AllowedSchemes' => ['http' => true, 'https' => true, 'mailto' => true, 'tel' => true],
            // Jangan menambah <p> otomatis agar tampilan konten lama tidak berubah
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
        ],
    ],
];
