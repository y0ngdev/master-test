<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import type { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { useWindowScroll } from '@vueuse/core';
import { Menu, ShoppingCart, X } from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

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

const navigation = [
    { name: 'Articles', href: route('articles') },
    { name: 'Watch', href: route('dashboard') },
];

const isHome = computed(() => window.location.pathname === '/');

const page = usePage<SharedData>();
</script>

<template>
    <header
        class="bg-background/80 fixed z-50 flex h-16 w-full items-center backdrop-blur-sm transition-all duration-300"
        :class="{ 'border-b': isScrolled }"
    >
        <div class="container mx-auto px-4 md:px-6 lg:px-14">
            <nav class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="mr-8 flex items-center">
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

                    <!-- Desktop Navigation -->
                    <div class="hidden items-center space-x-6 text-sm md:flex">
                        <div class="space-x-6" v-if="isHome">
                            <a
                                href="/#about"
                                class="text-muted-foreground hover:text-foreground after:bg-primary relative font-medium transition-colors after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:transition-all after:duration-300 hover:after:w-full"
                            >
                                About</a
                            >
                            <a
                                href="/#pricing"
                                class="text-muted-foreground hover:text-foreground after:bg-primary relative font-medium transition-colors after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:transition-all after:duration-300 hover:after:w-full"
                            >
                                Pricing</a
                            >
                        </div>
                        <Link
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            class="text-muted-foreground hover:text-foreground after:bg-primary relative font-medium transition-colors after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:transition-all after:duration-300 hover:after:w-full"
                        >
                            {{ item.name }}
                        </Link>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden items-center space-x-6 text-sm md:flex">
                    <a :href="route('login')" class="text-muted-foreground hover:text-foreground font-medium transition-colors"> Sign in </a>

                    <Link :href="route('homepage') + '#pricing'">
                        <Button class="transition duration-300 hover:scale-105" size="sm">
                            <ShoppingCart class="mr-2 h-4 w-4" />
                            Buy Now
                        </Button>
                    </Link>
                </div>

                <!-- Mobile Navigation Button -->
                <div class="md:hidden">
                    <Button variant="ghost" @click="isOpen = !isOpen" class="transition-transform duration-200" :class="{ 'rotate-180': isOpen }">
                        <Menu v-if="!isOpen" class="h-6 w-6" />
                        <X v-else class="h-6 w-6" />
                    </Button>
                </div>

                <!-- Mobile Navigation Menu -->
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="transform -translate-y-4 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-4 opacity-0"
                >
                    <div v-if="isOpen" class="bg-background/80 absolute left-0 top-full w-full border-b py-4 backdrop-blur-sm md:hidden">
                        <div class="container mx-auto px-4">
                            <div class="flex flex-col space-y-4">
                                <a
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    class="text-muted-foreground hover:text-foreground transform transition-all duration-200 hover:translate-x-2"
                                    @click="isOpen = false"
                                >
                                    {{ item.name }}
                                </a>
                                <a
                                    :href="route('login')"
                                    class="text-muted-foreground hover:text-foreground transform transition-all duration-200 hover:translate-x-2"
                                    @click="isOpen = false"
                                >
                                    Sign in
                                </a>

                                <Link :href="route('homepage') + '#pricing'">
                                    <Button
                                        class="w-full transform transition-all duration-200 hover:scale-[1.02]"
                                        as="a"
                                        :href="route('buy')"
                                        @click="isOpen = false"
                                    >
                                        <ShoppingCart class="mr-2 h-4 w-4" />
                                        Buy Now
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </Transition>
            </nav>
        </div>
    </header>
</template>
