<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { Lock } from 'lucide-vue-next';
</script>

<template>
    <div class="relative h-full w-full">
        <div
            class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 bg-zinc-50/90 text-zinc-900 backdrop-blur-lg dark:bg-zinc-950/85 dark:text-zinc-50"
        >
            <Lock />

            <div class="text-lg/6 font-semibold md:text-2xl">Locked video</div>

            <div class="flex flex-col items-center justify-center gap-3 pb-4">
                <p class="text-[13px] text-zinc-600 md:text-sm dark:text-zinc-400">Please purchase the course to watch this video.</p>
                <Link href="/#pricing">
                    <Button>Buy now</Button>
                </Link>
            </div>
        </div>
        <img src="./video_frame.png" alt="Video thumbnail" class="h-full w-full object-cover" />
    </div>
</template>

<style scoped></style>
