<script setup lang="ts">
import { Button } from '@/components/ui/button';

defineProps<{
    hasMore?: boolean;
}>();
</script>

<template>
    <Button
        :v-if="hasMore"
        type="button"
        variant="secondary"
        class="mt-8 w-full font-semibold main-button"
        title="Montrer plus"
        @click="$emit('click')"
    >
        <slot />
    </Button>
</template>
