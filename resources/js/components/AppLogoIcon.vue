<script setup lang="ts">
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
const page = usePage<SharedData>();
</script>

<template>
    <!--    {{route('')}}-->
    <img :src="`${page.props.settings.general.site_logo}`" alt="Site logo" :class="className" v-bind="$attrs" />
</template>
