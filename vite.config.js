import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    resolve: {
        alias: {
            '@/*': path.resolve('resources/js/*'),
            'ziggy-js': path.resolve('vendor/tightenco/ziggy/')
        }
    },
    plugins: [
        laravel({
            input: [ 'resources/css/app.css', 'resources/js/app.js' ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
