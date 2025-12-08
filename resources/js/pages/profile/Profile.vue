<script setup lang="ts">
import { ref } from 'vue';
import 'vue-sonner/style.css';
import { route } from 'ziggy-js';
import { BreadcrumbItem } from '@/types';
import { Toaster, toast } from 'vue-sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Share2, HeartPlus } from 'lucide-vue-next';
import Tooltip from '@/components/global/ToolTip.vue';
import { Separator } from '@/components/ui/separator';
import { router, usePage, Head, Link } from '@inertiajs/vue3';
import FollowersModal from '@/components/profile/FollowersModal.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const {
    user,
    posts,
    follows,
    followings,
    isFollowing,
    followers_list,
    following_list,
} = usePage().props as any;
const following = ref(isFollowing);
const followersCount = ref(follows);
const openFollowersModal = ref(false);

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
        <Toaster position="top-center" closeButton richColors />
        <Head :title="user.name" />
        <div class="flex justify-center p-6">
            <h2 aria-level="2" role="heading" class="sr-only">
                Profil - {{ user.name }}
            </h2>
            <div class="relative flex w-[600px] flex-col items-center gap-4">
                <Button
                    class="main-button absolute left-0"
                    title="Partager le lien"
                    @click="share"
                >
                    <Share2 class="mr-0.5 size-5" />
                </Button>
                <div class="h-[100px] w-[100px] rounded-full bg-gray-200" />
                <h3 aria-level="3" role="heading" class="text-xl font-semibold">
                    {{ user.name }}
                </h3>
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
                    <Tooltip :posts="posts">
                        <p>{{ posts }}&nbsp;Posts</p>
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
                >
                    Publier
                </Button>
                <Tabs defaultValue="new">
                    <TabsList class="p-2">
                        <TabsTrigger value="new">R&eacute;cents</TabsTrigger>
                        <TabsTrigger value="old">Anciens</TabsTrigger>
                        <TabsTrigger value="like">Likes</TabsTrigger>
                    </TabsList>
                    <TabsContent value="new">
                        <div>R&eacute;cents</div>
                    </TabsContent>
                    <TabsContent value="old">
                        <div>Anciens</div>
                    </TabsContent>
                    <TabsContent value="like">
                        <div>Likes</div>
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
    </AppLayout>
</template>

<!-- TODO : ToolTip -->
<!-- TODO : QR code ? -->
<!-- TODO : Ajouter avatar -->
<!-- TODO : Affichage du lieu (ex : Namur) -->
<!-- TODO : Condition si aucun post : Icône ou publier si auth()->id === user.id -->
