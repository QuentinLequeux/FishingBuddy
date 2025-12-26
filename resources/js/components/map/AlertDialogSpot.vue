<script setup lang="ts">
import { route } from 'ziggy-js';
import { toast } from 'vue-sonner';
import {
    AlertDialog,
    AlertDialogTitle,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogContent,
    AlertDialogTrigger,
    AlertDialogDescription,
} from '@/components/ui/alert-dialog';
import { ISpot } from '@/types/ISpot';
import { Form } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import { computed, defineProps } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Button } from '@/components/ui/button';
import MapController from '@/actions/App/Http/Controllers/Map/MapController';

const props = defineProps<{
    spot: ISpot | null;
}>();

const emit = defineEmits<{
    (e: 'deleted', id: number | null): void;
}>();

const submit = computed(() => {
    if (!props.spot) return {};
    return MapController.destroy.form(props.spot.id);
});

const onSuccess = () => {
    const id = props.spot?.id ?? null;

    toast.success('Spot supprimé', {
        duration: 5000,
        action: {
            label: 'Annuler',
            onClick: () => {
                if (!id) return;
                Inertia.post(route('map.restore', { spot: id }));
            },
        },
    });
    emit('deleted', id);
};
</script>
<template>
    <AlertDialog>
        <AlertDialogTrigger as-child>
            <Button type="button" title="Supprimer" variant="ghost" class="text-red-500 hover:text-red-500">
                <Trash2 :size="20" />
                Supprimer
            </Button>
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Êtes-vous sûr&nbsp;?</AlertDialogTitle>
                <AlertDialogDescription>Apr&egrave;s suppression, le spot ne sera plus disponible.</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <Form
                    v-bind="submit"
                    @success="onSuccess"
                >
                    <AlertDialogAction as-child>
                        <Button
                            type="submit"
                            title="Supprimer"
                            class="bg-red-500 hover:bg-red-500/90 w-full"
                        >
                            Supprimer
                        </Button>
                    </AlertDialogAction>
                </Form>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>

<!-- TODO : Reload après suppression -->
