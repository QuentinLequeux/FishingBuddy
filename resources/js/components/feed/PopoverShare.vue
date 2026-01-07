<script setup lang="ts">
import {
    Popover,
    PopoverTrigger,
    PopoverContent,
} from '@/components/ui/popover';
import { Button } from '@/components/ui/button';
import { Send, Link, Facebook } from 'lucide-vue-next';
</script>

<template>
    <Popover>
        <PopoverTrigger>
            <div
                class="rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-600"
                title="Partager"
            >
                <Send class="size-5 cursor-pointer" />
            </div>
        </PopoverTrigger>
        <PopoverContent class="flex w-fit flex-col gap-2 p-2">
            <Button
                variant="ghost"
                class="w-fit cursor-not-allowed"
                title="Copier le lien"
            >
                <Link />
            </Button>
            <Button
                variant="ghost"
                class="w-fit cursor-not-allowed"
                title="Partager sur Facebook"
            >
                <Facebook />
            </Button>
        </PopoverContent>
    </Popover>
</template>
