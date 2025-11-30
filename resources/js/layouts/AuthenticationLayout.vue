<template>
    <BaseBackgroundGradient />
    <StickyFooterLayout class="mx-auto max-w-124">
        <template #body>
            <div class="flex w-full flex-col items-center gap-8">
                <img src="@/assets/logo.svg" class="w-68" alt="" />
                <div class="flex w-full items-center gap-4">
                    <BaseButton type="internal-link" :href="login.create.url()" :severity="currentRouteSeverity(login.create.url())" class="flex-1">
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
                <slot name="body"></slot>
            </div>
        </template>
        <template #footer>
            <slot name="footer"></slot>
        </template>
    </StickyFooterLayout>
</template>

<script setup lang="ts">
import BaseBackgroundGradient from '@/components/BaseBackgroundGradient.vue';
import BaseButton from '@/components/BaseButton.vue';
import StickyFooterLayout from '@/layouts/StickyFooterLayout.vue';
import login from '@/wayfinder/routes/user/authentication/login';
import register from '@/wayfinder/routes/user/authentication/register';

const currentRouteClass = (routeUrl: string): string => {
    return routeUrl === window.location.pathname ? 'hover:text-secondary-300' : '';
};

const currentRouteSeverity = (routeUrl: string): 'secondary-muted-outline' | 'muted' => {
    return routeUrl === window.location.pathname ? 'secondary-muted-outline' : 'muted';
};
</script>

<style scoped></style>
