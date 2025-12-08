<script setup lang="ts">
import {
    Heart,
    Trophy,
    Weight,
    EyeIcon,
    HeartPlus,
    MessagesSquare,
    RulerDimensionLine,
} from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import { Separator } from '@/components/ui/separator';

// dayjs
import 'dayjs/locale/fr';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import PopoverActivity from '@/components/feed/PopoverActivity.vue';

dayjs.locale('fr');
dayjs.extend(relativeTime);

const props = defineProps<{
    activity: IActivities;
    authUserId: number | null;
}>();

const submit = (method: string) => {
    router.post(
        route(
            `feed.${method}`,
            method === 'like' ? props.activity.id : props.activity.user.id,
        ),
        {},
        { preserveScroll: true },
    );
};
</script>

<template>
    <article
        class="mt-10 h-auto max-w-[500px] rounded-xl border-1 bg-white p-4 shadow-card dark:bg-gray-900"
    >
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <a
                    :href="route('profile', activity.user?.slug)"
                    :title="`Vers le profile de ${activity.user.name}`"
                    class="h-[40px] w-[40px] rounded-full bg-gray-200"
                />
                <div class="flex flex-col gap-1">
                    <p class="font-medium">
                        {{ activity.user.name }}
                    </p>
                    <time
                        class="cursor-help text-xs text-gray-500"
                        :title="`${new Date(activity.created_at).toLocaleDateString('fr-BE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}`"
                        :datetime="`${activity.created_at}`"
                    >
                        {{ dayjs(activity.created_at).fromNow() }}
                    </time>
                </div>
            </div>
            <div>
                <Button
                    v-if="$page.props.auth.user?.id != activity.user.id"
                    @click="submit('follow')"
                    class="main-button"
                    title="Suivre"
                >
                    <div v-if="activity.isFollowing">Ne plus suivre</div>
                    <div v-else class="flex gap-2 text-white">
                        <HeartPlus />
                        Suivre
                    </div>
                </Button>
            </div>
        </div>
        <div class="my-4 flex gap-4">
            <div class="flex flex-col">
                <p class="text-sm text-gray-500">Esp&egrave;ce</p>
                <p class="text-lg font-medium">
                    {{ activity.specie?.name }}
                </p>
            </div>
            <Separator orientation="vertical" class="h-auto" />
            <div class="flex flex-col">
                <p class="text-sm text-gray-500">Leurre</p>
                <p class="text-lg font-medium">
                    {{ activity.lure?.name }}
                </p>
            </div>
            <div v-if="activity.size > 60" class="flex items-center gap-4">
                <Separator orientation="vertical" class="h-full" />
                <Trophy class="size-6 text-yellow-500" />
            </div>
        </div>
        <div class="mt-2 flex gap-2">
            <Badge class="bg-main text-white">
                <RulerDimensionLine />
                {{ activity.size }}&nbsp;cm
            </Badge>
            <Badge class="bg-main text-white">
                <Weight />
                {{ activity.weight }}&nbsp;kg
            </Badge>
        </div>
        <img
            src="/images/map.png"
            alt="Lieu de pêche"
            width="960"
            height="680"
            class="mt-4 h-auto rounded-xl"
        />
        <div class="mt-4 flex justify-between">
            <div class="flex">
                <div class="relative">
                    <Button
                        @click="submit('like')"
                        variant="ghost"
                        :title="activity.likesCount + ' Likes'"
                    >
                        <Heart
                            :class="
                                activity.hasLiked
                                    ? 'fill-current text-main'
                                    : ''
                            "
                            class="size-5"
                        />
                    </Button>
                    <span
                        v-if="activity.likesCount > 0"
                        class="absolute -top-2 right-0 rounded-full bg-main px-2 py-0.5 text-xs font-medium text-white"
                        >{{ activity.likesCount }}</span
                    >
                </div>
                <Button
                    variant="ghost"
                    @click="$emit('comment', activity)"
                    class="relative"
                    :title="activity.commentsCount + ' Commentaires'"
                >
                    <MessagesSquare class="size-5" />
                    <span
                        v-if="activity.commentsCount > 0"
                        class="absolute -top-2 right-0 rounded-full bg-main px-2 py-0.5 text-xs font-medium text-white"
                        >{{ activity.commentsCount }}</span
                    >
                </Button>
                <Button variant="ghost" title="... Vues" class="cursor-help">
                    <EyeIcon class="size-5" />
                </Button>
            </div>
            <div
                v-if="activity.user_id === $page.props.auth.user?.id"
                class="mr-2"
            >
                <PopoverActivity :activity="activity" />
            </div>
        </div>
    </article>
</template>

<!-- TODO : Compteur views -->
<!-- TODO : Image lieu de pêche -->
<!-- TODO : AlertDialog suppression -->
<!-- TODO : if (props.activities.specie > 60) afficher trophée -->
