<script setup lang="ts">
import { Button } from '@/components/ui/button';

defineProps<{
    faqs: object;
    email: string;
}>();
</script>
<template>
    <section class="border-border bg-background border-t py-20">
        <div class="mx-auto max-w-[1280px] px-6">
            <!-- Section Header -->
            <div class="mx-auto mb-16 max-w-[600px] text-center">
                <h2 class="text-foreground mb-4 text-3xl font-semibold">{{ faqs.title }}</h2>
                <p class="text-muted-foreground text-lg">{{ faqs.description }}</p>
            </div>

            <!-- FAQ Grid -->
            <div class="mx-auto max-w-3xl">
                <div class="grid gap-6">
                    <div v-for="faq in faqs.items" :key="faq.question" class="bg-card rounded-xl border p-6 shadow-sm">
                        <h3 class="text-foreground mb-2 text-lg font-semibold">{{ faq.question }}</h3>
                        <p class="text-muted-foreground">{{ faq.answer }}</p>
                    </div>
                </div>

                <!-- Support CTA -->
                <div class="mt-12 text-center" v-if="email">
                    <p class="text-muted-foreground mb-4">Still have questions?</p>
                    <Button variant="default" size="lg" as="a" :href="'mailto:' + email"> Contact Support</Button>
                </div>
            </div>
        </div>
    </section>
</template>
