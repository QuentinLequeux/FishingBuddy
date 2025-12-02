<script setup lang="ts">
import { User } from '@/types';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps({
    users: {
        type: Array as () => User[],
        default: () => [],
    },
});

const submit = (userId: number) => {
    router.post(route('feed.follow', userId), {}, { preserveScroll: true });
};
</script>
<template>
    <div v-if="users.length > 0">
        <div
            v-for="user in users"
            :key="user.id"
            class="mb-4 flex items-center justify-between rounded-xl border-1 border-gray-200 bg-gray-50 p-4 hover:bg-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800"
        >
            <div class="flex items-center gap-4">
                <div class="flex h-[50px] w-[50px] rounded-full bg-gray-300" />
                <a
                    :href="route('profile', user.slug)"
                    :title="`Vers le profil de ${user.name}`"
                >
                    <p class="text-sm font-medium">
                        {{ user.name }}
                    </p>
                </a>
            </div>
            <Button
                v-if="$page.props.auth.user.id !== user.id"
                class="main-button"
                :title="`Suivre ${user.name}`"
                @click="submit(user.id)"
            >
                Suivre
            </Button>
        </div>
    </div>
</template>

<!-- TODO : Condition Suivre/Ne plus suivre -->
