import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/seller/products.js',
                'resources/js/buyer/buyer.js',
                'resources/js/cart/cart.js'
            ],
            refresh: true,
        }),
    ],
});
