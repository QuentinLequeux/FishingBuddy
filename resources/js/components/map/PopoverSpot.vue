<script setup lang="ts">
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { ISpot } from '@/types/ISpot';
import { Button } from '@/components/ui/button';
import { EllipsisVertical, SquarePen } from 'lucide-vue-next';
import AlertDialogSpot from '@/components/map/AlertDialogSpot.vue';

defineProps<{
    spot: ISpot;
}>()

defineEmits<{
    (e: 'edit'): void;
}>()
</script>
<template>
    <Popover>
        <PopoverTrigger as-child>
            <EllipsisVertical
                class="absolute top-1 right-10 m-2 hover:cursor-pointer hover:text-main dark:text-white"
                :size="24"
            />
        </PopoverTrigger>
        <PopoverContent class="z-10 flex w-full flex-col items-start p-2">
            <Button
                type="button"
                title="Modifier"
                variant="ghost"
                @click="$emit('edit')"
            >
                <SquarePen :size="20" />
                Modifier
            </Button>
            <AlertDialogSpot :spot="spot" />
        </PopoverContent>
    </Popover>
</template>

<!-- TODO : Plein écran z-index -->
