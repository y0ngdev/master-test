<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

import { Toaster } from '@/components/ui/sonner';
import { usePage } from '@inertiajs/vue3';
import { watchEffect } from 'vue';
import { toast } from 'vue-sonner';
defineProps<{
    title?: string;
    description?: string;
}>();

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
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
</template>
