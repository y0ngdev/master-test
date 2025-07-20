<script setup lang="ts">
import Footer from '@/components/landing/Footer.vue';
import Navbar from '@/components/landing/Navbar.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Search as SearchIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

import Head from '@/components/Head.vue';
type Article = {
    id: string;
    title: string;
    excerpt: string;
    createdAt: string;
    body: string;
    author: [name: string];
    seo: [title: string, description: string, keywords: string];
};

defineProps<{
    articles: Article;
}>();

const searchTerm = ref('');

const filteredArticles = computed(() =>
    usePage().props.articles.filter((post) => {
        const term = searchTerm.value.toLowerCase();
        return post.title.toLowerCase().includes(term) || post.excerpt.toLowerCase().includes(term);
    }),
);

function formatDate(dateStr: string): string {
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    });
}
</script>
<template>
    <Head title="Articles" />
    <div class="bg-background min-h-screen">
        <!-- Header -->
        <Navbar />
        <!-- Main Content -->
        <main class="mx-auto max-w-3xl px-6 py-16 pt-32">
            <div class="mb-16">
                <h1 class="text-foreground mb-4 text-3xl font-semibold">Articles</h1>
                <p class="text-muted-foreground leading-relaxed">
                    Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...
                </p>
            </div>

            <!-- Search -->
            <div class="mb-12">
                <div class="relative">
                    <SearchIcon class="text-muted-foreground absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" />
                    <input
                        v-model="searchTerm"
                        type="text"
                        placeholder="Search..."
                        class="border-input bg-background text-foreground placeholder:text-muted-foreground focus:ring-ring focus:border-ring w-full rounded-md border py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2"
                    />
                </div>
            </div>

            <div class="space-y-12">
                <article v-for="article in filteredArticles" :key="article.id" class="group cursor-pointer">
                    <Link :href="article.link">
                        <h3 class="text-foreground group-hover:text-muted-foreground mb-2 text-xl font-semibold leading-tight transition-colors">
                            {{ article.title }}
                        </h3>
                        <p class="text-muted-foreground mb-3 leading-relaxed">
                            {{ article.excerpt }}
                        </p>
                        <div class="text-muted-foreground mb-3 flex items-center gap-4 text-sm">
                            <span>{{ article.author.name }}</span>
                            <span>•</span>
                            <span>{{ formatDate(article.createdAt) }}</span>
                        </div>
                    </Link>
                </article>
            </div>

            <!-- No Results -->
            <div v-if="filteredArticles.length === 0" class="py-16 text-center">
                <p class="text-muted-foreground">No articles found</p>
            </div>
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>
