<script setup lang="ts">
import { ref } from 'vue';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { PopoverArrow, PopoverClose } from 'reka-ui';
import { Separator } from '@/components/ui/separator';
import { LucideSlidersHorizontal, X } from 'lucide-vue-next';

const filters = ref({
    mine: true,
});

const applyFilters = () => {
    router.get(
        route('feed'),
        { filters: filters.value },
        {
            preserveScroll: true,
            preserveState: false,
            replace: true,
            only: ['activities', 'hasMore', 'offset'],
        },
    );
};

const resetFilters = () => {
    router.get(
        route('feed'),
        {
            filters: {},
            offset: 0,
        },
        {
            preserveScroll: true,
            preserveState: false,
            replace: true,
            only: ['activities', 'hasMore', 'offset', 'filters'],
        },
    );
};
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button title="Filtres" class="main-button">
                <LucideSlidersHorizontal />
                Filtres
            </Button>
        </PopoverTrigger>
        <PopoverContent class="flex flex-col">
            <PopoverClose>
                <X :size="16" class="absolute top-2 right-2" />
            </PopoverClose>
            <span class="text-sm">
                Filtres
                <Separator class="my-2" />
            </span>
            <div
                class="my-2 flex w-full items-start gap-2 rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-900"
            >
                <Checkbox
                    id="11"
                    v-model:checked="filters.mine"
                    @click="applyFilters"
                    class="data-[state=checked]:bg-main dark:data-[state=checked]:bg-white"
                />
                <div>
                    <Label for="11">Mes publications</Label>
                    <p class="text-xs">
                        Afficher uniquement vos activit&eacute;s
                    </p>
                </div>
            </div>
            <div class="mt-2 flex justify-end gap-2">
                <Button
                    variant="secondary"
                    size="sm"
                    title="Réinitialiser"
                    type="button"
                    @click="resetFilters"
                >
                    R&eacute;initialiser
                </Button>
            </div>
            <PopoverArrow
                class="fill-white stroke-gray-300 dark:fill-gray-950 dark:stroke-gray-950"
                aria-label="Close"
            />
        </PopoverContent>
    </Popover>
</template>
