<script setup lang="ts">
import BaseLogo from '@/components/BaseLogo.vue';

const links: {
    title: string;
    children: {
        title: string;
        href: string;
    }[];
}[] = [
    {
        title: 'Community',
        children: [
            { title: 'Discord', href: '#' },
            { title: 'Wattpad', href: '#' },
            { title: 'Reddit', href: '#' },
            { title: 'Facebook', href: '#' },
            { title: 'Youtube', href: '#' },
            { title: 'Instagram', href: '#' },
            { title: 'X', href: '#' },
        ],
    },
    {
        title: 'Navigate',
        children: [
            { title: 'Home', href: '#' },
            { title: 'Library', href: '#' },
            { title: 'creators', href: '#' },
            { title: 'Profile', href: '#' },
        ],
    },
    {
        title: 'Categories',
        children: [
            { title: 'Mystery', href: '#' },
            { title: 'Romance', href: '#' },
            { title: 'Horror', href: '#' },
            { title: 'Sci-Fi', href: '#' },
            { title: 'Historical', href: '#' },
            { title: 'Apocalyptic', href: '#' },
        ],
    },
    {
        title: 'Support',
        children: [
            { title: 'Help Center', href: '#' },
            { title: 'Terms of Service', href: '#' },
            { title: 'Privacy Policy', href: '#' },
        ],
    },
];
</script>

<template>
    <footer class="bg-black bg-linear-to-r from-transparent to-primary-400/15 py-12">
        <div class="container">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-5 md:gap-6">
                <div class="md:col-span-2">
                    <div class="flex flex-col gap-3">
                        <BaseLogo type="orb-typography" class="w-48" />
                        <p class="text-lg text-gray-500">For dreamers, creators, and wanderers; Lorespinner brings your stories to life.</p>
                    </div>
                </div>
                <div class="md:col-span-3">
                    <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                        <div v-for="section in links" :key="section.title" class="flex flex-col gap-3">
                            <h3 class="text-xl text-white">{{ section.title }}</h3>
                            <ul class="flex flex-col gap-1.5">
                                <li v-for="link in section.children" :key="link.title">
                                    <a :href="link.href" class="block text-gray-400 transition-colors hover:text-primary-400">
                                        {{ link.title }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
