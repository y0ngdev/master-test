<script setup lang="ts">
import Faq from '@/components/landing/Faq.vue';
import Footer from '@/components/landing/Footer.vue';
import Hero from '@/components/landing/Hero.vue';
import Instructor from '@/components/landing/Instructor.vue';
import Navbar from '@/components/landing/Navbar.vue';
import Pricing from '@/components/landing/Pricing.vue';
import Testimonial from '@/components/landing/Testimonial.vue';
import { Button } from '@/components/ui/button';
import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Plan } from '@/types';
import { Icon } from '@iconify/vue';
import { ChevronRight } from 'lucide-vue-next';

import Head from '@/components/Head.vue';
defineProps<{
    csrf_token: string;
    plan: Plan;
    settings: object;
    testimonials: object;
}>();
</script>
<template>
    <Head :title="settings.general.site_description" />
    <div class="bg-background min-h-screen">
        <!-- Navigation -->
        <Navbar />
        <!-- Hero Section -->

        <Hero
            :title="settings.landing.hero_title"
            :cta="settings.landing.hero_cta_text"
            :subtitle="settings.landing.hero_subtitle"
            :landing-image="settings.landing.hero_image"
        />
        <!-- Course Features -->

        <section class="border-border bg-background border-t py-20">
            <div class="mx-auto max-w-5xl px-6">
                <div class="mb-12 space-y-4 text-center">
                    <h2 class="text-3xl font-semibold tracking-tight">{{ settings.landing.features.section.title }}</h2>
                    <p class="text-muted-foreground">{{ settings.landing.features.section.subtitle }}</p>
                </div>
                <div class="grid gap-8 md:grid-cols-3">
                    <Card v-for="feature in settings.landing.features.items" :key="feature.title">
                        <CardHeader>
                            <div class="bg-primary/10 mb-4 flex h-12 w-12 items-center justify-center rounded-full">
                                <Icon :icon="feature.icon" class="text-primary h-6 w-6" />
                            </div>
                            <CardTitle>{{ feature.title }}</CardTitle>
                            <CardDescription>{{ feature.description }}</CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </div>
        </section>

        <!--         Meet Your Instructor -->
        <Instructor :instructor="settings.landing.instructor" />

        <!--        Testimonials-->
        <Testimonial :testimonials="testimonials"></Testimonial>
        <!-- Pricing Section -->

        <Pricing :pricing="plan" :csrf="csrf_token" />

        <!-- FAQ Section -->
        <Faq v-if="settings.landing.faq" :faqs="settings.landing.faq" :email="settings.general.socials.email" />

        <!-- Start Today -->
        <section class="border-border bg-background border-t py-20">
            <div class="mx-auto max-w-[1280px] px-6 text-center">
                <h2 class="text-foreground mb-4 text-3xl font-semibold">Start your Journey today</h2>
                <p class="text-muted-foreground mb-8 text-xl"></p>
                <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a href="#pricing">
                        <Button class="flex items-center gap-2 transition duration-300 hover:scale-105">
                            Buy Now
                            <ChevronRight class="h-4 w-4" />
                        </Button>
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
