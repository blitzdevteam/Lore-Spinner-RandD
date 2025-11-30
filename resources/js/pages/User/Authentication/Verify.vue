<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import StickyFooterLayout from '@/layouts/StickyFooterLayout.vue';
import { UserInterface } from '@/types';
import { destroy } from '@/wayfinder/actions/App/Http/Controllers/User/Authentication/LogoutController';
import { resend } from '@/wayfinder/actions/App/Http/Controllers/User/Authentication/VerifyController';
import { Link, usePage } from '@inertiajs/vue3';
import { MailOpen } from 'lucide-vue-next';

const page = usePage();

const auth = page.props.auth as UserInterface;
</script>

<template>
    <StickyFooterLayout class="mx-auto max-w-102">
        <template #body>
            <div class="flex flex-col items-center gap-4">
                <MailOpen class="text-primary-400" :size="48" :strokeWidth="1.5" />
                <div class="flex flex-col gap-4 text-center">
                    <p class="text-lg text-white">We have sent you an email to verify your account</p>
                    <p class="text-sm text-gray-500">Please check your inbox and click on the verification link to activate your account</p>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="flex flex-col gap-4">
                <Link :href="resend().url" :method="resend().method">
                    <BaseButton class="w-full">Resend Verification Email</BaseButton>
                </Link>
                <Link :href="destroy().url" :method="destroy().method">
                    <BaseButton class="w-full" severity="muted">Wrong email?</BaseButton>
                </Link>
                <p class="text-center text-gray-500">{{ auth.email }}</p>
            </div>
        </template>
    </StickyFooterLayout>
</template>

<style scoped></style>
