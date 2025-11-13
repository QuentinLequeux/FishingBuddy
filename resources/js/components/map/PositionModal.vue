<script setup lang="ts">
import { Car, X } from 'lucide-vue-next';
import { defineEmits, defineProps } from 'vue';
import { Button } from '@/components/ui/button';

defineProps<{
    modelValue: boolean;
    lat: string;
    lng: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', v: boolean): void;
    (e: 'open'): void;
    (e: 'close'): void;
}>();

const close = () => {
    emit('update:modelValue', false);
    emit('close');
};

const open = () => {
    emit('open');
};
</script>
<template>
    <transition name="fade">
        <div
            v-if="modelValue"
            class="absolute bottom-4 left-1/2 z-10 flex w-[300px] -translate-x-1/2 flex-col gap-6 rounded-lg bg-white dark:bg-[#262626] p-4 shadow-xl"
        >
            <X
                :size="18"
                class="absolute top-0 right-0 m-1 hover:cursor-pointer"
                @click="close"
            />
            <a
                :href="`https://www.google.com/maps/dir//${lat},${lng}`"
                class="w-fit rounded-full bg-main hove p-2 hover:bg-main/90 absolute"
                target="_blank"
                title="Vers la carte Google Maps"
            >
                <Car color="#fff" />
            </a>
            <p class="mt-2 text-center text-sm">
                {{ lat }}, {{ lng }}
            </p>
            <Button
                class="bg-main font-bold hover:cursor-pointer hover:bg-main/90 dark:text-white"
                @click="open"
                title="Ajouter ce lieu"
            >
                Ajouter ce lieu
            </Button>
        </div>
    </transition>
</template>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.4s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(30px);
}
.fade-enter-to,
.fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>

<!-- TODO : API météo -->
