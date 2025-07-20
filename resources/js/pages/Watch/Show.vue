<script setup lang="ts">
import { Card } from '@/components/ui/card';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import CourseNav from '@/components/watch/CourseNav.vue';
import Sidebar from '@/components/watch/CourseSidebar.vue';
import { Lesson, Module } from '@/types/course';
import { VueMarkdownIt } from '@f3ve/vue-markdown-it';
import { abbr } from '@mdit/plugin-abbr';
import { mark } from '@mdit/plugin-mark';
import { sub } from '@mdit/plugin-sub';
import { sup } from '@mdit/plugin-sup';
import { tasklist } from '@mdit/plugin-tasklist';
import { ref, watchEffect } from 'vue';

import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/sonner';
import LockedPlayer from '@/components/watch/LockedPlayer.vue';

import { Plan } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

import Head from '@/components/Head.vue';
import VidStack from '@/components/watch/VidStack.vue';

const props = defineProps<{
    lesson: Lesson;
    modules: Module[];
    pricing: Plan;
}>();

function handleVideoEnded() {
    const allLessons = props.modules.flatMap((m) => m.lessons);
    const currentIndex = allLessons.findIndex((l) => l.id === props.lesson.id);
    const nextLesson = allLessons.slice(currentIndex + 1).find((l) => l.canWatch);
    if (nextLesson) {
        console.log('Redirecting to:', nextLesson.url);
        router.visit(nextLesson.url);
    }
}

const opt = ref([tasklist, mark, abbr, sub, sup]);

const page = usePage();
watchEffect(() => {
    const flash = page.props.flash as { type?: string; message?: string } | undefined;

    if (flash?.message) {
        if (flash?.type === 'error') {
            toast.error('Error', { description: flash.message });
        } else {
            toast('Success', { description: flash.message });
        }
    }
});
</script>

<template>
    <Toaster position="top-right" />
    <!--    class="mt-32"-->
    <Head :title="props.lesson.title" />

    <SidebarProvider>
        <CourseNav :auth="$page.props.auth.user" />

        <Sidebar class="mt-16" :lesson="props.lesson" :modules="props.modules" />
        <SidebarInset class="mt-16">
            <!-- Video player area -->
            <div class="flex flex-1 flex-col">
                <!-- Video container -->
                <div class="bg-muted/2 flex flex-1 items-center justify-center">
                    <div class="w-full">
                        <Suspense v-if="props.lesson.canWatch">
                            <VidStack :title="props.lesson.title" :src="props.lesson.streamURL" @video-ended="handleVideoEnded" />
                        </Suspense>
                        <LockedPlayer v-else />
                    </div>
                </div>
                <div class="mx-auto w-full border-t px-4 py-2 tracking-tight md:px-6 md:py-3">
                    <div class="items-center gap-2">
                        <div class="text-xl font-semibold md:pb-1 md:text-3xl">{{ props.lesson.title }}</div>
                        <!--                        <p class="text-sm mt-2">Duration: {{ lesson.duration }}</p>-->
                    </div>
                </div>

                <!-- Lesson info -->
                <div class="mx-auto flex w-full flex-1 flex-col gap-6 border-t px-4 py-2.5 pb-48 md:px-6 md:pb-96 md:pt-4 lg:flex-row">
                    <!-- Sidebar Card (Buy Section) -->

                    <div class="w-full lg:order-2 lg:w-1/3" v-if="!$page.props.auth.user">
                        <Card class="space-y-6 p-6">
                            <div>
                                <h3 class="mb-2 text-lg font-semibold">Full Course</h3>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold">{{ props.pricing.price }}</span>
                                    <span v-if="0 === '0'" class="text-muted-foreground text-sm line-through">$399</span>
                                </div>
                                <p class="text-muted-foreground text-sm">one-time fee</p>
                            </div>

                            <Button class="transition duration-300 hover:scale-105" size="sm">
                                <Link :href="route('homepage') + '#pricing'"> Buy Now</Link>
                            </Button>
                            <div class="space-y-4" v-if="0 === '0'">
                                <div class="flex items-center gap-1">
                                    <!-- Star ratings -->
                                </div>

                                <blockquote class="text-muted-foreground text-sm italic leading-relaxed">
                                    "The course 'Mastering Postgres' helped me practice query commands and understand why they're important.
                                    <strong class="text-foreground"
                                        >Inspiring me to explore the 'why' and 'how' behind each one. Truly a great course!</strong
                                    >"
                                </blockquote>

                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-600">
                                        <span class="text-sm font-medium text-white">N</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Nakolus</p>
                                    </div>
                                </div>
                            </div>
                        </Card>
                    </div>

                    <!-- Course Description -->
                    <div class="w-full text-sm text-zinc-900 md:text-base lg:w-2/3 dark:text-zinc-300">
                        <VueMarkdownIt :source="props.lesson.description" class="prose dark:prose-invert max-w-none" :plugins="opt" />
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
