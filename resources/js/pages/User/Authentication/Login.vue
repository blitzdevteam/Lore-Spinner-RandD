<template>
    <AuthenticationLayout>
        <div class="flex w-full flex-col gap-8">
            <Form class="flex w-full flex-col gap-8" :action="store()" #default="{ errors, processing }">
                <div class="flex flex-col gap-4">
                    <BaseInputFormat label="Email" :required="false" :error="errors.email">
                        <PrimeInputText name="email" placeholder="Enter your email address" />
                    </BaseInputFormat>
                    <BaseInputFormat label="Password" :required="false" :error="errors.password">
                        <PrimePassword name="password" placeholder="Enter your password" :feedback="false" toggle-mask />
                    </BaseInputFormat>
                </div>
                <div class="flex flex-col gap-4">
                    <BaseButton :processing severity="primary" class="text-lg">Login</BaseButton>
                    <div class="flex justify-center">
                        <Link>Forgot password?</Link>
                    </div>
                </div>
            </Form>
            <div class="grid grid-cols-3 gap-4">
                <div
                    class="grid h-22 cursor-pointer place-items-center rounded-lg bg-gray-950 outline-0 outline-gray-600/10 transition-all hover:outline-5"
                >
                    <div class="flex flex-col items-center justify-center gap-1">
                        <img src="@/assets/brands/google.svg" class="w-6 brightness-0 invert" alt="" />
                        <p class="font-light text-white">Google</p>
                    </div>
                </div>
                <div
                    class="grid h-22 cursor-pointer place-items-center rounded-lg bg-gray-950 outline-0 outline-gray-600/10 transition-all hover:outline-5"
                >
                    <div class="flex flex-col items-center justify-center gap-1">
                        <img src="@/assets/brands/facebook.svg" class="w-6" alt="" />
                        <p class="font-light text-white">Facebook</p>
                    </div>
                </div>
                <div
                    class="grid h-22 cursor-pointer place-items-center rounded-lg bg-gray-950 outline-0 outline-gray-600/10 transition-all hover:outline-5"
                >
                    <div class="flex flex-col items-center justify-center gap-1">
                        <img src="@/assets/brands/apple.svg" class="w-6 brightness-0 invert" alt="" />
                        <p class="font-light text-white">Apple</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticationLayout>
</template>

<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import BaseInputFormat from '@/components/BaseInputFormat.vue';
import AuthenticationLayout from '@/layouts/AuthenticationLayout.vue';
import { Form, Link } from '@inertiajs/vue3';
import { store } from '@/wayfinder/actions/App/Http/Controllers/User/Authentication/LoginController';
</script>

<style scoped></style>
