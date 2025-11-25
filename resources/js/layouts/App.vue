<template>
    <Toaster position="top-center" />
    <slot />
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, watch } from 'vue';
import { Toaster, toast } from 'vue-sonner'
import 'vue-sonner/style.css';

const page = usePage();

const flash = computed(() => page.props.flash);

onMounted(() => {

    watch(
        flash,
        () => {
            const options = {
                closeButton: false,
            };
            const { success, error, warning } = flash.value as FlashInterface;

            nextTick(() => {
                success?.forEach((message) => toast.success(message, options));
                error?.forEach((message) => toast.error(message, options));
                warning?.forEach((message) => toast.warning(message, options));
            });
        },
        {
            immediate: true,
            deep: true,
        },
    );
});
</script>

<style scoped></style>
