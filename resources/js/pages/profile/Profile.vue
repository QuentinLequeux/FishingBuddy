<script setup lang="ts">
import {
    Ban,
    Flame,
    Share2,
    MapPin,
    Facebook,
    HeartPlus,
    TrendingUp,
    TrendingDown,
} from 'lucide-vue-next';
import { ref } from 'vue';
import 'vue-sonner/style.css';
import { route } from 'ziggy-js';
import { BreadcrumbItem } from '@/types';
import { Toaster, toast } from 'vue-sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import Tooltip from '@/components/global/ToolTip.vue';
import { Separator } from '@/components/ui/separator';
import UserAvatar from '@/components/global/UserAvatar.vue';
import { router, usePage, Head, Link } from '@inertiajs/vue3';
import ActivityCard from '@/components/feed/ActivityCard.vue';
import CommentModal from '@/components/feed/CommentModal.vue';
import FollowersModal from '@/components/profile/FollowersModal.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const {
    old,
    user,
    liked,
    posts,
    recent,
    follows,
    followings,
    isFollowing,
    followers_list,
    following_list,
} = usePage().props as any;
const url = window.location.href;
const following = ref(isFollowing);
const followersCount = ref(follows);
const openCommentModal = ref(false);
const openFollowersModal = ref(false);
const selectedActivity = ref<IActivities | null>(null);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profil',
        href: route('profile', user.name),
    },
];

const submit = () => {
    following.value = !following.value;

    if (following.value) {
        followersCount.value++;
    } else {
        followersCount.value--;
    }

    router.post(
        route('feed.follow', user.id),
        {},
        {
            preserveScroll: true,
            preserveState: false,
        },
    );
};

const share = () => {
    const url = window.location.href;
    navigator.clipboard.writeText(url);
    toast.success('Lien copié dans le presse-papier !', {
        duration: 5000,
    });
};

const activeModalTab = ref('followers');

const openFollowers = () => {
    activeModalTab.value = 'followers';
    openFollowersModal.value = true;
};

