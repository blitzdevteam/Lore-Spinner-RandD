<template>
    <component
        :is="getComponentTag"
        :href="['internal-link', 'external-link'].includes(props.type) ? props.href : undefined"
        :class="getComponentClass"
        :disabled="type === 'button' && processing"
        @click="emitHandleClick"
        @submit="type === 'button' ? emitHandleSubmit : undefined"
    >
        <template v-if="processing">
            <LoaderCircle class="animate-spin opacity-50" />
        </template>
        <template v-else>
            <slot></slot>
        </template>
    </component>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LoaderCircle } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        severity?: 'primary' | 'secondary' | 'secondary-muted-outline' | 'muted';
        iconOnly?: boolean;
        type?: 'internal-link' | 'external-link' | 'button' | 'span';
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
        case 'button':
        default:
            return 'button';
    }
});

const getComponentClass = computed((): string => {
    const severityClass = {
        primary: 'bg-primary-400 text-black font-light outline-primary-200/20',
        secondary: 'bg-secondary-400 text-black font-light outline-secondary-400/30',
        'secondary-muted-outline':
            'bg-secondary-300/20 text-black border border-secondary-300/75 text-secondary-300 font-light outline-secondary-200/20',
        muted: 'bg-gray-900 text-gray-300 font-light outline-gray-500/15',
    }[props.severity];

    const roundedClass = props.iconOnly ? 'w-12 grid place-items-center' : ' px-4';

    const notAllowedClass =
        props.type === 'button' && (props.disabled || props.processing)
            ? 'cursor-not-allowed opacity-60 outline-none'
            : 'cursor-pointer outline-0 opacity-100 hover:outline-4 focus:outline-4';

    return `relative font-medium justify-center flex items-center transition-all rounded-md h-12 ${severityClass} ${roundedClass} ${notAllowedClass}`;
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

<style scoped lang="scss"></style>
