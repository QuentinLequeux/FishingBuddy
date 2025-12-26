<script setup lang="ts">
import 'vue-sonner/style.css';
import { route } from 'ziggy-js';
import { dashboard } from '@/routes';
import { Toaster } from 'vue-sonner';
import { MapPin } from 'lucide-vue-next';
import { onMounted, Ref, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Spinner } from '@/components/ui/spinner';
import { IActivities } from '@/types/IActivities';
import { type BreadcrumbItem, User } from '@/types';
import { Separator } from '@/components/ui/separator';
import UserCard from '@/components/profile/UserCard.vue';
import ActivityCard from '@/components/feed/ActivityCard.vue';
import CommentModal from '@/components/feed/CommentModal.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accueil',
        href: dashboard().url,
    },
];

defineProps<{
    user: User;
    users: User[];
    activity: IActivities;
}>();

interface Weather {
    current_weather: {
        temperature: number;
    };
    daily: {
        temperature_2m_min: number[];
        temperature_2m_max: number[];
    };
}

const isoDate = ref('');
const currentDate = ref('');
const currentTime = ref('');
const lat = ref<number | null>(null);
const long = ref<number | null>(null);
const locationName = ref<string | null>(null);
const weather: Ref<Weather | null> = ref(null);
const openCommentModal = ref(false);
const selectedActivity = ref<IActivities | null>(null);

const updateDateTime = () => {
    const now = new Date();

    isoDate.value = now.toISOString().split('T')[0];
    currentDate.value = now.toLocaleDateString('fr-BE', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });

    currentTime.value = now.toLocaleTimeString('fr-BE', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Europe/Brussels',
    });
};

const fetchWeather = async (lat: number, long: number) => {
    try {
        const response = await fetch(
            `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${long}&daily=temperature_2m_max,temperature_2m_min&current_weather=true&timezone=Europe/Brussels`,
        );
        weather.value = await response.json();
    } catch (error) {
        console.error(error);
    }
};

const fetchLocationName = async (lat: number, long: number) => {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${long}&format=json&accept-language=fr`,
        );
        const data = await response.json();
        const country = (
            (data.address.country_code as string) || ''
        ).toUpperCase();
        const city =
            data.address.city_district ||
            data.address.village ||
            data.address.town ||
            data.address.city ||
            data.address.municipality ||
            data.address.county ||
            data.address.state ||
            data.address.country;
        locationName.value = `${city}, ${country}`;
    } catch (error) {
        console.error(error);
    }
};

const getLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                lat.value = position.coords.latitude;
                long.value = position.coords.longitude;
                fetchWeather(lat.value, long.value);
                fetchLocationName(lat.value, long.value);
            },
            (error) => {
                console.error('Error getting location:', error);
            },
        );
    } else {
        console.error('Geolocation is not supported by this browser.');
    }
};

onMounted(() => {
    updateDateTime();
    setInterval(updateDateTime, 1000);
    getLocation();
});
</script>

<template>
    <Toaster
        position="top-center"
        closeButton
        closeButtonPosition="top-right"
        richColors
    />
    <Head title="Accueil" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-wrap gap-4 bg-[#f4f4f6] p-6 dark:bg-[#262626]"
        >
            <div class="flex min-w-[460px] max-md:min-w-[80%] flex-1 flex-col gap-4">
                <div
                    class="relative h-fit rounded-xl bg-white p-6 shadow-2xs max-sm:w-full dark:bg-sidebar"
                >
                    <h2
                        aria-level="2"
                        role="heading"
                        class="text-xl font-semibold"
                    >
                        Bienvenu(e) sur
                        <span class="text-main">FishingBuddy</span>,
                        {{ user.name }}&nbsp;!
                    </h2>
                    <div v-if="weather" class="mt-4">
                        <span class="font-medium">
                            {{ weather.current_weather.temperature }}°C
                        </span>
                    </div>
                    <Spinner v-else class="mt-4" />
                    <div v-if="locationName" class="mt-2 flex gap-2">
                        <MapPin class="inline-block" /><span
                            class="font-medium"
                            >{{ locationName }}</span
                        >
                    </div>
                    <Spinner class="mt-2" v-else />
                    <div class="mt-4 flex gap-2 font-medium">
                        <span>
                            {{ weather?.daily.temperature_2m_min[0] }}°C
                        </span>
                        <Separator orientation="vertical" class="h-auto" />
                        <span>
                            {{ weather?.daily.temperature_2m_max[0] }}°C
                        </span>
                    </div>
                    <time
                        :datetime="currentTime"
                        class="my-2 block font-medium"
                    >
                        {{ currentTime }}
                    </time>
                    <time :datetime="isoDate" class="font-medium">
                        {{ currentDate }}
                    </time>
                    <span
                        class="absolute right-6 bottom-6 text-[10px] text-gray-500"
                        >open-meteo.com</span
                    >
                </div>
                <div
                    class="flex h-fit w-[460px] flex-col rounded-xl bg-white p-6 shadow-2xs max-md:w-full dark:bg-sidebar"
                >
                    <div class="flex justify-between">
                        <h2
                            aria-level="2"
                            role="heading"
                            class="mb-4 text-xl font-semibold"
                        >
                            Suggestions
                        </h2>
                        <Link
                            :href="route('feed', { tab: 'search' })"
                            class="text-sm font-medium text-main"
                            title="Voir tout"
                        >
                            Voir tout
                        </Link>
                    </div>
                    <UserCard v-if="users" :users="users" />
                </div>
            </div>
            <div
                class="h-fit w-fit rounded-xl bg-white p-6 shadow-2xs max-md:w-full dark:bg-sidebar"
            >
                <div class="flex justify-between">
                    <h2
                        aria-level="2"
                        role="heading"
                        class="text-xl font-semibold"
                    >
                        Activit&eacute;s
                    </h2>
                    <Link
                        :href="route('feed')"
                        class="text-sm font-medium text-main"
                        title="Voir tout"
                    >
                        Voir tout
                    </Link>
                </div>
                <ActivityCard
                    v-if="activity"
                    :activity="activity"
                    :auth-user-id="$page.props.auth.user?.id"
                    class="m-auto !mt-4"
                    @comment="
                        selectedActivity = $event;
                        openCommentModal = true;
                    "
                />
            </div>
        </div>
        <CommentModal
            v-if="selectedActivity"
            v-model:open="openCommentModal"
            :activity="selectedActivity"
        />
    </AppLayout>
</template>

<!-- TODO : CSS Widgets -->
<!-- TODO : Composables -->
