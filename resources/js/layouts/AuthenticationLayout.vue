<template>
    <App>
        <div class="relative grid min-h-screen w-full place-items-center bg-[#161616]">
            <BaseBackgroundGradient />
            <div class="z-10 w-full max-w-116">
                <div class="flex w-full flex-col items-center gap-8">
                    <img src="@/assets/logo.svg" class="w-68" alt="" />
                    <div class="flex w-full items-center gap-4">
                        <BaseButton
                            type="internal-link"
                            :href="login.create.url()"
                            :severity="currentRouteSeverity(login.create.url())"
                            class="flex-1"
                        >
                            Log In
                        </BaseButton>
                        <BaseButton
                            type="internal-link"
                            :href="register.create.url()"
                            :severity="currentRouteSeverity(register.create.url())"
                            :class="['flex-1', currentRouteClass(register.create.url())]"
                        >
                            Sign Up
                        </BaseButton>
                    </div>
                    <slot></slot>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import App from '@/layouts/App.vue';
import login from '@/wayfinder/routes/user/authentication/login';
import register from '@/wayfinder/routes/user/authentication/register';
import BaseBackgroundGradient from '@/components/BaseBackgroundGradient.vue';

const currentRouteClass = (routeUrl: string): string => {
    return routeUrl === window.location.pathname ? 'hover:text-secondary-300' : '';
};

const currentRouteSeverity = (routeUrl: string): 'secondary-muted-outline' | 'muted' => {
    return routeUrl === window.location.pathname ? 'secondary-muted-outline' : 'muted';
};
</script>

<style scoped></style>
