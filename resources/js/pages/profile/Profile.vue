<script setup lang="ts">
import { ref } from 'vue';
import 'vue-sonner/style.css';
import { route } from 'ziggy-js';
import { BreadcrumbItem } from '@/types';
import { Toaster, toast } from 'vue-sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Share2, HeartPlus } from 'lucide-vue-next';
import { Separator } from '@/components/ui/separator';
import { router, usePage, Head } from '@inertiajs/vue3';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const { user, follows, posts, isFollowing } = usePage().props as any;
const following = ref(isFollowing);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profil',
        href: route('profile', user.name),
    },
];

const submit = () => {
    following.value = !following.value;

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
    toast.success('Lien copié dans le presse-papier !',{
        duration: 5000,
    });
};
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toaster position="top-center" closeButton richColors />
        <Head :title="user.name" />
        <div class="flex justify-center p-6">
            <div class="relative flex w-[600px] flex-col items-center gap-4">
                <Button
                    class="absolute left-0 bg-main text-white hover:bg-main/90"
                    title="Partager le lien"
                    @click="share"
                >
                    <Share2 class="size-5" />
                </Button>
                <div class="h-[100px] w-[100px] rounded-full bg-gray-200" />
                <h2
                    aria-level="2"
                    role="heading"
                    class="font-semibold text-xl">
                    {{ user.name }}
                </h2>
                <div class="flex gap-2">
                    <p class="text-xs">
                        <span class="font-semibold">{{ follows }}&nbsp;</span>
                        Suivi·e·s
                    </p>
                    <Separator orientation="vertical" />
                    <p class="text-xs">
                        <span class="font-semibold">{{ posts }}&nbsp;</span>
                        Posts
                    </p>
                </div>
                <Button
                    @click="submit"
                    class="my-4 w-full bg-main text-white hover:bg-main/90"
                    :title="following ? 'Ne plus suivre' : 'Suivre'"
                >
                    <div v-if="following">
                        Ne plus suivre
                    </div>
                    <div v-else class="flex gap-2">
                        <HeartPlus class="size-5" />
                        Suivre
                    </div>
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
    </AppLayout>
</template>

<!-- TODO : QR code ? -->
<!-- TODO : Ajouter avatar -->
<!-- TODO : Enlever Layout guest -->
<!-- TODO : Mise à jour live (follow, etc) -->
