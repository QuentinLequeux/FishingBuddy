<script setup lang="ts">
import { ArrowUp } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { onMounted, onUnmounted, ref } from 'vue';

const isVisible = ref(false);
const viewportHeight = 400;

const handleScroll = () => {
    isVisible.value = window.scrollY > viewportHeight;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
    <Button
        variant="outline"
        v-show="isVisible"
        @click="scrollToTop"
        title="Retour en haut de la page"
        class="fixed right-10 bottom-10 z-10 cursor-pointer rounded-full bg-main dark:bg-main p-5.5 hover:bg-main/90 dark:hover:bg-main/90"
    >
        <ArrowUp class="size-5 text-white" />
    </Button>
</template>
