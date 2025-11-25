<template>
    <App>
        <div class="relative grid min-h-screen w-full place-items-center bg-[#161616]">
            <div class="z-5 bg-gradient-to-r from-primary-400/10 to-transparent absolute top-0 bottom-0 left-0 w-1/4"></div>
            <div class="z-5 bg-gradient-to-l from-primary-400/10 to-transparent absolute top-0 bottom-0 right-0 w-1/4"></div>
            <div class="z-10 w-full max-w-116">
                <div class="flex flex-col gap-8 items-center w-full">
                    <img src="@/assets/logo.svg" class="w-68" alt="">
                    <slot></slot>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
import App from '@/layouts/App.vue';
</script>

<style scoped></style>
