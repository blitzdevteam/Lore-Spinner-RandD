<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const auth = computed(() => page.props.auth);
</script>

<template>
    <template v-if="auth === null">
        <BaseButton>Account</BaseButton>
    </template>
    <template v-else>
        <img
            :src="auth.avatar"
            alt=""
            class="size-12 cursor-pointer rounded-full border border-primary-600 outline-3 outline-transparent hover:outline-primary-400/30 transition"
        />
    </template>
</template>

<style scoped></style>
