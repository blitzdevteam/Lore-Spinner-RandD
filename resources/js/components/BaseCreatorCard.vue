<script lang="ts" setup>
import { WriterInterface } from '@/types';
import BaseButton from '@/components/BaseButton.vue';
import { LucideBookmark } from 'lucide-vue-next';
import { show } from '@/wayfinder/routes/writers';

defineProps<{
    writer: WriterInterface
}>()
</script>

<template>
    <div
        class="flex flex-col gap-3 rounded-xl border border-gray-700 bg-gray-800 p-3 outline-4 outline-transparent transition hover:border-primary-500 relative"
    >
        <div class="absolute top-3 end-3">
            <BaseButton severity="glass" :icon-only="true">
                <LucideBookmark class="size-6 text-secondary-200" :stroke-width="1.5" />
            </BaseButton>
        </div>
        <div class="grid place-items-center">
            <img :src="writer.avatar" alt="" class="rounded-full size-40">
        </div>
        <div class="flex flex-col gap-3">
            <div class="flex flex-col">
                <h3 class="font-semibold text-lg">{{writer.full_name}}</h3>
                <div class="flex items-center gap-3">
                    <span class="text-primary font-light">18 Stories</span>
                    <span class="w-px h-4 bg-primary"></span>
                    <span class="text-primary font-light">200K Reads</span>
                </div>
            </div>
            <p class="text-gray-400 line-clamp-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci aliquid beatae cupiditate ducimus exercitationem expedita, in inventore laudantium magnam magni modi odit officia quo, sapiente sequi tenetur ullam unde, voluptates.</p>
            <BaseButton
                severity="glass"
                class="text-primary hover:bg-primary-400! hover:text-black hover:scale-100!"
                type="internal-link"
                :href="show(writer.username as string).url"
            >
                View Profile
            </BaseButton>
        </div>
    </div>
</template>

<style scoped></style>
