<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { edit } from '@/routes/privacy';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Paramètres de confidentialité',
        href: edit().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Paramètres de confidentialité" />
        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Paramètres de confidentialité"
                    description="Mettre à jour les paramètres de confidentialité de votre compte"
                />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
