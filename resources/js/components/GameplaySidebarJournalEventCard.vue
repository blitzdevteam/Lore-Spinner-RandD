<script setup lang="ts">
defineProps<{
    isPassed: boolean;
}>()
</script>

<template>
    <div
        :class="isPassed
            ? 'border-gray-700/50 opacity-60'
            : 'border-secondary-400 bg-secondary-400/5'"
        class="border p-3 rounded-lg transition-all"
    >
        <div class="flex items-center gap-3">
            <span
                :class="isPassed
                    ? 'border-gray-600 bg-gray-600'
                    : 'border-secondary-400 bg-secondary-400'"
                class="rounded-full size-4 border-3 shrink-0"
            ></span>
            <div class="flex flex-col gap-0.75 flex-1">
                <h6
                    :class="isPassed ? 'text-gray-400' : 'text-gray-100'"
                    class="text-base"
                >Face the Shadow Within</h6>
                <p
                    :class="isPassed ? 'text-gray-500' : 'text-gray-300'"
                    class="text-sm font-light line-clamp-1"
                >Break the first bond before the night devours the sky</p>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
