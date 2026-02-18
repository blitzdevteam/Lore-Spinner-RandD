<script setup lang="ts">
import BaseBackgroundGradient from '@/components/BaseBackgroundGradient.vue';
import BaseButton from '@/components/BaseButton.vue';
import { LucideChevronLeft, LucideX, LucideCog, LucideFileText } from 'lucide-vue-next';
import GameplayInput from '@/components/GameplayInput.vue';
import Tabs from 'primevue/tabs';
import TabPanels from 'primevue/tabpanels';
import Tab from 'primevue/tab';
import TabPanel from 'primevue/tabpanel';
import TabList from 'primevue/tablist';
import { ref } from 'vue';

const isSidebarOpen = ref(true)
</script>

<template>
    <div class="h-svh relative">
        <BaseBackgroundGradient />
        <div class="min-h-svh flex relative">
            <div class="flex-1">
                <div class="sticky top-0 w-full left-0 right-0 z-10">
                    <div
                        :class="{
                            'px-24': !isSidebarOpen,
                            'px-8': isSidebarOpen
                        }"
                        class="h-28 flex items-center justify-between bg-linear-to-b from-gray-950 via-gray-950/50 to-transparent z-50"
                    >
                        <div class="flex-1">
                            <BaseButton severity="glass" :icon-only="true" class="size-12!">
                                <LucideChevronLeft class="text-gray-50 size-8" :stroke-width="1.5" />
                            </BaseButton>
                        </div>
                        <div class="flex-3 text-center">
                            <div class="flex flex-col gap-1.5">
                                <h1 class="text-3xl uppercase">Red Hallow Opens</h1>
                                <div>
                                    <span class="bg-gray-800 rounded-full py-1 px-2 text-sm">Chapter 1</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 flex items-center justify-end gap-3">
                            <BaseButton severity="glass" :icon-only="true" class="size-12!">
                                <LucideCog class="text-secondary-300" />
                            </BaseButton>
                            <BaseButton severity="glass" :icon-only="true" class="size-12!" @click="isSidebarOpen = !isSidebarOpen">
                                <LucideFileText
                                    v-if="!isSidebarOpen"
                                    class="text-secondary-300"
                                />
                                <LucideX
                                    v-if="isSidebarOpen"
                                    class="text-secondary-300"
                                />
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="max-w-3xl mx-auto flex flex-col justify-end z-5">
                    <div class="flex flex-col divide-y divide-gray-100/20">
                        <slot name="game" />
                    </div>
                </div>
                <div class="sticky bottom-0 w-full left-0 right-0 z-10">
                    <div class="h-28 grid place-items-center">
                        <GameplayInput />
                    </div>
                </div>
            </div>
            <template v-if="isSidebarOpen">
                <div class="w-md h-svh border-s border-gray-700 bg-gray-900 sticky top-0 bottom-0 flex flex-col">
                    <Tabs value="journals" class="w-full px-8" :show-navigators="false" unstyled>
                        <TabList
                            pt:tab-list="h-28 flex items-center gap-4"
                            pt:content=""
                            pt:active-bar="hidden"
                        >
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
            </template>
        </div>
    </div>
</template>

<style scoped>

</style>
