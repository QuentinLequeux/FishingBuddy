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
import { MapPin } from 'lucide-vue-next';
import { toast, Toaster } from 'vue-sonner';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem, User } from '@/types';
import InputError from '@/components/InputError.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Form, Head, router, useForm, usePage } from '@inertiajs/vue3';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Paramètres de confidentialité',
        href: edit().url,
    },
];

const props = usePage().props;

const user = props.auth.user as User;

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

const toggleLocationVisibility = (event: Event) => {
    const checked = (event.target as HTMLInputElement).checked;

    router.patch(
        route('location.update'),
        {
            location_visibility: checked ? 1 : 0,
        },
        {
            preserveState: false,
            onSuccess: () =>
                toast.success(
                    checked
                        ? 'Localisation visible'
                        : 'Localisation masquée',
                ),
        },
    );
};

const fetchLocationName = async (
    lat: number,
    long: number,
): Promise<string> => {
    const response = await fetch(
        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${long}&format=json&accept-language=fr`,
    );

    const data = await response.json();

    const country = ((data.address.country_code as string) || '').toUpperCase();

    const county = data.address.county;

    return `${county}, ${country}`;
};

const saveLocation = async () => {
    if (!navigator.geolocation) {
        alert('Géolocalisation non supportée');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const location = await fetchLocationName(
                position.coords.latitude,
                position.coords.longitude,
            );

            router.patch(route('location.store'), {
                location,
            },{
                preserveState: false,
                onSuccess: () => toast.success('Localisation ajoutée !'),
            });
        },
        () => alert('Permission refusée'),
    );
};

const deleteLocation = () => {
    router.delete(route('location.destroy'), {
        preserveState: false,
        onSuccess: () => toast.success('Localisation supprimée !'),
    });
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
                    <p class="text-gray-500 text-sm">
                        D&eacute;finis la visibilit&eacute; de vos activit&eacute;s.
                    </p>
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
                    <Button
                        type="submit"
                        class="main-button w-fit"
                        title="Changer"
                    >
                        Changer
                    </Button>
                </Form>
                <InputError :message="form.errors.visibility" />
            </div>
            <div class="flex flex-col gap-6">
                <h3 aria-level="3" role="heading" class="font-medium">
                    Localisation
                </h3>
                <p v-if="user.location" class="text-sm flex items-center gap-2">
                    <MapPin class="text-main" />
                    {{ user.location ? user.location : '' }}
                </p>
                <div class="flex flex-col gap-6">
                    <div class="flex items-start gap-2">
                        <input
                            type="checkbox"
                            id="location"
                            :checked="user.location_visibility"
                            @change="toggleLocationVisibility"
                        />
                        <div class="flex flex-col gap-2">
                            <Label for="location" class="w-fit"
                                >Autoriser</Label
                            >
                            <p class="text-sm text-gray-500">
                                En cochant cette case vous acceptez que votre
                                localisation apparaisse.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <Button
                            class="main-button w-fit"
                            type="submit"
                            title="Supprimer"
                            @click="deleteLocation"
                        >
                            Supprimer
                        </Button>
                        <Button
                            class="main-button w-fit"
                            type="submit"
                            title="Mettre à jour"
                            @click="saveLocation"
                        >
                            Mettre &agrave; jour
                        </Button>
                    </div>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
