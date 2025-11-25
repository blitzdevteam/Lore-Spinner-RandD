<template>
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2" v-if="label">
            <Asterisk v-if="required" class="w-4 text-red-500" />
            <p class="text-white text-base">{{ label }}</p>
        </div>
        <slot></slot>
        <div v-if="error">
            <p class="text-xs text-red-500">{{ error }}</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Asterisk } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        label?: string;
        required?: boolean;
        error?: string;
    }>(),
    {
        label: '',
        required: true,
        error: '',
    },
);
</script>

<style scoped lang="scss"></style>
