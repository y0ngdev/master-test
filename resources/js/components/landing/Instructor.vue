<script setup lang="ts">
import { Card } from '@/components/ui/card';
import { Github, Globe, Linkedin, Mail, Twitter, Youtube } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    instructor: object;
}>();

const socialLinks = computed(() => {
    const map = {
        email: {
            name: 'Email',
            getHref: (v: string) => `mailto:${v}`,
            icon: Mail,
        },
        twitter: {
            name: 'Twitter',
            getHref: (v: string) => `https://twitter.com/${v}`,
            icon: Twitter,
        },
        linkedin: {
            name: 'LinkedIn',
            getHref: (v: string) => `https://linkedin.com/in/${v}`,
            icon: Linkedin,
        },
        youtube: {
            name: 'YouTube',
            getHref: (v: string) => `https://youtube.com/${v.startsWith('@') ? v : '@' + v}`,
            icon: Youtube,
        },
        github: {
            name: 'GitHub',
            getHref: (v: string) => `https://github.com/${v}`,
            icon: Github, // Make sure you've imported the correct GitHub icon
        },
        website: {
            name: 'Website',
            getHref: (v: string) => (v.startsWith('http') ? v : `https://${v}`),
            icon: Globe,
        },
    };

    return Object.entries(props.instructor.socials || {})
        .filter(([key, val]) => map[key] && val)
        .map(([key, val]) => ({
            name: map[key].name,
            href: map[key].getHref(val),
            icon: map[key].icon,
        }));
});
</script>

<template>
    <section class="border-border bg-background border-t py-20">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-semibold tracking-tight">
                    {{ instructor.section.title }}
                </h2>
            </div>
            <Card class="py-0">
                <div class="flex flex-col items-center md:flex-row md:items-stretch">
                    <!-- Instructor Image -->
                    <div class="flex justify-center p-6">
                        <img :src="instructor.image" :alt="instructor.name" class="w-full max-w-md rounded-lg object-contain" />
                    </div>

                    <!-- Instructor Info -->
                    <div class="flex w-full flex-col justify-center p-6 sm:p-8 md:w-1/2 md:p-10">
                        <div class="max-w-xl">
                            <div class="mb-6">
                                <h3 class="text-2xl font-semibold">{{ instructor.name }}</h3>
                                <p class="text-primary mt-1 text-sm font-medium">{{ instructor.title }}</p>
                            </div>

                            <p class="text-muted-foreground mb-8 text-base leading-relaxed">
                                {{ instructor.about }}
                            </p>

                            <div class="flex flex-wrap items-center gap-4">
                                <a
                                    v-for="link in socialLinks"
                                    :key="link.name"
                                    :href="link.href"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hover:bg-muted inline-flex h-10 w-10 items-center justify-center rounded-md transition-colors"
                                >
                                    <component :is="link.icon" class="text-muted-foreground hover:text-foreground h-5 w-5 transition-colors" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </section>
</template>
