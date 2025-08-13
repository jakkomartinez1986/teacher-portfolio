import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'resources/js/**/*.js',
                'resources/css/**/*.css'
            ],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        hmr: {
            host: 'localhost',
        },
    },
    resolve: {
        alias: {
            // Eliminamos el alias de fullcalendar que causaba problemas
            // En su lugar, lo importaremos directamente en app.js
        }
    },
    optimizeDeps: {
        include: [
            '@fullcalendar/core',
            '@fullcalendar/daygrid',
            '@fullcalendar/interaction',
            '@fullcalendar/timegrid',
            '@fullcalendar/list'
        ],
    },
    build: {
        commonjsOptions: {
            include: [
                /node_modules/,
                /\.js$/
            ],
        }
    }
});