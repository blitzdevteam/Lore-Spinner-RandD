<script setup lang="ts">
defineProps<{
    title: string;
    objective: string | null;
    isCurrent: boolean;
}>();
</script>

<template>
    <div
        :class="isCurrent ? 'border-secondary-400 bg-secondary-400/5' : 'border-gray-700/50 opacity-60'"
        class="rounded-xl border p-3 transition-all"
    >
        <div class="flex items-center gap-3">
            <span
                :class="isCurrent ? 'border-secondary-400 bg-secondary-400' : 'border-gray-600 bg-gray-600'"
                class="size-4 shrink-0 rounded-full border-3"
            ></span>
            <div class="flex flex-1 flex-col gap-0.75">
                <h6 :class="isCurrent ? 'text-gray-100' : 'text-gray-400'" class="text-base">{{ title }}</h6>
                <p
                    v-if="objective"
                    :class="isCurrent ? 'text-gray-300' : 'text-gray-500'"
                    class="line-clamp-1 text-sm font-light"
                >
                    {{ objective }}
                </p>
            </div>
        </div>
    </div>
</template>
