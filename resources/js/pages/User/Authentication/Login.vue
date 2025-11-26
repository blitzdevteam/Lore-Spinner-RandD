<template>
    <AuthenticationLayout>
        <div class="flex flex-col gap-8 w-full">
            <div class="flex flex-col gap-4">
                <BaseInputFormat label="Email" :required="false">
                    <PrimeInputText placeholder="Enter your email address" />
                </BaseInputFormat>
                <BaseInputFormat label="Password" :required="false">
                    <PrimePassword placeholder="Enter your password" :feedback="false" toggle-mask />
                </BaseInputFormat>
        </div>
            <div class="flex flex-col gap-4">
                <BaseButton severity="primary" class="text-lg">Login</BaseButton>
                <div class="justify-center flex">
                    <Link>Forgot password?</Link>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="h-22 bg-gray-950 grid place-items-center rounded-lg cursor-pointer transition-all outline-0 hover:outline-5 outline-gray-600/10">
                    <div class="flex flex-col gap-1 justify-center items-center">
                        <img src="@/assets/brands/google.svg" class="w-6 brightness-0 invert" alt="">
                        <p class="text-white font-light">Google</p>
                    </div>
                </div>
                <div class="h-22 bg-gray-950 grid place-items-center rounded-lg cursor-pointer transition-all outline-0 hover:outline-5 outline-gray-600/10">
                    <div class="flex flex-col gap-1 justify-center items-center">
                        <img src="@/assets/brands/facebook.svg" class="w-6" alt="">
                        <p class="text-white font-light">Facebook</p>
                    </div>
                </div>
                <div class="h-22 bg-gray-950 grid place-items-center rounded-lg cursor-pointer transition-all outline-0 hover:outline-5 outline-gray-600/10">
                    <div class="flex flex-col gap-1 justify-center items-center">
                        <img src="@/assets/brands/apple.svg" class="w-6 brightness-0 invert" alt="">
                        <p class="text-white font-light">Apple</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticationLayout>
</template>

<script setup lang="ts">
import AuthenticationLayout from '@/layouts/AuthenticationLayout.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseInputFormat from '@/components/BaseInputFormat.vue';
import { Link } from '@inertiajs/vue3';
</script>

<style scoped></style>
