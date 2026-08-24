import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 'resources/js/app.js',
                'resources/css/common.css', 'resources/css/home.css',
                'resources/css/pea.css', 'resources/css/goals.css',
                'resources/css/join.css', 'resources/css/make.css',
                'resources/css/progress.css', 'resources/css/situation.css',
                'resources/css/goal-flow.css', 'resources/css/pair-waiting.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
