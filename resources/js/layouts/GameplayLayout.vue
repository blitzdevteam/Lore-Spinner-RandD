<script setup lang="ts">
import BaseBackgroundGradient from '@/components/BaseBackgroundGradient.vue';
import BaseButton from '@/components/BaseButton.vue';
import GameplayInput from '@/components/GameplayInput.vue';
import { LucideChevronLeft, LucideCog, LucideFileText, LucideX } from 'lucide-vue-next';
import Tab from 'primevue/tab';
import TabList from 'primevue/tablist';
import TabPanel from 'primevue/tabpanel';
import TabPanels from 'primevue/tabpanels';
import Tabs from 'primevue/tabs';
import { ref } from 'vue';

const isSidebarOpen = ref<boolean>(false);

const emit = defineEmits<{
    submit: [prompt: string];
    back: [];
}>();

const handleInputSubmit = (prompt: string) => {
    emit('submit', prompt);
};
</script>

<template>
    <div class="relative h-svh">
        <BaseBackgroundGradient />
        <div class="relative flex min-h-svh">
            <div class="flex-1">
                <div class="sticky top-0 right-0 left-0 z-10 w-full">
                    <div
                        :class="{
                            'px-24': !isSidebarOpen,
                            'px-8': isSidebarOpen,
                        }"
                        class="z-50 flex h-28 items-center justify-between bg-linear-to-b from-gray-950 via-gray-950/50 to-transparent transition-all duration-300"
                    >
                        <div class="flex-1">
                            <BaseButton severity="glass" :icon-only="true" class="size-12!" @click="$emit('back')">
                                <LucideChevronLeft class="size-8 text-gray-50" :stroke-width="1.5" />
                            </BaseButton>
                        </div>
                        <div class="flex-3 text-center">
                            <slot name="header">
                                <div class="flex flex-col gap-1.5">
                                    <h1 class="text-3xl uppercase">Adventure</h1>
                                </div>
                            </slot>
                        </div>
                        <div class="flex flex-1 items-center justify-end gap-3">
                            <BaseButton severity="glass" :icon-only="true" class="size-12!">
                                <LucideCog class="text-secondary-300" />
                            </BaseButton>
                            <BaseButton severity="glass" :icon-only="true" class="size-12!" @click="isSidebarOpen = !isSidebarOpen">
                                <LucideFileText v-if="!isSidebarOpen" class="text-secondary-300" />
                                <LucideX v-if="isSidebarOpen" class="text-secondary-300" />
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="z-5 mx-auto flex max-w-3xl flex-col justify-end pb-36">
                    <div class="flex flex-col divide-y divide-gray-100/20">
                        <slot name="game" />
                    </div>
                </div>
                <div class="sticky right-0 bottom-0 left-0 z-10 w-full">
                    <div class="grid h-28 place-items-center">
                        <GameplayInput @submit="handleInputSubmit" />
                    </div>
                </div>
            </div>
            <Transition name="sidebar-slide">
                <div
                    v-if="isSidebarOpen"
                    class="sticky top-0 bottom-0 flex h-svh w-md shrink-0 flex-col overflow-hidden border-s border-gray-700 bg-gray-900"
                >
                    <div class="flex h-full w-md flex-col">
                        <Tabs value="journals" class="w-full px-8" :show-navigators="false" unstyled>
                            <TabList pt:tab-list="h-28 flex items-center gap-4" pt:content="" pt:active-bar="hidden">
                                <Tab class="flex-1" value="journals" v-slot="slotProps" as-child>
                                    <BaseButton
                                        @click="slotProps.onClick"
                                        class="w-full"
                                        :severity="slotProps.active ? 'secondary-muted-outline' : 'gray-muted'"
                                    >
                                        Journals
                                    </BaseButton>
                                </Tab>
                                <Tab class="flex-1" value="characters" v-slot="slotProps" as-child>
                                    <BaseButton
                                        @click="slotProps.onClick"
                                        class="w-full"
                                        :severity="slotProps.active ? 'secondary-muted-outline' : 'gray-muted'"
                                    >
                                        Characters
                                    </BaseButton>
                                </Tab>
                            </TabList>
                            <TabPanels class="container">
                                <TabPanel value="journals">
                                    <div class="flex flex-col gap-4">
                                        <slot name="journals" />
                                    </div>
                                </TabPanel>
                                <TabPanel value="characters">
                                    <slot name="characters" />
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>

<style scoped>
.sidebar-slide-enter-active,
.sidebar-slide-leave-active {
    transition: all 0.3s ease;
}

.sidebar-slide-enter-from,
.sidebar-slide-leave-to {
    width: 0;
    opacity: 0;
}
</style>
