<script setup lang="ts">
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import AppLogo from './AppLogo.vue';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { dashboard, map } from '@/routes';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import NavFooter from '@/components/NavFooter.vue';
import {
    FigmaIcon,
    Fish,
    GithubIcon,
    HomeIcon,
    IdCard,
    MapIcon,
} from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        title: 'Accueil',
        href: dashboard(),
        icon: HomeIcon,
    },
    {
        title: 'Carte',
        href: map(),
        icon: MapIcon,
    },
    {
        title: 'Permis',
        href: '#',
        icon: IdCard,
    },
    {
        title: 'Espèces',
        href: '#',
        icon: Fish,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github',
        href: 'https://github.com/QuentinLequeux/FishingBuddy',
        icon: GithubIcon,
    },
    {
        title: 'Figma',
        href: 'https://www.figma.com/design/MuP0p49DjDY7RhyGtgxE8f/Projet?node-id=17-2755&t=ZbejOo2sL8LBrUPi-0',
        icon: FigmaIcon,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
