<script setup lang="ts">
import { ref } from 'vue';
import { User } from '@/types';
import {
    Dialog,
    DialogTitle,
    DialogHeader,
    DialogContent,
} from '@/components/ui/dialog';
import { Ban } from 'lucide-vue-next';
import UserCard from '@/components/profile/UserCard.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const emit = defineEmits(['update:open']);
const activeTab = ref('followers');
const props = defineProps({
    open: Boolean,
    followers: {
        type: Array,
        default: () => [],
    },
    following: {
        type: Array,
        default: () => [],
    },
    followers_list: {
        type: Array as () => User[],
        default: () => [],
    },
    following_list: {
        type: Array as () => User[],
        default: () => [],
    },
});
</script>
<template>
    <Dialog :open="props.open" @update:open="emit('update:open', $event)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ activeTab === 'followers' ? 'Followers' : 'Suivi·e·s' }}
                </DialogTitle>
            </DialogHeader>
            <Tabs
                v-model="activeTab"
                defaultValue="followers"
                class="text-center"
            >
                <TabsList class="mb-4">
                    <TabsTrigger value="followers"
                        >Followers&nbsp;<span
                            class="inline-flex rounded-full bg-main px-2 py-0.5 text-xs text-white"
                            >{{ followers }}</span
                        ></TabsTrigger
                    >
                    <TabsTrigger value="following"
                        >Suivi·e·s&nbsp;<span
                            class="inline-flex rounded-full bg-main px-2 py-0.5 text-xs text-white"
                            >{{ following }}</span
                        ></TabsTrigger
                    >
                </TabsList>
                <TabsContent
                    value="followers"
                    class="max-h-128 overflow-y-auto"
                >
                    <UserCard
                        :users="followers_list"
                        v-if="followers_list.length > 0"
                    />
                    <div
                        v-else
                        class="my-4 flex justify-center gap-2 text-gray-500"
                    >
                        <Ban />
                        Aucun followers
                    </div>
                </TabsContent>
                <TabsContent value="following">
                    <UserCard
                        :users="following_list"
                        v-if="following_list.length > 0"
                    />
                    <div
                        v-else
                        class="my-4 flex justify-center gap-2 text-gray-500"
                    >
                        <Ban />
                        Aucun suivi·e·s
                    </div>
                </TabsContent>
            </Tabs>
        </DialogContent>
    </Dialog>
</template>

<!-- TODO : watch follow ? -->
<!-- TODO : chargement autres users ou pagination ? -->
