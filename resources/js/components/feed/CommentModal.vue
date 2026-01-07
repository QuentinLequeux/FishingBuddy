<script setup lang="ts">
import {
    Eye,
    Clock,
    Heart,
    Flame,
    Trophy,
    Weight,
    HeartPlus,
    MessagesSquare,
    RulerDimensionLine,
} from 'lucide-vue-next';
import { watch } from 'vue';
import {
    Dialog,
    DialogTitle,
    DialogHeader,
    DialogContent,
} from '@/components/ui/dialog';
import { route } from 'ziggy-js';
import { toast } from 'vue-sonner';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import Time from '@/components/feed/Time.vue';
import { Button } from '@/components/ui/button';
import { IActivities } from '@/types/IActivities';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/InputError.vue';
import { Separator } from '@/components/ui/separator';
import { Form, router, useForm, usePage } from '@inertiajs/vue3';
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
            toast.success('Commentaire ajouté !');
        },
        preserveScroll: true,
        preserveState: false,
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

const like = () => {
    if (props.activity.hasLiked) {
        // eslint-disable-next-line vue/no-mutating-props
        props.activity.hasLiked = false;
        // eslint-disable-next-line vue/no-mutating-props
        props.activity.likesCount--;
    } else {
        // eslint-disable-next-line vue/no-mutating-props
        props.activity.hasLiked = true;
        // eslint-disable-next-line vue/no-mutating-props
        props.activity.likesCount++;
    }

    router.post(
        route('feed.like', props.activity.id),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

const follow = () => {
    // eslint-disable-next-line vue/no-mutating-props
    props.activity.isFollowing = !props.activity.isFollowing;

    router.post(
        route('feed.follow', props.activity.user.id),
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
                                <Time :datetime="activity.created_at" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <Button
                            v-if="
                                $page.props.auth.user.id !== activity?.user_id
                            "
                            class="main-button"
                            title="Suivre"
                            @click="follow"
                        >
                            <div v-if="activity?.isFollowing">
                                Ne plus suivre
                            </div>
                            <div v-else class="flex items-center gap-2">
                                <HeartPlus />
                                Suivre
                            </div>
                        </Button>
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
                        <div class="flex-col">
                            <p class="text-sm text-gray-500">Record</p>
                            <Trophy class="mx-auto mt-1 size-6 text-yellow-500" />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <div class="flex gap-2">
                        <Badge class="bg-main/80 text-white">
                            <RulerDimensionLine />
                            {{ activity?.size }}&nbsp;cm
                        </Badge>
                        <Badge class="bg-main/80 text-white">
                            <Weight />
                            {{ activity?.weight }}&nbsp;kg
                        </Badge>
                    </div>
                    <div v-if="activity.likesCount > 1">
                        <Badge class="bg-gray-200">
                            <p class="text-black">Populaire</p>
                            <Flame class="text-red-500" />
                        </Badge>
                    </div>
                </div>
                <img
                    src="/images/map2.png"
                    alt="Lieu de pêche"
                    width="1001"
                    height="667"
                    class="mt-4 h-auto w-full rounded-xl object-cover"
                />
                <div class="relative my-4 flex">
                    <Button
                        type="submit"
                        variant="ghost"
                        :title="activity.likesCount + ' Likes'"
                        @click="like"
                        class="relative"
                    >
                        <Heart
                            :class="
                                activity?.hasLiked
                                    ? 'fill-current text-main'
                                    : ''
                            "
                            class="size-5"
                        />
                        <span v-if="activity.likesCount > 0" class="count"
                            >{{ activity?.likesCount }}
                        </span>
                    </Button>
                    <Button
                        class="relative"
                        variant="ghost"
                        :title="activity.commentsCount + ' Commentaires'"
                    >
                        <MessagesSquare class="size-5" />
                        <span v-if="activity.commentsCount > 0" class="count">{{
                            activity?.commentsCount
                        }}</span>
                    </Button>
                    <Button
                        variant="ghost"
                        :title="activity.views + ' Vues'"
                        class="relative cursor-help"
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
                            <Time :datetime="comment.created_at" />
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
                    class="h-auto max-h-37.5"
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
