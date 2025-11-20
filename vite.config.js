import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import mkcert from 'vite-plugin-mkcert';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        mkcert(), // Habilita la generación de certificados SSL
    ],
    server: {
        https: true, // Fuerza a Vite a usar HTTPS
        host: 'tallerdevictor.com', // El dominio que tu navegador usa
        hmr: {
            host: 'tallerdevictor.com',
            protocol: 'wss' // Protocolo de websocket seguro
        },
        // ¡La solución está aquí!
        cors: true,
    }
});
