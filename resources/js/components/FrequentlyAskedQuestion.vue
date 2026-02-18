<script setup lang="ts">
import BaseContentTitle from '@/components/BaseContentTitle.vue';
import FrequentlyAskedQuestionItem from '@/components/FrequentlyAskedQuestionItem.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex flex-col gap-12">
        <BaseContentTitle title="Frequently Asked Questions">
            <template #description>
                Can’t find what you need? Visit our
                <Link class="text-primary">community</Link>
                forum, reach out on
                <Link class="text-primary">Twitter</Link>
                , or email our
                <Link class="text-primary">support team</Link>
            </template>
        </BaseContentTitle>
        <div>
            <div class="mx-auto max-w-4xl">
                <div class="flex flex-col gap-6">
                    <FrequentlyAskedQuestionItem>
                        <template #question>How do I publish my first story?</template>
                        <template #answer>
                            Create an account, click “New Story”, write your draft, then hit Publish. You can publish as a full story or by chapters.
                        </template>
                    </FrequentlyAskedQuestionItem>
                    <FrequentlyAskedQuestionItem>
                        <template #question>Do I keep the rights to my stories?</template>
                        <template #answer>
                            Yes. You keep full ownership. We only need permission to host and display your content on the platform.
                        </template>
                    </FrequentlyAskedQuestionItem>
                    <FrequentlyAskedQuestionItem>
                        <template #question>Can I save drafts and edit later?</template>
                        <template #answer> Drafts auto-save while you write. You can continue editing anytime from your Dashboard. </template>
                    </FrequentlyAskedQuestionItem>
                    <FrequentlyAskedQuestionItem>
                        <template #question>Can I monetize my stories?</template>
                        <template #answer>
                            Yes. You can enable tips or set selected chapters as paid content (where available in your region).
                        </template>
                    </FrequentlyAskedQuestionItem>
                    <FrequentlyAskedQuestionItem>
                        <template #question>How do ratings and reviews work?</template>
                        <template #answer>
                            Readers can rate from 1–5 stars and leave reviews. You can report abusive comments and moderate your story’s discussion.
                        </template>
                    </FrequentlyAskedQuestionItem>
                    <FrequentlyAskedQuestionItem>
                        <template #question>How do I contact support?</template>
                        <template #answer>
                            Use the Help button in your dashboard or email support. We typically respond within 24–48 hours on business days.
                        </template>
                    </FrequentlyAskedQuestionItem>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
