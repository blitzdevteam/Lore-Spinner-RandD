<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import login from '@/wayfinder/routes/user/authentication/login';
import { Link, usePage } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const page = usePage();

const auth = computed(() => page.props.auth);

const profileDrawerVisibility = ref(true);
</script>

<template>
    <template v-if="auth === null">
        <Link :href="login.create().url">
            <BaseButton>Account</BaseButton>
        </Link>
    </template>
    <template v-else>
        <Teleport to="body">
            <PrimeDrawer v-model:visible="profileDrawerVisibility" position="right" class="!w-full max-w-108" :show-close-icon="false">
                <template #container="{ closeCallback }">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-4 p-8">
                            <div class="size-10"></div>
                            <div class="flex flex-1 items-center justify-center">
                                <h3 class="text-xl font-light">Profile</h3>
                            </div>
                            <button
                                @cllick.self="closeCallback"
                                class="grid size-10 cursor-pointer place-items-center rounded-full transition hover:bg-gray-900/50"
                            >
                                <X class="size-6 text-gray-400" />
                            </button>
                        </div>
                        <div class="flex-1 p-8">
                            <div class="bg-gray-950 border border-gray-800/50 rounded-sm p-4 flex flex-col gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex-1 flex items-center gap-4">
                                        <img
                                            :src="auth.avatar"
                                            alt=""
                                            class="size-14 cursor-pointer rounded-full"
                                        />
                                        <div class="flex flex-col">
                                            <p class="font-medium text-lg">{{auth.full_name}}</p>
                                            <span class="text-sm text-gray-300">@{{auth.username}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </PrimeDrawer>
        </Teleport>

        <button @click="() => (profileDrawerVisibility = !profileDrawerVisibility)">
            <img
                :src="auth.avatar"
                alt=""
                class="size-12 cursor-pointer rounded-full border border-primary-600 outline-3 outline-transparent transition hover:outline-primary-400/30"
            />
        </button>
    </template>
</template>

<style scoped></style>
