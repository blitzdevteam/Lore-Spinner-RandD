<script setup lang="ts">
import BaseBackgroundGradient from '@/components/BaseBackgroundGradient.vue';
import BaseButton from '@/components/BaseButton.vue';
import GameplayInput from '@/components/GameplayInput.vue';
import GameplayMediaPlayer from '@/components/GameplayMediaPlayer.vue';
import GameplaySettingsPanel from '@/components/GameplaySettingsPanel.vue';
import { useGameplaySettings } from '@/composables/useGameplaySettings';
import { LucideChevronLeft, LucideCog, LucideFileText, LucideX } from 'lucide-vue-next';
import Tab from 'primevue/tab';
import TabList from 'primevue/tablist';
import TabPanel from 'primevue/tabpanel';
import TabPanels from 'primevue/tabpanels';
import Tabs from 'primevue/tabs';
import { ref } from 'vue';

type RightPanel = 'journal' | 'settings' | null;
const activePanel = ref<RightPanel>(null);

const { settings } = useGameplaySettings();

const togglePanel = (panel: 'journal' | 'settings') => {
    activePanel.value = activePanel.value === panel ? null : panel;
};

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
                            'px-24': !activePanel,
                            'px-8': activePanel,
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
                            <BaseButton severity="glass" :icon-only="true" class="size-12!" @click="togglePanel('settings')">
                                <LucideCog v-if="activePanel !== 'settings'" class="text-secondary-300" />
                                <LucideX v-else class="text-secondary-300" />
                            </BaseButton>
                            <BaseButton severity="glass" :icon-only="true" class="size-12!" @click="togglePanel('journal')">
                                <LucideFileText v-if="activePanel !== 'journal'" class="text-secondary-300" />
                                <LucideX v-else class="text-secondary-300" />
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <!-- Floating media player -->
                <div class="pointer-events-none sticky top-28 z-20 flex justify-center">
                    <GameplayMediaPlayer />
                </div>

                <div
                    class="z-5 mx-auto flex max-w-3xl flex-col justify-end pb-36 transition-colors duration-300"
                    :style="{
                        fontSize: settings.fontSize + 'px',
                        color: settings.fontColor,
                    }"
                >
                    <div
                        v-if="settings.backgroundColor"
                        class="pointer-events-none h-24"
                        :style="{ background: `linear-gradient(to bottom, transparent, ${settings.backgroundColor})` }"
                    />
                    <div :style="{ backgroundColor: settings.backgroundColor || undefined }">
                        <div class="flex flex-col divide-y divide-gray-100/20 px-4">
                            <slot name="game" />
                        </div>
                    </div>
                    <div
                        v-if="settings.backgroundColor"
                        class="pointer-events-none h-24"
                        :style="{ background: `linear-gradient(to top, transparent, ${settings.backgroundColor})` }"
                    />
                </div>
                <div class="sticky right-0 bottom-0 left-0 z-10 w-full">
                    <div class="grid h-28 place-items-center">
                        <GameplayInput @submit="handleInputSubmit" />
                    </div>
                </div>
            </div>
            <Transition name="sidebar-slide">
                <!-- Journal panel -->
                <div
                    v-if="activePanel === 'journal'"
                    key="journal"
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
                                        Journal
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
                                    <div class="flex flex-col gap-3">
                                        <slot name="characters" />
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>
                </div>
                <!-- Settings panel -->
                <div
                    v-else-if="activePanel === 'settings'"
                    key="settings"
                    class="sticky top-0 bottom-0 flex h-svh w-sm shrink-0 flex-col overflow-y-auto border-s border-gray-700 bg-gray-900"
                >
                    <div class="flex h-full w-sm flex-col px-6 pt-8">
                        <GameplaySettingsPanel />
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
