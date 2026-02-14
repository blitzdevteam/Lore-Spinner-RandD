<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        severity?: 'primary' | 'secondary' | 'secondary-muted-outline' | 'muted' | 'glass' | 'transparent';
        iconOnly?: boolean;
        type?: 'internal-link' | 'external-link' | 'button' | 'submit' | 'span';
        href?: string;
        processing?: boolean;
        disabled?: boolean;
    }>(),
    {
        severity: 'primary',
        type: 'button',
        iconOnly: false,
        href: '',
        processing: false,
        disabled: false,
    },
);

const emits = defineEmits<{
    (e: 'click', ev: MouseEvent): void;
    (e: 'submit', ev: SubmitEvent): void;
}>();

const componentTagMap: Record<typeof props.type, string | typeof Link> = {
    'internal-link': Link,
    'external-link': 'a',
    'span': 'span',
    'submit': 'button',
    'button': 'button',
};

const getComponentTag = computed((): string | typeof Link => componentTagMap[props.type]);

const isDisabled = computed(() => props.disabled || props.processing);

const severityClass = computed(() => {
    const classes: Record<typeof props.severity, string> = {
        primary: 'bg-primary-400 text-black outline-primary-200/20',
        secondary: 'bg-secondary-400 text-black outline-secondary-400/30',
        'secondary-muted-outline': 'bg-secondary-300/20 text-black border border-secondary-300/75 text-secondary-300 outline-secondary-200/20',
        muted: 'bg-gray-900 text-gray-300 font-normal outline-gray-500/15',
        glass: 'bg-white/10 [background-blend-mode:plus-lighter,normal] shadow-[inset_0.25px_0.5px_0.5px_0.25px_rgba(255,255,255,0.22),inset_-0.2px_-0.5px_0.15px_0.5px_rgba(255,255,255,0.05)] drop-shadow-[0_4px_80px_rgba(0,0,0,0.20)] backdrop-blur-[3px]',
        transparent: 'bg-transparent text-primary outline-transparent',
    };
    return classes[props.severity];
});

const hoverClass = computed(() => {
    if (props.severity === 'glass') return 'hover:scale-110 hover:bg-white/20';
    if (props.severity === 'transparent') return 'hover:bg-primary-50/10 hover:outline-primary-200/30';
    return 'hover:outline-4 focus:outline-4';
});

const getComponentClass = computed((): string => {
    const base = 'relative justify-center flex items-center transition-all';
    const rounded = props.iconOnly ? 'rounded-full grid place-items-center' : 'rounded-xl px-4';
    const size = props.iconOnly ? '!size-9' : 'h-12';

    if (isDisabled.value) {
        return `${base} ${size} ${severityClass.value} ${rounded} cursor-not-allowed opacity-60 outline-none pointer-events-none`;
    }

    return `${base} ${size} ${severityClass.value} ${rounded} cursor-pointer outline-0 ${hoverClass.value}`;
});

const emitHandleClick = (event: MouseEvent) => {
    if (!isDisabled.value) {
        emits('click', event);
    }
};

const emitHandleSubmit = (event: SubmitEvent) => {
    if (!isDisabled.value) {
        emits('submit', event);
    }
};

const isLink = computed(() => props.type === 'internal-link' || props.type === 'external-link');
const isButtonType = computed(() => props.type === 'submit' || props.type === 'button');
</script>

<template>
    <component
        :is="getComponentTag"
        :href="isLink ? props.href : undefined"
        :class="getComponentClass"
        :disabled="isButtonType && isDisabled"
        @click="emitHandleClick"
        @submit="isButtonType ? emitHandleSubmit : undefined"
    >
        <template v-if="processing">
            <LoaderCircle class="animate-spin opacity-50" />
        </template>
        <template v-else>
            <slot></slot>
        </template>
    </component>
</template>

<style scoped lang="scss"></style>
