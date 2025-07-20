import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/filament/admin/theme.css'],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [tailwindcss()],
        },
    },
    build: {
        outDir: 'public/build/filament',
    },
});
