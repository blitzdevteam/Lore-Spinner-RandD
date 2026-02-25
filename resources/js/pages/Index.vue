<script setup lang="ts">
import BannerImage from '@/assets/banner.png';
import BaseButton from '@/components/BaseButton.vue';
import BaseContentTitle from '@/components/BaseContentTitle.vue';
import BaseCreatorCard from '@/components/BaseCreatorCard.vue';
import BaseLogo from '@/components/BaseLogo.vue';
import BaseStoryCard from '@/components/BaseStoryCard.vue';
import CommunitySignup from '@/components/CommunitySignup.vue';
import FrequentlyAskedQuestion from '@/components/FrequentlyAskedQuestion.vue';
import HomeLayout from '@/layouts/HomeLayout.vue';
import { CreatorInterface, StoryInterface } from '@/types';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = withDefaults(
    defineProps<{
        creators?: CreatorInterface[];
        stories?: StoryInterface[];
    }>(),
    {
        creators: () => [],
        stories: () => [],
    },
);

const selectedStory = ref<StoryInterface | null>(null);

const activeStory = computed(() => selectedStory.value ?? props.stories[0] ?? null);

const handleSelectStory = (story: StoryInterface) => {
    selectedStory.value = story;
};

// Smart scroll shadows — opacity scales with available scroll distance
const scrollEl = ref<HTMLElement | null>(null);
const topShadow = ref(0);
const bottomShadow = ref(0);

const updateShadows = () => {
    const el = scrollEl.value;
    if (!el) return;

    const { scrollTop, scrollHeight, clientHeight } = el;
    const maxScroll = scrollHeight - clientHeight;

    if (maxScroll <= 0) {
        // Nothing to scroll
        topShadow.value = 0;
        bottomShadow.value = 0;
        return;
    }

    // Ramp up over the first/last 80px of scroll, capped at 1
    topShadow.value = Math.min(scrollTop / 80, 1);
    bottomShadow.value = Math.min((maxScroll - scrollTop) / 80, 1);
};

onMounted(() => {
    const el = scrollEl.value;
    if (el) {
        el.addEventListener('scroll', updateShadows, { passive: true });
        updateShadows();
    }
});

onBeforeUnmount(() => {
    scrollEl.value?.removeEventListener('scroll', updateShadows);
});
</script>

<template>
    <HomeLayout>
        <div
            class="grid h-108 place-items-center bg-cover"
            :style="{ background: `url(${BannerImage}) center center no-repeat`, backgroundSize: 'cover' }"
        >
            <div class="container">
                <div class="-ms-20 flex w-86 flex-col items-center gap-4">
                    <BaseLogo class="w-full" fill="white" />
                    <h3 class="font-gill-sans text-2xl font-light text-primary">Stories That Live Through You</h3>
                </div>
            </div>
        </div>

        <div class="py-18">
            <div class="container">
                <div class="flex flex-col gap-12">
                    <BaseContentTitle title="Creators">
                        <template #description>
                            Meet the minds behind the worlds you love and explore the worlds they are actively
                            <span class="text-primary">bringing to life</span>
                        </template>
                    </BaseContentTitle>
                    <div class="flex flex-col gap-6">
                        <div class="grid grid-cols-3 gap-6">
                            <BaseCreatorCard v-for="creator in creators" :key="creator.username" :creator />
                        </div>
                        <div class="mx-auto">
                            <BaseButton class="w-64 text-lg" severity="transparent"> View All ({{ creators.length }}) </BaseButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-18">
            <div class="container">
                <div class="flex flex-col gap-12">
                    <BaseContentTitle title="Stories">
                        <template #description>
                            Explore
                            <span class="text-primary">original worlds</span>
                            created by creators and unlocked gradually as you read
                        </template>
                    </BaseContentTitle>
                    <div v-if="stories.length" class="flex h-[640px] gap-6">
                        <!-- Left: independently scrollable story list -->
                        <div class="relative w-1/2">
                            <div ref="scrollEl" class="h-full overflow-y-auto pr-2 scrollbar-thin">
                                <div class="flex flex-col gap-4">
                                    <BaseStoryCard
                                        v-for="story in stories"
                                        :key="story.id"
                                        :story
                                        :selectable="true"
                                        :active="activeStory?.id === story.id"
                                        @select="handleSelectStory"
                                    />
                                </div>
                            </div>
                            <!-- Top shadow — fades in as you scroll down -->
                            <div
                                class="pointer-events-none absolute top-0 right-0 left-0 h-10 bg-gradient-to-b from-gray-950 to-transparent transition-opacity duration-200"
                                :style="{ opacity: topShadow }"
                            />
                            <!-- Bottom shadow — fades in when there's more to scroll -->
                            <div
                                class="pointer-events-none absolute right-0 bottom-0 left-0 h-14 bg-gradient-to-t from-gray-950 to-transparent transition-opacity duration-200"
                                :style="{ opacity: bottomShadow }"
                            />
                        </div>
                        <!-- Right: sticky detail panel for selected story -->
                        <div class="w-1/2 overflow-hidden">
                            <Transition name="fade" mode="out-in">
                                <BaseStoryCard
                                    v-if="activeStory"
                                    :key="activeStory.id"
                                    :story="activeStory"
                                    type="column"
                                />
                            </Transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-18">
            <div class="container">
                <CommunitySignup />
            </div>
        </div>

        <div class="pt-18 pb-16">
            <div class="container">
                <FrequentlyAskedQuestion />
            </div>
        </div>
    </HomeLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 2px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
