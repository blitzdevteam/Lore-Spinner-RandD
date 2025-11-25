// CSS
import '../css/app.css';
import '../css/fonts/sf-pro-display/sf-pro-display.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';

// Primevue
import PrimeVue from 'primevue/config';
import customPresetOptions from '../css/primevue-theme/aura/options';

// Primevue Component
import Checkbox from 'primevue/checkbox';
import Divider from 'primevue/divider';
import InputMask from 'primevue/inputmask';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Textarea from 'primevue/textarea';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)

            // Primevue
            .use(PrimeVue, customPresetOptions)

            // Primevue Components
            .component('PrimeInputText', InputText)
            .component('PrimeInputMask', InputMask)
            .component('PrimeTextarea', Textarea)
            .component('PrimeCheckbox', Checkbox)
            .component('PrimeSelect', Select)
            .component('PrimeDivider', Divider)

            .mount(el);
    },
});
