<script setup lang="ts">
import {
    SidebarMenu,
    SidebarGroup,
    SidebarMenuItem,
    SidebarMenuButton,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { urlIsActive } from '@/lib/utils';
import { Badge } from '@/components/ui/badge';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="!item.soon && urlIsActive(item.href, page.url)"
                    :tooltip="item.title"
                    :class="item.soon ? 'pointer-events-none' : ''"
                >
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span :class="item.soon ? 'opacity-50' : ''">{{ item.title }}</span>
                        <Badge v-if="item.soon" class="bg-main">Bientôt</Badge>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
