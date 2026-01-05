<script setup lang="ts">
import { ref } from 'vue';
import { User } from '@/types';
import { route } from 'ziggy-js';
import { X } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import UserAvatar from '@/components/global/UserAvatar.vue';

defineProps<{
    users: User[];
}>();

const showSuggest = ref(true);

const closeSuggest = () => {
    showSuggest.value = false;
};
</script>

<template>
    <aside
        v-if="users.length"
        v-show="showSuggest"
        id="suggest"
        class="fixed top-56 right-10 flex max-w-75 flex-col gap-4 max-2xl:hidden pt-8"
    >
        <Button
            class="w-fit absolute top-0 right-0"
            variant="ghost"
            @click="closeSuggest"
            title="Fermer les suggestions"
        >
            <X />
        </Button>
        <div class="flex items-center justify-between gap-2">
            <h3 aria-level="3" role="heading" class="text-sm font-semibold">
                Suggestions
            </h3>
            <Button
                as-child
                variant="ghost"
                class="text-main hover:bg-main/90 hover:text-white dark:hover:bg-main"
                title="Voir tout"
            >
                <Link :href="route('feed', { tab: 'search' })" class="text-sm font-medium"> Voir tout </Link>
            </Button>
        </div>
        <div v-for="user in users" :key="user.id">
            <div class="flex justify-between bg-white hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800 p-2 border rounded-xl">
                <div class="mr-4 flex items-center gap-4">
                    <UserAvatar :user="user" :size="40" />
                    <div class="flex flex-col">
                        <p class="max-w-37.5 truncate text-sm font-medium">
                        {{ user.name }}
                        </p>
                        <p v-if="user.location_visibility && user.location" class="text-xs text-gray-500 truncate max-w-20">
                            {{ user.location }}
                        </p>
                    </div>
                </div>
                <Button
                    variant="ghost"
                    class="text-main hover:bg-main/90 hover:text-white dark:hover:bg-main"
                    title="Suivre"
                    as-child
                >
                    <Link :href="route('profile', user.slug)">
                        Suivre
                    </Link>
                </Button>
            </div>
        </div>
    </aside>
</template>
