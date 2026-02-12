<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        severity?: 'primary' | 'secondary' | 'secondary-muted-outline' | 'muted' | 'glass';
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

const getComponentTag = computed((): string | typeof Link => {
    switch (props.type) {
        case 'internal-link':
            return Link;
        case 'external-link':
            return 'a';
        case 'span':
            return 'span';
        case 'submit':
        case 'button':
        default:
            return 'button';
    }
});

const isDisabled = computed(() => props.disabled || props.processing);

const severityClass = computed(() => {
    const classes: Record<typeof props.severity, string> = {
        primary: 'bg-primary-400 text-black outline-primary-200/20',
        secondary: 'bg-secondary-400 text-black outline-secondary-400/30',
        'secondary-muted-outline': 'bg-secondary-300/20 text-black border border-secondary-300/75 text-secondary-300 outline-secondary-200/20',
        muted: 'bg-gray-900 text-gray-300 font-normal outline-gray-500/15',
        glass: 'bg-white/10 [background-blend-mode:plus-lighter,normal] shadow-[inset_0.25px_0.5px_0.5px_0.25px_rgba(255,255,255,0.22),inset_-0.2px_-0.5px_0.15px_0.5px_rgba(255,255,255,0.05)] drop-shadow-[0_4px_80px_rgba(0,0,0,0.20)] backdrop-blur-[3px]',
    };
    return classes[props.severity];
});

const getComponentClass = computed((): string => {
    const base = 'relative justify-center flex items-center transition-all';
    const rounded = props.iconOnly ? 'rounded-full grid place-items-center' : 'rounded-xl px-4';
    const size = props.iconOnly ? '!size-9' : 'h-12';

    if (isDisabled.value) {
        return `${base} ${size} ${severityClass.value} ${rounded} cursor-not-allowed opacity-60 outline-none pointer-events-none`;
    }

    const hoverClass = props.severity === 'glass'
        ? 'hover:scale-110 hover:bg-white/20'
        : 'hover:outline-4 focus:outline-4';

    return `${base} ${size} ${severityClass.value} ${rounded} cursor-pointer outline-0 ${hoverClass}`;
});

const emitHandleClick = (event: MouseEvent) => {
    if (!props.disabled && !props.processing) {
        emits('click', event);
    }
};

const emitHandleSubmit = (event: SubmitEvent) => {
    if (!props.disabled && !props.processing) {
        emits('submit', event);
    }
};
</script>

<template>
    <component
        :is="getComponentTag"
        :href="['internal-link', 'external-link'].includes(props.type) ? props.href : undefined"
        :class="getComponentClass"
        :disabled="['submit', 'button'].includes(type) && isDisabled"
        @click="emitHandleClick"
        @submit="['submit', 'button'].includes(type) ? emitHandleSubmit : undefined"
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
