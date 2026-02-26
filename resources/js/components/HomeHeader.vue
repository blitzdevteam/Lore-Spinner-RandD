<script setup lang="ts">
import BaseLogo from '@/components/BaseLogo.vue';
import HomeHeaderMenu from '@/components/HomeHeaderMenu.vue';
import HomeHeaderProfile from '@/components/HomeHeaderProfile.vue';
import HomeHeaderSearchBar from '@/components/HomeHeaderSearchBar.vue';
</script>

<template>
    <header class="h-19 border-b border-b-gray-900 bg-black shadow">
        <div class="container flex h-19 items-center justify-between">
            <div class="flex items-center gap-6 md:gap-16">
                <BaseLogo class="w-28 md:w-36" />
                <div class="hidden md:block">
                    <HomeHeaderMenu />
                </div>
            </div>
            <div class="flex items-center gap-4 md:gap-8">
                <div class="hidden md:block">
                    <HomeHeaderSearchBar />
                </div>
                <HomeHeaderProfile />
            </div>
        </div>
    </header>
</template>
