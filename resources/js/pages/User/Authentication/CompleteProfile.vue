<script setup lang="ts">
import BaseButton from '@/components/BaseButton.vue';
import BaseInputFormat from '@/components/BaseInputFormat.vue';
import StickyFooterLayout from '@/layouts/StickyFooterLayout.vue';
import { GenderEnum } from '@/types/enum';
import { update } from '@/wayfinder/routes/user/authentication/complete-profile';
import { Form } from '@inertiajs/vue3';
import { UserRoundPen } from 'lucide-vue-next';

const genderEnumOptions = Object.values(GenderEnum).map((value) => ({
    label: value.charAt(0).toUpperCase() + value.slice(1).toLowerCase(),
    value: value,
}));
</script>

<template>
    <StickyFooterLayout class="mx-auto max-w-102">
        <template #body>
            <div class="flex flex-col items-center gap-4">
                <UserRoundPen class="text-primary-400" :size="48" :strokeWidth="1.5" />
                <div class="flex flex-col gap-4 text-center">
                    <p class="text-lg text-white">Complete your profile to get started</p>
                    <p class="text-sm text-gray-500">Please provide the necessary information to complete your profile and access all features.</p>
                </div>
            </div>
        </template>
        <template #footer>
            <Form :action="update()" #default="{ errors, processing }">
                <div class="flex flex-col gap-8">
                    <div class="grid w-full grid-cols-2 gap-4">
                        <div class="col-span-full">
                            <BaseInputFormat label="Username" :error="errors.username">
                                <PrimeInputText name="username" placeholder="Enter your username" />
                            </BaseInputFormat>
                        </div>
                        <BaseInputFormat label="First name" :error="errors.first_name">
                            <PrimeInputText name="first_name" placeholder="Enter your first name" />
                        </BaseInputFormat>
                        <BaseInputFormat label="Last name" :error="errors.last_name">
                            <PrimeInputText name="last_name" placeholder="Enter your last name" />
                        </BaseInputFormat>
                        <div class="col-span-full">
                            <BaseInputFormat label="Gender" :error="errors.gender">
                                <PrimeSelect
                                    name="gender"
                                    placeholder="Select your gender"
                                    option-value="value"
                                    option-label="label"
                                    :options="genderEnumOptions"
                                />
                            </BaseInputFormat>
                        </div>
                    </div>
                    <BaseButton :processing>Submit</BaseButton>
                </div>
            </Form>
        </template>
    </StickyFooterLayout>
</template>

<style scoped></style>
