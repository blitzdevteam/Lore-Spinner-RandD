<script setup lang="ts">
import { Search } from 'lucide-vue-next';
</script>

<template>
    <div class="relative">
        <PrimeInputText class="h-10 !ps-10 !text-gray-100 !text-sm w-80 placeholder:opacity-70 " placeholder="Search what you want to play" />
        <Search class="absolute inset-x-3 top-1/2 size-4 -translate-y-1/2 text-gray-400" />
    </div>
</template>

<style scoped></style>
