<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { CommentInterface } from '@/types';

const props = defineProps<{
    comment: CommentInterface;
}>();

const isFullContentVisible = ref<boolean>(false);
const isTruncated = ref<boolean>(false);
const contentRef = ref<HTMLParagraphElement | null>(null);

const timeAgo = computed((): string => {
    if (! props.comment.created_at) return '';

    const now = new Date();
    const created = new Date(props.comment.created_at);
    const diffMs = now.getTime() - created.getTime();
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);
    const diffWeeks = Math.floor(diffDays / 7);
    const diffMonths = Math.floor(diffDays / 30);

    if (diffMins < 1) return 'just now';
    if (diffMins < 60) return `${diffMins} min ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    if (diffWeeks < 5) return `${diffWeeks} week${diffWeeks > 1 ? 's' : ''} ago`;
    return `${diffMonths} month${diffMonths > 1 ? 's' : ''} ago`;
});

onMounted(() => {
    if (contentRef.value) {
        isTruncated.value = contentRef.value.scrollHeight > contentRef.value.clientHeight;
    }
});
</script>

<template>
    <div class="rounded-xl border border-gray-700 p-3 bg-gray-800/50">
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img :src="comment.author?.avatar" alt="" class="size-9 rounded-full">
                    <p class="text-base text-white">{{ comment.author?.username ?? comment.author?.full_name }}</p>
                </div>
                <span class="text-gray-500 text-sm">{{ timeAgo }}</span>
            </div>
            <p
                ref="contentRef"
                :class="{'line-clamp-3': ! isFullContentVisible}"
                class="font-light leading-relaxed"
            >
                {{ comment.content }}
            </p>
            <button
                v-if="isTruncated && ! isFullContentVisible"
                @click.stop="isFullContentVisible = true"
                class="self-start text-sm text-secondary-300 hover:text-secondary-200 transition-colors"
            >
                View more
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
