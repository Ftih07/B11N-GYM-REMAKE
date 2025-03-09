import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [laravel([
        'resources/css/app.css',
        'resources/css/blog.css',
        'resources/css/home.css',
        'resources/css/index.css',
        'resources/css/kinggym.css',
        'resources/css/kost.css',
        'resources/js/app.js'
    ])],
});
