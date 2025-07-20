<script lang="ts" setup>
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Carousel, CarouselContent, CarouselItem } from '@/components/ui/carousel';
import { getInitials } from '@/composables/useInitials';
import Autoplay from 'embla-carousel-autoplay';

defineProps<{
    testimonials: object;
}>();
const plugin = Autoplay({
    delay: 5000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
});
</script>

<template>
    <Carousel
        class="mx-auto my-20 mb-4 max-h-fit max-w-[800px]"
        :plugins="[plugin]"
        @mouseenter="plugin.stop"
        @mouseleave="[plugin.reset(), plugin.play()]"
    >
        <CarouselContent>
            <CarouselItem v-for="testimonial in testimonials" :key="testimonial.id">
                <div class="flex flex-col items-center text-center">
                    <blockquote class="text-foreground mb-8 text-2xl font-medium leading-[40px] tracking-[-0.02em]">
                        {{ testimonial.content }}
                    </blockquote>

                    <div class="flex items-center gap-4">
                        <Avatar class="h-14 w-14 rounded-full object-cover">
                            <AvatarImage :src="testimonial.avatar" :alt="testimonial.name" />
                            <AvatarFallback>{{ getInitials(testimonial.name) }}</AvatarFallback>
                        </Avatar>
                        <div class="text-left">
                            <div class="font-semibold">{{ testimonial.name }}</div>
                            <div class="text-muted-foreground">{{ testimonial.company }}</div>
                        </div>
                    </div>
                </div>
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>
