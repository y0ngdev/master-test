<script setup lang="ts">
import 'vidstack/player/styles/default/layouts/video.css';
import 'vidstack/player/styles/default/theme.css';

import { VidstackPlayer, VidstackPlayerLayout } from 'https://cdn.vidstack.io/player';
import { onMounted } from 'vue';
const props = defineProps<{
    title?: string;
    src?: string;
}>();

const emit = defineEmits<{
    (e: 'video-ended'): void;
}>();

onMounted(async () => {
    const player = await VidstackPlayer.create({
        target: '#placeholder',
        title: props.title,
        src: props.src,
        layout: new VidstackPlayerLayout(),
    });

    player.subscribe(({ ended }) => {
        if (ended) emit('video-ended');
    });
});
// Todo onscroll, pop out picture in picture
</script>

<template>
    <div id="placeholder"></div>
</template>
