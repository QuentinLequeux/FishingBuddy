<script setup lang="ts">
import { User } from '@/types';
import { computed } from 'vue';
import { UserRound } from 'lucide-vue-next';
import { IActivities } from '@/types/IActivities';

const props = defineProps<{
    user?: User;
    size: number;
    activity?: IActivities;
}>();

const targetUser = computed(() => {
    return props.activity?.user ?? props.user ?? null;
});

const avatarSize = computed(() => props.size ?? 40);
</script>

<template>
    <div
        v-if="targetUser?.avatar"
        :style="{ width: `${avatarSize}px`, height: `${avatarSize}px` }"
    >
        <img
            :src="`/storage/${targetUser.avatar}`"
            :alt="`Avatar de ${targetUser.name}`"
            :width="avatarSize"
            :height="avatarSize"
            class="rounded-full"
            :style="{ width: `${avatarSize}px`, height: `${avatarSize}px` }"
        />
    </div>
    <div
        v-else
        class="flex items-center justify-center rounded-full bg-gray-200 overflow-hidden"
        :style="{ width: `${avatarSize}px`, height: `${avatarSize}px` }"
    >
        <UserRound
            class="text-main"
            :style="{ width: `${avatarSize}px`, height: `${avatarSize}px` }"
        />
    </div>
</template>

<!-- TODO : src prod (asset ?) -->
