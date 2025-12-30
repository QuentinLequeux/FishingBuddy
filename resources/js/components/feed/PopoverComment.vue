<script setup lang="ts">
import { User } from '@/types';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { route } from 'ziggy-js';
import { toast } from 'vue-sonner';
import { PopoverArrow } from 'reka-ui';
import { router } from '@inertiajs/vue3';
import { IComment } from '@/types/IComment';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import { Ellipsis, SquarePen, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    user: User;
    comment: IComment;
    activity: IActivities;
}>();

const deleteComment = () => {
    router.delete(route('comment.destroy', { comment: props.comment.id }), {
        onSuccess: () => {
            // eslint-disable-next-line vue/no-mutating-props
            props.activity.comments = props.activity.comments.filter(
                (c) => c.id !== props.comment.id,
            );
            toast.success('Commentaire supprimé !', { duration: 5000 });
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Popover v-if="user && user.id === comment.user.id">
        <PopoverTrigger>
            <div class="rounded-full p-1 hover:bg-gray-200 dark:hover:bg-gray-600" title="Options">
                <Ellipsis class="size-5 cursor-pointer" />
            </div>
        </PopoverTrigger>
        <PopoverContent class="flex w-fit flex-col gap-2 p-2">
            <Button variant="secondary" title="Modifier" class="cursor-not-allowed">
                <SquarePen />
                Modifier
            </Button>
            <Button variant="destructive" title="Supprimer" @click="deleteComment">
                <Trash2 />
                Supprimer
            </Button>
            <PopoverArrow
                class="fill-white stroke-gray-300 dark:fill-gray-950 dark:stroke-gray-700"
            />
        </PopoverContent>
    </Popover>
</template>
