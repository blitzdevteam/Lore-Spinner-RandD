<script setup lang="ts">
import BaseLogo from '@/components/BaseLogo.vue';
import HomeHeaderMenu from '@/components/HomeHeaderMenu.vue';
import HomeHeaderSearchBar from '@/components/HomeHeaderSearchBar.vue';
import HomeHeaderProfile from '@/components/HomeHeaderProfile.vue';
</script>

<template>
    <header class="h-19 bg-black shadow border-b border-b-gray-900">
        <div class="container flex items-center h-19 justify-between">
            <div class="flex items-center gap-16">
                <BaseLogo class="w-36" />
                <HomeHeaderMenu />
            </div>
            <div class="flex items-center gap-8">
                <HomeHeaderSearchBar />
                <HomeHeaderProfile />
            </div>
        </div>
    </header>
</template>

<style scoped></style>
