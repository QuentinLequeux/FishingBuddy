<script setup lang="ts">
import 'vue-sonner/style.css';
import {
    Select,
    SelectItem,
    SelectValue,
    SelectTrigger,
    SelectContent,
} from '@/components/ui/select';
import { route } from 'ziggy-js';
import { edit } from '@/routes/privacy';
import { toast, Toaster } from 'vue-sonner';
import type { BreadcrumbItem } from '@/types';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Form, Head, useForm, usePage } from '@inertiajs/vue3';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Paramètres de confidentialité',
        href: edit().url,
    },
];

const props = usePage().props;

const form = useForm({
    visibility: props.defaultVisibility || 'Public',
});

const submit = () => {
    form.patch(route('privacy.update'), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => toast.success('Paramètres modifiés !'),
    });
};

const privacyLabels: Record<string, string> = {
    public: 'Public',
    followers: 'Suivi(e)',
    private: 'Privé',
};
</script>

<template>
    <Toaster
        richColors
        position="top-center"
        closeButton
        closeButtonPosition="top-right"
    />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Paramètres de confidentialité" />
        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Paramètres de confidentialité"
                    description="Mettre à jour les paramètres de confidentialité de votre compte"
                />
                <Form @submit.prevent="submit" class="flex flex-col gap-4">
                    <Label for="privacy">Confidentialit&eacute;</Label>
                    <Select v-model="form.visibility">
                        <SelectTrigger id="privacy">
                            <SelectValue placeholder="Confidentialité">{{
                                privacyLabels[form.visibility as string] ||
                                form.visibility
                            }}</SelectValue>
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="option in props.privacy"
                                :key="option"
                                :value="option"
                            >
                                {{ privacyLabels[option] || option }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <Button type="submit" class="main-button w-fit" title="Changer">
                        Changer
                    </Button>
                </Form>
                <InputError :message="form.errors.visibility" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
