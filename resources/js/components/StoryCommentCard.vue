<script setup lang="ts">
import { ref, onMounted } from 'vue';

// TODO: Remove it and replace it with the real data
const rating = ref(3.5);

const isFullContentVisible = ref<boolean>(false);
const isTruncated = ref<boolean>(false);
const contentRef = ref<HTMLParagraphElement | null>(null);

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
                    <img src="@/assets/temp/avatar.png" alt="" class="size-9 rounded-full">
                    <p class="text-base text-white">aboutnima</p>
                </div>
                <span class="text-gray-500 text-sm">2 days ago</span>
            </div>
            <PrimeRating
                v-model="rating"
                readonly
                :dt="{
                    icon: {
                        color: 'var(--color-secondary-300)',
                        size: '0.85rem',
                        active: {
                            color: 'var(--color-secondary-300)'
                        }
                    }
                }"
            />
            <p
                ref="contentRef"
                :class="{'line-clamp-3': ! isFullContentVisible}"
                class="font-light leading-relaxed"
            >
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam dolores eaque facilis impedit inventore labore maiores modi mollitia necessitatibus non odit, quod sed similique. Accusamus adipisci eveniet harum minus modi?
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
