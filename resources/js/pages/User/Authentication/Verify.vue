<script setup lang="ts">
import StickyFooterLayout from '@/layouts/StickyFooterLayout.vue';
import { MailOpen } from 'lucide-vue-next';
import BaseButton from '@/components/BaseButton.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { UserInterface } from '@/types';
import { destroy } from '@/wayfinder/actions/App/Http/Controllers/User/Authentication/LogoutController';
import { resend } from '@/wayfinder/actions/App/Http/Controllers/User/Authentication/VerifyController';

const page = usePage();

const auth = page.props.auth as UserInterface

</script>

<template>
    <StickyFooterLayout class="max-w-102 mx-auto">
        <template #body>
            <div class="flex flex-col gap-4 items-center">
                <MailOpen class="text-primary-400" :size="48" :strokeWidth="1.5" />
                <div class="text-center flex flex-col gap-4">
                    <p class="text-white text-lg">We have sent you an email to verify your account</p>
                    <p class="text-gray-500 text-sm">Please check your inbox and click on the verification link to activate your account</p>
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
                <p class="text-center text-gray-500">{{auth.email}}</p>
            </div>
        </template>
    </StickyFooterLayout>
</template>

<style scoped></style>
