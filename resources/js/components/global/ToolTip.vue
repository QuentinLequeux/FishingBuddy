<script setup lang="ts">
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
    TooltipProvider,
} from '@/components/ui/tooltip';

defineProps<{
    label?: string | number,
}>();
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <p class="cursor-help text-xs">
                    <slot />
                </p>
            </TooltipTrigger>
            <TooltipContent>
                <slot/>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>

<!-- TODO : Utilisation générale ? -->
