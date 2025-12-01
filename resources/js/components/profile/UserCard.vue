<script setup lang="ts">
import { User } from '@/types';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';

defineProps({
    users: {
        type: Array as () => User[],
        default: () => [],
    },
});
</script>
<template>
    <div v-if="users.length > 0">
        <div
            v-for="user in users"
            :key="user.id"
            class="mb-4 flex items-center justify-between rounded-xl bg-gray-50 p-4 hover:bg-gray-200 border-1 border-gray-200"
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
                class="bg-main text-white hover:bg-main/90"
                :title="`Suivre ${user.name}`"
            >
                Suivre
            </Button>
        </div>
    </div>
</template>

<!-- TODO : Condition Suivre/Ne plus suivre -->
