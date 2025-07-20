<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, PlayCircle } from 'lucide-vue-next';

defineProps<{
    title: string;
    subtitle: string;
    cta: string;
    landingImage: string;
}>();
</script>

<template>
    <div class="bg-background relative flex min-h-[calc(100vh-4rem)] flex-col items-center justify-center overflow-hidden">
        <!-- Content Container -->
        <div class="pb-16 pt-32">
            <div class="mx-auto max-w-[1280px] px-6">
                <div class="mx-auto mb-16 max-w-[800px] text-center">
                    <h1 class="text-foreground mb-6 text-4xl font-bold leading-tight tracking-[-0.02em] sm:text-5xl lg:text-[56px] lg:leading-[64px]">
                        {{ title }}
                    </h1>
                    <p class="text-muted-foreground mx-auto mb-8 max-w-[600px] text-lg sm:text-xl">{{ subtitle }}</p>
                    <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="#pricing">
                            <Button size="lg" class="min-w-[160px] gap-2">
                                <ArrowRight class="h-5 w-5" />
                                {{ cta }}
                            </Button>
                        </a>
                        <Link :href="route('dashboard')">
                            <Button variant="outline" size="lg" class="min-w-[160px] gap-2">
                                <PlayCircle class="h-5 w-5" />
                                Watch demo
                            </Button>
                        </Link>
                    </div>
                </div>

                <!-- Dashboard Preview -->
                <div class="relative mx-auto max-w-[1000px]">
                    <div class="bg-card text-card-foreground aspect-[16/10] overflow-hidden rounded-2xl border shadow-2xl ring-1 ring-gray-900/10">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/10 to-gray-900/5"></div>
                        <img
                            :src="landingImage"
                            alt="Course Platform Preview"
                            class="h-full w-full transform object-cover transition-transform duration-500 hover:scale-[1.02]"
                            loading="eager"
                        />
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute inset-0 -z-10 scale-[1.15] opacity-30 blur-lg dark:opacity-20">
                        <div class="bg-primary/40 absolute -top-10 left-0 h-72 w-72 rounded-full opacity-70 mix-blend-multiply blur-2xl filter"></div>
                        <div
                            class="bg-primary/40 absolute -top-10 right-0 h-72 w-72 rounded-full opacity-70 mix-blend-multiply blur-2xl filter"
                        ></div>
                    </div>
                    <!-- Browser-like top bar for more realism -->
                    <div class="bg-card/80 absolute left-0 right-0 top-0 flex h-8 items-center rounded-t-2xl border-b px-4 backdrop-blur-sm">
                        <div class="flex space-x-2">
                            <div class="h-3 w-3 rounded-full bg-red-400"></div>
                            <div class="h-3 w-3 rounded-full bg-yellow-400"></div>
                            <div class="h-3 w-3 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
