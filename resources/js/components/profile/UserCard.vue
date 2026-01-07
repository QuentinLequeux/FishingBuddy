<script setup lang="ts">
import { User } from '@/types';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { Ban, MapPin } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import UserAvatar from '@/components/global/UserAvatar.vue';

const props = defineProps({
    users: {
        type: Array as () => User[],
        default: () => [],
    },
});

const submit = (userId: number) => {
    const user = props.users.find(u => u.id === userId);

    if (!user) return;

    user.isFollowing = !user.isFollowing;

    router.post(
        route('feed.follow', userId),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};
</script>
<template>
    <div v-if="users.length > 0">
        <div
            v-for="user in users"
            :key="user.id"
            class="mb-4 flex items-center justify-between rounded-xl border border-gray-200 bg-gray-50 p-4 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800"
        >
            <div class="flex items-center gap-4">
                <UserAvatar :user="user" :size="40" />
                <div class="flex flex-col gap-1">
                    <a
                        :href="route('profile', user.slug)"
                        :title="`Vers le profil de ${user.name}`"
                    >
                        <p class="max-w-43.75 text-sm font-medium">
                            {{ user.name }}
                        </p>
                    </a>
                    <p
                        v-if="user.location_visibility && user.location"
                        class="flex max-w-62.5 gap-1 text-xs text-gray-500"
                    >
                        <MapPin class="size-4 text-main" />
                        {{ user.location }}
                    </p>
                </div>
            </div>
            <Button
                v-if="$page.props.auth.user?.id !== user.id"
                class="main-button"
                :title="`Suivre ${user.name}`"
                @click="submit(user.id)"
            >
                {{ user.isFollowing ? 'Ne plus suivre' : 'Suivre' }}
            </Button>
        </div>
    </div>
    <p v-else class="mt-16 flex justify-center gap-2 text-gray-500">
        <Ban />
        Aucun utilisateur trouv&eacute;.
    </p>
</template>
