import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/home.css',
                'resources/css/login.css',
                'resources/css/register.css',
                'resources/css/pea.css',
                'resources/css/goal.css',
                'resources/css/goals.css',
                'resources/css/join.css',
                'resources/css/make.css',
                'resources/css/progress.css',
                'resources/css/situation.css',
                'resources/css/common.css',
                'resources/css/mypage.css',

                'resources/js/home.js',
                'resources/js/join.js',
                'resources/js/make.js',
                'resources/js/pea.js',
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
