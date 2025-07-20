<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import NavUser from '@/components/NavUser.vue';
import { Button } from '@/components/ui/button';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { type SharedData, User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { useWindowScroll } from '@vueuse/core';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    auth?: User;
}>();

const isOpen = ref(false);
const isScrolled = ref(false);
const { y } = useWindowScroll();

// Watch for scroll position to add shadow
watch(y, (newY) => {
    isScrolled.value = newY > 0;
});

// Close mobile menu when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    if (isOpen.value && !target.closest('nav')) {
        isOpen.value = false;
    }
};

// Close mobile menu on resize if screen becomes larger
const handleResize = () => {
    if (window.innerWidth >= 768 && isOpen.value) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('resize', handleResize);
});

const page = usePage<SharedData>();
</script>

<template>
    <header
        class="bg-background/80 fixed z-50 flex h-16 w-full shrink-0 items-center border-[0.1px] border-b transition-all duration-300"
        :class="{ 'border-b': isScrolled }"
    >
        <div class="container mx-auto px-4">
            <nav class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <a href="/" class="flex items-center gap-2 transition hover:opacity-80">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg">
                                <AppLogoIcon />
                            </div>

                            <div
                                class="hidden whitespace-nowrap text-base font-semibold tracking-tight text-zinc-800 sm:block md:text-[17px] dark:text-zinc-100"
                            >
                                {{ page.props.name }}
                            </div>
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-zinc-500 dark:text-zinc-400">
                    <NavUser v-if="props.auth" />
                    <div class="items-center space-x-6 text-sm" v-else>
                        <a :href="route('login')" class="text-muted-foreground hover:text-foreground font-medium transition-colors"> Sign in </a>
                        <Button class="transition duration-300 hover:scale-105" size="sm">
                            <Link :href="route('homepage') + '#pricing'"> Buy Now</Link>
                        </Button>
                    </div>
                    <!--                    Todo: only show on mobile-->
                    <SidebarTrigger class="ml-2" />
                </div>
            </nav>
        </div>
    </header>
</template>
