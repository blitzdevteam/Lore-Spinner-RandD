<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="flex flex-col items-center gap-3 font-light">
        <h3 class="text-2xl font-semibold uppercase md:text-4xl">{{ title }}</h3>
        <p class="max-w-4xl text-center text-base text-gray-200 md:text-xl" v-if="$slots.description">
            <slot name="description" />
        </p>
    </div>
</template>

<style scoped></style>
