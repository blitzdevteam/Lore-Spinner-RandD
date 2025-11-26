<template>
    <App>
        <div class="relative grid min-h-screen w-full place-items-center bg-[#161616]">
            <div class="z-5 bg-gradient-to-r from-primary-400/10 to-transparent absolute top-0 bottom-0 left-0 w-1/4"></div>
            <div class="z-5 bg-gradient-to-l from-primary-400/10 to-transparent absolute top-0 bottom-0 right-0 w-1/4"></div>
            <div class="z-10 w-full max-w-116">
                <div class="flex flex-col gap-8 items-center w-full">
                    <img src="@/assets/logo.svg" class="w-68" alt="">
                    <div class="flex items-center gap-4 w-full">
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
                            :class="[
                                'flex-1', currentRouteClass(register.create.url())
                            ]"
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
import App from '@/layouts/App.vue';
import BaseButton from '@/components/BaseButton.vue';
import login from '@/wayfinder/routes/user/authentication/login';
import register from '@/wayfinder/routes/user/authentication/register';

const currentRouteClass = (routeUrl: string): string => {
    return routeUrl === window.location.pathname ? 'hover:text-secondary-300' : '';
}

const currentRouteSeverity = (routeUrl: string): 'secondary-muted-outline' | 'muted' => {
    return routeUrl === window.location.pathname ? 'secondary-muted-outline' : 'muted';
}

</script>

<style scoped></style>
