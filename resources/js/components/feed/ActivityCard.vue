<script setup lang="ts">
import {
    Clock,
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
import Time from '@/components/feed/Time.vue';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import { Separator } from '@/components/ui/separator';
import UserAvatar from '@/components/global/UserAvatar.vue';
import PopoverActivity from '@/components/feed/PopoverActivity.vue';

// dayjs
import 'dayjs/locale/fr';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

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

const addView = (activity: IActivities) => {
    router.post(
        route('feed.view', activity.id),
        {},
        {
            preserveScroll: true,
        },
    );
    if (!activity.views) {
        activity.views++;
    }
};
</script>

<template>
    <article
        class="mt-10 h-auto max-w-[500px] rounded-xl border-1 bg-white p-4 shadow-card dark:bg-gray-900"
    >
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <UserAvatar :activity="activity" :size="40" />
                <div class="flex flex-col gap-1">
                    <a
                        v-if="activity.user"
                        :href="route('profile', { user: activity.user?.slug })"
                        :title="`Vers le profile de ${activity.user.name}`"
                    >
                        <p class="font-medium">
                            {{ activity.user?.name }}
                        </p>
                    </a>
                    <div class="flex gap-2 text-gray-500">
                        <Clock class="size-3" />
                        <Time :datetime="activity.created_at" />
                    </div>
                </div>
            </div>
            <div>
                <Button
                    v-if="$page.props.auth.user?.id != activity.user?.id"
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
            <div v-if="activity.size > 60" class="flex gap-4">
                <Separator orientation="vertical" class="h-full" />
                <div class="flex-col">
                    <p class="text-sm text-gray-500">Record</p>
                    <Trophy class="size-6 text-yellow-500 mx-auto mt-1" />
                </div>
            </div>
        </div>
        <div class="mt-2 flex gap-2">
            <Badge class="bg-main/80 text-white">
                <RulerDimensionLine />
                {{ activity.size }}&nbsp;cm
            </Badge>
            <Badge class="bg-main/80 text-white">
                <Weight />
                {{ activity.weight }}&nbsp;kg
            </Badge>
        </div>
        <img
            src="/images/map2.png"
            alt="Lieu de pêche"
            width="1001"
            height="667"
            class="mt-4 h-full w-full rounded-xl object-cover"
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
                    <span v-if="activity.likesCount > 0" class="count">{{
                        activity.likesCount
                    }}</span>
                </div>
                <Button
                    v-if="$page.props.auth.user?.id"
                    variant="ghost"
                    @click="
                        $emit('comment', activity);
                        addView(activity);
                    "
                    class="relative"
                    :title="activity.commentsCount + ' Commentaires'"
                >
                    <MessagesSquare class="size-5" />
                    <span v-if="activity.commentsCount > 0" class="count">{{
                        activity.commentsCount
                    }}</span>
                </Button>
                <Button
                    v-else
                    variant="ghost"
                    :title="activity.commentsCount + ' Commentaires'"
                    class="relative"
                    @click="router.visit(route('login'))"
                >
                    <MessagesSquare class="size-5" />
                    <span v-if="activity.commentsCount > 0" class="count">{{
                        activity.commentsCount
                    }}</span>
                </Button>
                <Button
                    variant="ghost"
                    :title="activity.views + ' Vues'"
                    class="relative cursor-help"
                >
                    <EyeIcon class="size-6" />
                    <span v-if="activity.views > 0" class="count">
                        {{ activity.views }}
                    </span>
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

<!-- TODO : if (props.activities.specie > 60) afficher trophée -->