const openFollowing = () => {
    activeModalTab.value = 'following';
    openFollowersModal.value = true;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toaster
            position="top-center"
            closeButton
            closeButtonPosition="top-right"
            richColors
        />
        <Head :title="user.name" />
        <div class="feed flex justify-center p-6">
            <h2 aria-level="2" role="heading" class="sr-only">
                Profil - {{ user.name }}
            </h2>
            <div
                class="relative flex flex-col items-center gap-4 max-sm:w-full"
            >
                <Button
                    class="main-button absolute left-0"
                    title="Partager le lien"
                    @click="share"
                >
                    <Share2 class="size-5" />
                </Button>
                <Button
                    class="main-button absolute top-12 left-0"
                    title="Partager sur facebook"
                    as-child
                >
                    <a
                        :href="`https://www.facebook.com/sharer/sharer.php?u=${url}`"
                        target="_blank"
                    >
                        <Facebook class="size-5" />
                    </a>
                </Button>
                <UserAvatar :user="user" :size="100" />
                <h3 aria-level="3" role="heading" class="text-xl font-semibold">
                    {{ user.name }}
                </h3>
                <p class="font-medium flex items-center gap-2 text-sm" v-if="user.location_visibility && user.location">
                    <MapPin class="text-main" />
                    {{ user.location }}
                </p>
                <p class="text-xs text-gray-500">
                    Membre depuis
                    {{
                        new Date(user.created_at).toLocaleDateString('fr-BE', {
                            year: 'numeric',
                            month: 'long',
                        })
                    }}
                </p>
                <div class="flex gap-2">
                    <p
                        @click="openFollowers"
                        class="text-xs hover:cursor-pointer"
                        :title="`${followersCount} Followers`"
                    >
                        <span class="font-semibold"
                            >{{ followersCount }}&nbsp;</span
                        >
                        Followers
                    </p>
                    <Separator orientation="vertical" />
                    <p
                        @click="openFollowing"
                        class="text-xs hover:cursor-pointer"
                        :title="`${followings} Suivi(e)s`"
                    >
                        <span class="font-semibold"
                            >{{ followings }}&nbsp;</span
                        >
                        Suivi·e·s
                    </p>
                    <Separator orientation="vertical" />
                    <Tooltip :label="posts">
                        <span class="font-semibold">{{ posts }}</span
                        >&nbsp;Posts
                    </Tooltip>
                </div>
                <Button
                    v-if="$page.props.auth.user?.id != user.id"
                    @click="submit"
                    class="main-button my-4 w-full"
                    :title="following ? 'Ne plus suivre' : 'Suivre'"
                >
                    <div v-if="following">Ne plus suivre</div>
                    <div v-else class="flex gap-2">
                        <HeartPlus class="size-5" />
                        Suivre
                    </div>
                </Button>
                <Button
                    as-child
                    v-if="$page.props.auth.user?.id === user.id"
                    class="main-button w-full"
                    title="Modifier"
                >
                    <Link :href="route('profile.edit')"> Modifier </Link>
                </Button>
                <Button
                    v-if="$page.props.auth.user?.id === user.id"
                    class="main-button mb-4 w-full"
                    title="Publier"
                    @click="router.get(route('feed'), { publish: 1 })"
                >
                    Publier
                </Button>
                <Tabs defaultValue="new">
                    <TabsList class="m-auto flex w-fit justify-center p-2">
                        <TabsTrigger value="new">
                            <div class="flex items-center gap-2">
                                <TrendingUp class="size-6" />
                                R&eacute;cents
                            </div>
                        </TabsTrigger>
                        <TabsTrigger value="old">
                            <div class="flex items-center gap-2">
                                <TrendingDown class="size-6" />
                                Anciens
                            </div>
                        </TabsTrigger>
                        <TabsTrigger value="like">
                            <div class="flex items-center gap-2">
                                <Flame class="size-6" />
                                Populaires
                            </div>
                        </TabsTrigger>
                    </TabsList>
                    <TabsContent value="new">
                        <p
                            v-if="recent.length === 0"
                            class="mt-8 flex h-screen justify-center gap-2 text-gray-500"
                        >
                            <Ban />
                            Aucune publications.
                        </p>
                        <ActivityCard
                            v-for="activity in recent"
                            :key="activity.id"
                            :activity="activity"
                            :auth-user-id="$page.props.auth.user?.id"
                            @comment="
                                selectedActivity = $event;
                                openCommentModal = true;
                            "
                        />
                    </TabsContent>
                    <TabsContent value="old">
                        <p
                            v-if="old.length === 0"
                            class="mt-8 flex h-screen justify-center gap-2 text-gray-500"
                        >
                            <Ban />
                            Aucune publications.
                        </p>
                        <ActivityCard
                            v-for="activity in old"
                            :key="activity.id"
                            :activity="activity"
                            :auth-user-id="$page.props.auth.user?.id"
                            @comment="
                                selectedActivity = $event;
                                openCommentModal = true;
                            "
                        />
                    </TabsContent>
                    <TabsContent value="like">
                        <p
                            v-if="liked.length === 0"
                            class="mt-8 flex h-screen justify-center gap-2 text-gray-500"
                        >
                            <Ban />
                            Aucune publications.
                        </p>
                        <ActivityCard
                            v-for="activity in liked"
                            :key="activity.id"
                            :activity="activity"
                            :auth-user-id="$page.props.auth.user?.id"
                            @comment="
                                selectedActivity = $event;
                                openCommentModal = true;
                            "
                        />
                    </TabsContent>
                </Tabs>
            </div>
        </div>
        <FollowersModal
            v-model:open="openFollowersModal"
            :active-tab="activeModalTab"
            :followers="follows"
            :following="followings"
            :followers_list="followers_list"
            :following_list="following_list"
        />
        <CommentModal
            v-if="selectedActivity"
            v-model:open="openCommentModal"
            :activity="selectedActivity"
        />
    </AppLayout>
</template>
