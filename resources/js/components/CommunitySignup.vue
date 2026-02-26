<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import BaseContentTitle from '@/components/BaseContentTitle.vue';
import BaseInputFormat from '@/components/BaseInputFormat.vue';
</script>

<template>
    <div class="flex flex-col gap-12">
        <BaseContentTitle title="Join The Community">
            <template #description>
                Join our thriving community of
                <span class="text-primary">4,000+ adventurers</span>
                and help forge a more private and decentralized realm of the internet. Start your journey !
            </template>
        </BaseContentTitle>
        <div class="flex items-center justify-center">
            <div class="relative w-full md:w-auto">
                <BaseInputFormat>
                    <PrimeInputText name="email" placeholder="Email address" class="w-full !rounded-2xl !py-4 !pe-28 md:min-w-175" />
                </BaseInputFormat>
                <BaseButton type="button" severity="primary" class="!absolute !end-2 !top-1/2 !h-10 !-translate-y-1/2 !rounded-xl">Submit</BaseButton>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
