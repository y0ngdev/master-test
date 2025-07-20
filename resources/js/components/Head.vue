<script setup lang="ts">
import { computed } from 'vue';

import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: '',
    },
});

const page = usePage();

const appName = computed(() => page.props.name ?? 'MyApp');

const title = computed(() => (props.title ? `${props.title} - ${appName.value}` : appName.value));
</script>

<template>
    <Head :title="title">
        <link rel="icon" :href="`${page.props.settings.general.site_logo}`" type="image/svg+xml" />

        <slot />
    </Head>
</template>
