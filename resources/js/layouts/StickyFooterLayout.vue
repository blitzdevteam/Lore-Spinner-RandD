<template>
    <div class="z-10 relative">
        <div class="grid min-h-screen place-items-center max-md:block">
            <div class="flex flex-col gap-8 w-full p-4 max-md:min-h-screen">
                <div class="w-full max-md:flex-1">
                    <slot name="body"></slot>
                </div>
                <div class="w-full">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped></style>
