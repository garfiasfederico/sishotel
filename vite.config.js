import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/css/output.css',
                'resources/css/flowbite.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve :{
        alias:{
            '$':'jQuery'
        }
    }
});
