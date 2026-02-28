import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                //CARPETAS JS
                //clients
                'resources/js/clients/createClient.js',
                'resources/js/clients/editClient.js',
                'resources/js/clients/showClient.js',
                //contracts
                'resources/js/contracts/changeStateContract.js',
                'resources/js/contracts/createContract.js',
                'resources/js/contracts/editContract.js',
                //debts
                'resources/js/debts/showDebts.js',
                //payments
                'resources/js/payments/makePayment.js',
                'resources/js/payments/nullPayment.js',
                'resources/js/payments/showReceipt.js',
                //settings
                'resources/js/settings/services/createService.js',
                'resources/js/settings/services/editService.js',
                'resources/js/settings/additional-services/createAddService.js',
                'resources/js/settings/additional-services/editAddService.js',
                //zones
                'resources/js/zones/createZone.js',
                'resources/js/zones/editZone.js',
            ],
            refresh: true,
        }),
    ],
});
