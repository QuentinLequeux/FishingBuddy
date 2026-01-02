<script setup lang="ts">
import 'vue-sonner/style.css';
import { feed } from '@/routes';
import { route } from 'ziggy-js';
import { Toaster } from 'vue-sonner';
import { ILure } from '@/types/ILure';
import { AppPageProps } from '@/types';
import { ISpecie } from '@/types/ISpecie';
import { Kbd } from '@/components/ui/kbd';
import { Input } from '@/components/ui/input';
import { BreadcrumbItem, User } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import { Head, router, usePage } from '@inertiajs/vue3';
import LoadMore from '@/components/global/LoadMore.vue';
import UserCard from '@/components/profile/UserCard.vue';
import { Ban, Search, Home, Heart } from 'lucide-vue-next';
import Suggestions from '@/components/feed/Suggestions.vue';
import PublishModal from '@/components/feed/PublishModal.vue';
import CommentModal from '@/components/feed/CommentModal.vue';
import ActivityCard from '@/components/feed/ActivityCard.vue';
import PopoverFilter from '@/components/feed/PopoverFilter.vue';
import ScrollButton from '@/components/global/ScrollButton.vue';
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Activité',
        href: feed().url,
    },
];

const page = usePage<
    AppPageProps & { searchQuery?: string; filters: boolean }
>();

const props = defineProps<{
    activities: IActivities[];
    follows: IActivities[];
    species: ISpecie[];
    lures: ILure[];
    users: User[];
    suggestions: User[];
    hasMore: boolean;
    offset: number;
}>();

const offset = ref(props.offset);
const hasMore = ref(props.hasMore);
const searchRef: any = ref(null);
const openPublishModal = ref(false);
const openCommentModal = ref(false);
const activeTab = ref(page.props.tab || 'feed');
const activities = ref<IActivities[]>(props.activities);
const selectedActivityId = ref<number | null>(null);
const searchQuery = ref(page.props.searchQuery || '');
const shortcutKey = computed(() => (isMac ? '⌘' : 'Ctrl'));
const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;

const handleShortcut = (e: any) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        searchRef.value?.focus();
    }
};

type LoadMoreProps = {
    offset: number;
    hasMore: boolean;
    activities: IActivities[];
};

const loadMore = () => {
    router.get(
        route('feed'),
        { offset: offset.value, filters: page.props.filters },
        {
            preserveScroll: true,
            preserveState: true,
            only: ['activities', 'hasMore', 'offset'],
            onSuccess: (page) => {
                const props = page.props as unknown as LoadMoreProps;
                activities.value.push(...props.activities);
                offset.value = props.offset;
                hasMore.value = props.hasMore;
            },
        },
    );
};

watch(activeTab, (tab) => {
    if (tab !== 'search') {
        router.get(
            route('feed'),
            {},
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
                only: ['users'],
            },
        );
    }
});

const updateSearch = () => {
    router.get(
        route('feed.search'),
        { q: searchQuery.value },
        {
            preserveState: true,
            only: ['users'],
            replace: true,
        },
    );
};

watch(searchQuery, (newVal) => {
    if (!newVal) {
        router.get(
            route('feed.search'),
            { q: '' },
            {
                preserveState: true,
                only: ['users'],
                replace: true,
            },
        );
    } else {
        updateSearch();
    }
});

const selectedActivity = computed(
    () =>
        props.activities.find((a) => a.id === selectedActivityId.value) || null,
);

onMounted(() => {
    if (page.props.publish) {
        openPublishModal.value = true;
    }

    window.addEventListener('keydown', handleShortcut);
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleShortcut);
});
</script>

