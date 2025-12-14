<script setup lang="ts">
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { route } from 'ziggy-js';
import { Form } from '@inertiajs/vue3';
import { PopoverArrow } from 'reka-ui';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import { Ellipsis, SquarePen, Trash2Icon } from 'lucide-vue-next';

const props = defineProps<{
    activity: IActivities;
}>();

const destroy = () => {
    router.delete(route('feed.destroy', props.activity.id), {
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <Popover>
        <PopoverTrigger>
            <div class="hover:bg-gray-100 dark:hover:bg-gray-600 p-1 rounded-full" title="Options">
                <Ellipsis class="cursor-pointer" />
            </div>
        </PopoverTrigger>
        <PopoverContent class="w-fit p-2">
            <Button variant="ghost" title="Modifier">
                <SquarePen />
                Modifier
            </Button>
            <Form @submit.prevent="destroy">
                <Button
                    variant="ghost"
                    title="Supprimer"
                    class="text-red-500 hover:text-red-500"
                >
                    <Trash2Icon />
                    Supprimer
                </Button>
            </Form>
            <PopoverArrow
                class="fill-white stroke-gray-300 dark:fill-gray-950 dark:stroke-gray-700"
            />
        </PopoverContent>
    </Popover>
</template>
