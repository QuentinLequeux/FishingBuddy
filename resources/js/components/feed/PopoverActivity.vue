<script setup lang="ts">
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { route } from 'ziggy-js';
import { toast } from 'vue-sonner';
import {
    AlertDialog,
    AlertDialogTitle,
    AlertDialogCancel,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTrigger,
    AlertDialogContent,
    AlertDialogDescription,
} from '@/components/ui/alert-dialog';
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
        onSuccess: () => {
            toast.success('Activité supprimée !');
        },
    });
};
</script>

<template>
    <Popover>
        <PopoverTrigger>
            <div
                class="rounded-full p-1 hover:bg-gray-100 dark:hover:bg-gray-600"
                title="Options"
            >
                <Ellipsis class="cursor-pointer" />
            </div>
        </PopoverTrigger>
        <PopoverContent class="flex w-fit flex-col gap-2 p-2">
            <Button variant="ghost" title="Modifier" class="cursor-not-allowed">
                <SquarePen />
                Modifier
            </Button>
            <AlertDialog>
                <AlertDialogTrigger as-child>
                    <Button
                        title="Supprimer"
                        variant="ghost"
                        class="text-red-500 hover:text-red-500"
                    >
                        <Trash2Icon />
                        Supprimer
                    </Button>
                </AlertDialogTrigger>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle
                            >Êtes-vous sûr&nbsp;?</AlertDialogTitle
                        >
                        <AlertDialogDescription
                            >Apr&egrave;s suppression, l'activit&eacute; ne sera
                            plus disponible.</AlertDialogDescription
                        >
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Annuler</AlertDialogCancel>
                        <Form @submit.prevent="destroy">
                            <Button
                                variant="ghost"
                                title="Supprimer"
                                class="w-full bg-red-500 text-white hover:bg-red-500/90 hover:text-white dark:hover:bg-red-500/80"
                            >
                                <Trash2Icon />
                                Supprimer
                            </Button>
                        </Form>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
            <PopoverArrow
                class="fill-white stroke-gray-300 dark:fill-gray-950 dark:stroke-gray-700"
            />
        </PopoverContent>
    </Popover>
</template>
