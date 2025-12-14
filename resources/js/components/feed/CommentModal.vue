<script setup lang="ts">
import {
    Eye,
    Clock,
    Heart,
    Trophy,
    Weight,
    HeartPlus,
    MessagesSquare,
    RulerDimensionLine,
} from 'lucide-vue-next';
import dayjs from 'dayjs';
import { watch } from 'vue';
import {
    Dialog,
    DialogTitle,
    DialogHeader,
    DialogContent,
} from '@/components/ui/dialog';
import { route } from 'ziggy-js';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import Time from '@/components/feed/Time.vue';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/InputError.vue';
import { Separator } from '@/components/ui/separator';
import { Form, useForm, usePage } from '@inertiajs/vue3';
import UserAvatar from '@/components/global/UserAvatar.vue';
import PopoverComment from '@/components/feed/PopoverComment.vue';

const emit = defineEmits(['update:open']);
const close = () => emit('update:open', false);

const props = defineProps<{
    activity: IActivities;
    open: boolean;
}>();

const user = usePage().props.auth.user;

const form = useForm<{ activity_id: number | null; content: string }>({
    activity_id: null,
    content: '',
});

const submit = () => {
    form.post(route('comment.store'), {
        onSuccess: () => {
            form.reset('content');
        },
        preserveScroll: true,
    });
};

watch(
    () => props.activity,
    (newActivity) => {
        if (newActivity) {
            form.activity_id = newActivity.id;
        }
    },
    { immediate: true },
);
</script>

<template>
    <Dialog :open="props.open" @update:open="close">
        <DialogContent class="gap-2">
            <DialogHeader>
                <DialogTitle>
                    Publication de {{ activity?.user.name }}
                </DialogTitle>
                <Separator class="my-2" />
            </DialogHeader>
            <div class="max-h-128 overflow-y-auto">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <UserAvatar :activity="activity" :size="40" />
                        <div class="flex flex-col gap-1">
                            <a
                                :href="route('profile', activity?.user.slug)"
                                :title="`Vers le profil de ${activity?.user.name}`"
                            >
                                <p class="font-medium">
                                    {{ activity?.user.name }}
                                </p>
                            </a>
                            <div class="flex gap-2 text-gray-500">
                                <Clock class="size-3" />
                                <time
                                    class="cursor-help text-xs text-gray-500"
                                    :datetime="`${activity?.created_at}`"
                                    :title="`${new Date(activity.created_at).toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}`"
                                >
                                    {{ dayjs(activity?.created_at).fromNow() }}
                                </time>
                            </div>
                        </div>
                    </div>
                    <div>
                        <Form
                            :action="route('feed.follow', activity?.user.id)"
                            method="post"
                        >
                            <Button
                                v-if="
                                    $page.props.auth.user.id !==
                                    activity?.user_id
                                "
                                class="main-button"
                                title="Suivre"
                            >
                                <div v-if="activity?.isFollowing">
                                    Ne plus suivre
                                </div>
                                <div v-else class="flex items-center gap-2">
                                    <HeartPlus />
                                    Suivre
                                </div>
                            </Button>
                        </Form>
                    </div>
                </div>
                <div class="my-4 flex gap-4">
                    <div class="flex flex-col">
                        <p class="text-xs text-gray-500">Esp&egrave;ce</p>
                        <p class="text-lg font-medium">
                            {{ activity?.specie?.name }}
                        </p>
                    </div>
                    <Separator orientation="vertical" class="h-auto" />
                    <div class="flex flex-col">
                        <p class="text-xs text-gray-500">Leurre</p>
                        <p class="text-lg font-medium">
                            {{ activity?.lure?.name }}
                        </p>
                    </div>
                    <div
                        v-if="activity.size > 60"
                        class="flex items-center gap-4"
                    >
                        <Separator orientation="vertical" class="h-full" />
                        <Trophy class="size-6 text-yellow-500" />
                    </div>
                </div>
                <div class="flex gap-2">
                    <Badge class="bg-main text-white">
                        <RulerDimensionLine />
                        {{ activity?.size }}&nbsp;cm
                    </Badge>
                    <Badge class="bg-main text-white">
                        <Weight />
                        {{ activity?.weight }}&nbsp;kg
                    </Badge>
                </div>
                <img
                    src="/images/map.png"
                    alt="Lieu de pêche"
                    width="960"
                    height="680"
                    class="mt-4 h-[300px] rounded-xl"
                />
                <div class="relative my-4 flex">
                    <Form
                        :action="route('feed.like', activity?.id)"
                        method="post"
                        class="relative"
                    >
                        <Button
                            type="submit"
                            variant="ghost"
                            :title="activity.likesCount + ' Likes'"
                        >
                            <Heart
                                :class="
                                    activity?.hasLiked
                                        ? 'fill-current text-main'
                                        : ''
                                "
                                class="size-5"
                            />
                            <span
                                v-if="activity.likesCount > 0"
                                class="count"
                                >{{ activity?.likesCount }}
                            </span>
                        </Button>
                    </Form>
                    <Button
                        class="relative"
                        variant="ghost"
                        :title="activity.commentsCount + ' Commentaires'"
                    >
                        <MessagesSquare class="size-5" />
                        <span
                            v-if="activity.commentsCount > 0"
                            class="count"
                            >{{ activity?.commentsCount }}</span
                        >
                    </Button>
                    <Button
                        variant="ghost"
                        :title="activity.views + ' Vues'"
                        class="cursor-help relative"
                    >
                        <Eye class="size-6" />
                        <span class="count">
                            {{ activity.views }}
                        </span>
                    </Button>
                </div>
                <div v-if="activity?.comments.length" class="flex flex-col">
                    <div
                        v-for="comment in activity.comments"
                        :key="comment.id"
                        class="mb-3 flex flex-col gap-3 rounded-xl bg-gray-100 p-3 dark:bg-gray-900"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <UserAvatar :user="comment.user" :size="40" />
                                <a
                                    :href="route('profile', comment.user.slug)"
                                    :title="`Vers le profil de ${comment.user.name}`"
                                >
                                    <p class="text-sm font-medium">
                                        {{ comment.user.name }}
                                    </p>
                                </a>
                            </div>
                            <PopoverComment
                                :user="user"
                                :comment="comment"
                                :activity="activity"
                            />
                        </div>
                        <p class="text-sm">
                            {{ comment.content }}
                        </p>
                        <div class="flex gap-2 text-gray-500">
                            <Clock class="size-3" />
                            <Time :comment="comment" />
                        </div>
                    </div>
                </div>
            </div>
            <Form @submit.prevent="submit">
                <Input
                    type="hidden"
                    name="activity_id"
                    :v-model="form.activity_id"
                />
                <Textarea
                    name="content"
                    placeholder="Écrire votre commentaire ici..."
                    class="max-h-[150px] h-auto"
                    v-model="form.content"
                    maxlength="1000"
                    minlength="3"
                    required
                />
                <InputError :message="form.errors.content" />
                <div class="mt-2 flex justify-between gap-2">
                    <span class="text-xs"
                        >{{ form.content.length }}&nbsp;/&nbsp;1000</span
                    >
                    <Button
                        type="submit"
                        class="main-button mt-2"
                        title="Commenter"
                    >
                        Commenter
                    </Button>
                </div>
            </Form>
        </DialogContent>
    </Dialog>
</template>

<!-- TODO : Like live -->
<!-- TODO : Image lieu -->
<!-- TODO : Components -->
<!-- TODO : Icône scroll ? -->
<!-- TODO : Ajout commentaire live -->
<!-- TODO : Suivre / Ne pas suivre -->