<template>
    <Toaster
        position="top-center"
        closeButton
        closeButtonPosition="top-right"
        richColors
    />
    <Head title="Activité" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="feed flex flex-col gap-6 p-6">
            <div v-show="$page.props.auth.user" class="flex justify-between">
                <h2 aria-level="2" role="heading" class="text-xl font-semibold">
                    Activit&eacute;
                </h2>
                <div class="flex gap-2">
                    <Button
                        title="Publier"
                        class="main-button"
                        @click="openPublishModal = true"
                    >
                        Publier
                    </Button>
                    <PopoverFilter v-if="activeTab !== 'search' && activeTab !== 'follow'" />
                </div>
            </div>
            <div class="flex justify-center">
                <Tabs
                    v-model="activeTab as string"
                    defaultValue="feed"
                    class="z-10 flex flex-col items-center"
                >
                    <TabsList class="my-2">
                        <TabsTrigger value="feed" class="m-1 px-3">
                            <div class="flex items-center gap-2">
                                <Home class="size-6" />
                                Activit&eacute;
                            </div>
                        </TabsTrigger>
                        <TabsTrigger
                            v-if="$page.props.auth.user"
                            value="follow"
                            class="m-1 px-3"
                        >
                            <div class="flex items-center gap-2">
                                <Heart class="size-6" />
                                Suivi(e)
                            </div>
                        </TabsTrigger>
                        <TabsTrigger value="search" class="m-1 px-3">
                            <div class="flex items-center gap-2">
                                <Search class="size-6" />
                                Recherche
                            </div>
                        </TabsTrigger>
                    </TabsList>
                    <TabsContent value="feed">
                        <p
                            v-if="activities.length === 0"
                            class="mt-8 flex h-screen gap-2 text-gray-500"
                        >
                            <Ban />
                            Aucune publications.
                        </p>
                        <ActivityCard
                            v-for="activity in activities"
                            :key="activity.id"
                            :activity="activity"
                            :auth-user-id="$page.props.auth.user?.id"
                            @comment="
                                selectedActivityId = activity.id;
                                openCommentModal = true;
                            "
                        />
                        <div v-if="activeTab !== 'search'">
                            <LoadMore
                                :has-more="hasMore"
                                @click="loadMore"
                                v-if="hasMore && activities.length > 0"
                            >
                                Montrer plus
                            </LoadMore>
                        </div>
                    </TabsContent>
                    <TabsContent value="follow">
                        <p
                            v-if="props.follows.length === 0"
                            class="mt-8 flex h-screen gap-2 text-gray-500"
                        >
                            <Ban />
                            Aucune publications.
                        </p>
                        <ActivityCard
                            v-for="activity in props.follows"
                            :key="activity.id"
                            :activity="activity"
                            :auth-user-id="$page.props.auth.user?.id"
                            @comment="
                                selectedActivityId = activity.id;
                                openCommentModal = true;
                            "
                        />
                    </TabsContent>
                    <TabsContent value="search">
                        <div class="relative mt-6 w-112.5 max-sm:w-full">
                            <Search
                                class="absolute top-2.5 left-3 size-5 text-gray-500"
                            />
                            <Kbd class="absolute top-2.5 right-9">{{
                                shortcutKey
                            }}</Kbd>
                            <Kbd class="absolute top-2.5 right-2">K</Kbd>
                            <Input
                                v-model="searchQuery"
                                @input="updateSearch"
                                type="text"
                                placeholder="Rechercher un utilisateur..."
                                class="mb-8 bg-white py-5 pl-12 hover:bg-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800"
                                ref="searchRef"
                            />
                            <UserCard
                                v-if="props.users.length"
                                :users="props.users"
                                class="h-screen"
                            />
                        </div>
                    </TabsContent>
                </Tabs>
            </div>
        </div>
        <ScrollButton />
        <PublishModal
            v-model="openPublishModal"
            :species="props.species"
            :lures="props.lures"
        />
        <CommentModal
            v-if="selectedActivity"
            v-model:open="openCommentModal"
            :activity="selectedActivity"
        />
        <Suggestions v-if="activeTab !== 'search'" :users="props.suggestions" />
    </AppLayout>
</template>
