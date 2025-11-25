import { definePreset } from '@primeuix/themes';
import Aura from '@primeuix/themes/aura';
import AuraTheme from './theme.js';

const customPreset = definePreset(Aura, AuraTheme);

export default {
    theme: {
        preset: customPreset,
        options: {
            darkModeSelector: 'dark',
        },
    },
    inputVariant: 'filled',
};
