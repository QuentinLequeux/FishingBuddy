<script setup lang="ts">
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/hover-card';
import { Info } from 'lucide-vue-next';
import { HoverCardArrow } from 'reka-ui';
</script>

<template>
    <HoverCard>
        <HoverCardTrigger>
            <Info :size="16" class="text-white fill-main" />
        </HoverCardTrigger>
        <HoverCardContent>
            <div class="text-xs">
                <slot/>
            </div>
            <HoverCardArrow class="fill-white stroke-gray-300 dark:stroke-gray-950 dark:fill-gray-950" />
        </HoverCardContent>
    </HoverCard>
</template>
