<script lang="ts" setup>
import BaseButton from '@/components/BaseButton.vue';
import { CreatorInterface } from '@/types';
import { show } from '@/wayfinder/routes/creators';
import { LucideBookmark } from 'lucide-vue-next';

defineProps<{
    creator: CreatorInterface;
}>();
</script>

<template>
    <div
        class="relative flex flex-col gap-3 rounded-xl border border-gray-700 bg-gray-800 p-3 outline-4 outline-transparent transition hover:border-primary-500"
    >
        <div class="absolute end-3 top-3">
            <BaseButton severity="glass" :icon-only="true">
                <LucideBookmark class="size-6 text-secondary-200" :stroke-width="1.5" />
            </BaseButton>
        </div>
        <div class="grid place-items-center">
            <img :src="creator.avatar" alt="" class="size-40 rounded-full" />
        </div>
        <div class="flex flex-col gap-3">
            <div class="flex flex-col">
                <h3 class="text-lg font-semibold">{{ creator.full_name }}</h3>
                <div class="flex items-center gap-3">
                    <span class="font-light text-primary">{{ creator.stories_count }} Stories</span>
                    <!--                    <span class="w-px h-4 bg-primary"></span>-->
                    <!--                    <span class="text-primary font-light">200K Reads</span>-->
                </div>
            </div>
            <p class="line-clamp-3 text-gray-400">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci aliquid beatae cupiditate ducimus exercitationem expedita, in
                inventore laudantium magnam magni modi odit officia quo, sapiente sequi tenetur ullam unde, voluptates.
            </p>
            <BaseButton
                severity="muted-glass"
                class="text-primary hover:scale-100! hover:bg-primary-400! hover:text-black"
                type="internal-link"
                :href="show(creator.username as string).url"
            >
                View Profile
            </BaseButton>
        </div>
    </div>
</template>

<style scoped></style>
