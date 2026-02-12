import { definePreset } from '@primeuix/themes';
import Aura from '@primeuix/themes/aura';
import AuraTheme from './theme.js';

const customPreset = definePreset(Aura, {
    ...AuraTheme,
    semantic: {
        formField: {
            paddingX: '0.85rem',
            paddingY: '0.85rem',
        },
        colorScheme: {
            light: {
                formField: {
                    borderRadius: '{border.radius.lg}',
                }
            },
            dark: {
                formField: {
                    borderRadius: '{border.radius.lg}',
                    filledBackground: '{gray.700}',
                    filledHoverBackground: '{gray.800}',
                    backgroundBlendMode: 'plus-lighter, normal',
                    shadow: '0.25px 0.5px 0.5px 0.25px rgba(255, 255, 255, 0.22) inset, -0.2px -0.5px 0.15px 0.5px rgba(255, 255, 255, 0.05) inset',
                    backdropFilter: 'blur(3px)',
                    borderColor: '#ffffff00'
                }
            }
        }
    }
});

export default {
    theme: {
        preset: customPreset,
        options: {
            darkModeSelector: '.dark',
        },
    },
    inputVariant: 'filled',
};
