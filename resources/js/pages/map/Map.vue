<script setup lang="ts">
import 'vue-sonner/style.css';
import { Toaster } from 'vue-sonner';
import { ISpot } from '@/types/ISpot';
import { map as carte } from '@/routes';
import { BreadcrumbItem } from '@/types';
import { ISpecie } from '@/types/ISpecie';
import '@maptiler/sdk/dist/maptiler-sdk.css';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FormModal from '@/components/map/FormModal.vue';
import SpotModal from '@/components/map/SpotModal.vue';
import { useFishMarkers } from '@/composables/useFishMarkers';
import PositionModal from '@/components/map/PositionModal.vue';
import { config, Language, Map, MapStyle, Marker } from '@maptiler/sdk';
import { markRaw, onMounted, onUnmounted, reactive, ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Carte',
        href: carte().url,
    },
];

const mapContainer = ref<HTMLElement | null>(null);
const map = ref<any>(null);
const props = defineProps<{ spots: ISpot[]; species: ISpecie[] }>();
const spots = ref<ISpot[]>(props.spots || []);
const spotModalVisible = ref(false);
const spotModalData = ref<ISpot | null>(null);
const { createFishMarkers } = useFishMarkers(
    map,
    spotModalData,
    spotModalVisible,
);
let userMarker: Marker | null = null;
const markerVisible = ref(false);
config.primaryLanguage = Language.FRENCH;
config.apiKey = import.meta.env.VITE_MAPTILER_KEY;
const mapZoom = 13;
const species = ref<ISpecie[]>(props.species || []);

const popup = reactive({
    lng: 0,
    lat: 0,
    visible: false,
});

const popupContent = reactive({
    lng: popup.lng,
    lat: popup.lat,
    visible: false,
});

// Dark mode
function mapStyleForCurrentMode() {
    return document.documentElement.classList.contains('dark')
        ? MapStyle.BRIGHT.DARK
        : MapStyle.BRIGHT;
}

function initMap(center: [number, number]) {
    map.value = markRaw(
        new Map({
            container: mapContainer.value as HTMLElement,
            style: mapStyleForCurrentMode(),
            center,
            zoom: mapZoom,
            fullscreenControl: true,
            localIdeographFontFamily: 'Poppins',
            maxZoom: 20,
            projectionControl: true,
            scaleControl: true,
        }),
    );

    createFishMarkers(spots.value);

    watch(
        () => props.spots,
        (newSpots) => {
            if (userMarker) {
                userMarker.remove();
                userMarker = null;
                markerVisible.value = false;
                popup.visible = false;
            }

            createFishMarkers(newSpots);
        },
    );

    map.value.on('click', (e: any) => {
        const lng = e.lngLat.lng;
        const lat = e.lngLat.lat;

        if (!userMarker) {
            userMarker = new Marker({ color: '#1D4E8D', draggable: true })
                .setLngLat([lng, lat])
                .addTo(map.value);
        }
        if (!markerVisible.value) {
            userMarker.setLngLat([lng, lat]);
            userMarker.getElement().style.display = 'block';
            markerVisible.value = true;
            popup.lng = lng;
            popup.lat = lat;
            popup.visible = true;
        } else {
            userMarker.getElement().style.display = 'none';
            markerVisible.value = false;
            popup.visible = false;

            const e = userMarker.getElement();
            e.style.cursor = 'pointer';

            e.addEventListener('mouseenter', () => {
                map.value.getCanvas().style.cursor = 'pointer';
            });
            e.addEventListener('mouseleave', () => {
                map.value.getCanvas().style.cursor = '';
            });
        }
        userMarker.on('dragend', () => {
            const lngLat = userMarker!.getLngLat();
            popup.lng = lngLat.lng;
            popup.lat = lngLat.lat;
            form.latitude = Number(lngLat.lat.toFixed(7));
            form.longitude = Number(lngLat.lng.toFixed(7));
        });
    });
}

onMounted(() => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const { latitude, longitude } = pos.coords;

                initMap([longitude, latitude]);
            },
            (err) => {
                console.warn('Géolocalisation indisponible ou refusée :', err);
                initMap([5.510639, 50.61063]);
            },
            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 },
        );
    } else {
        initMap([5.510639, 50.61063]);
    }
});

onUnmounted(() => {
    map.value?.remove();
});

const form = useForm({
    name: '',
    license: '',
    url: '',
    latitude: 0,
    longitude: 0,
    species: [] as number[],
    environement: <string[]>[],
    rules: <string[]>[],
    is_public: 1,
    equipments: <string[]>[],
});

// FormModal
const closePopupForm = () => {
    popupContent.visible = false;
};

// PositionModal
const openPositionModal = () => {
    popupContent.visible = true;
    popupContent.lng = popup.lng;
    popupContent.lat = popup.lat;
    form.latitude = Number(popup.lat.toFixed(7));
    form.longitude = Number(popup.lng.toFixed(7));
};

const closePositionModal = () => {
    popup.visible = false;
    if (userMarker && markerVisible.value) {
        userMarker.getElement().style.display = 'none';
        markerVisible.value = false;
    }
};
</script>
<template>
    <Toaster
        position="top-center"
        closeButton
        closeButtonPosition="top-right"
        richColors
    />
    <Head title="Carte" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="map-wrap relative h-full w-full">
            <div class="map absolute inset-0 h-full w-full" ref="mapContainer">
                <PositionModal
                    v-model="popup.visible"
                    :lat="popup.lat.toFixed(6)"
                    :lng="popup.lng.toFixed(6)"
                    @open="openPositionModal"
                    @close="closePositionModal"
                />
                <transition name="background">
                    <div
                        class="absolute z-10 flex h-full w-full bg-gray-500/50 transition-opacity"
                        v-show="popupContent.visible"
                        @click.self="closePopupForm"
                    ></div>
                </transition>
                <FormModal
                    v-model:visible="popupContent.visible"
                    :lat="popup.lat"
                    :lng="popup.lng"
                    :species="species"
                />
                <SpotModal v-model="spotModalVisible" :spot="spotModalData" />
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
.background-enter-active,
.background-leave-active {
    transition: all 0.3s ease-in-out;
}
.background-enter-from,
.background-leave-to {
    opacity: 0;
}
.background-enter-to,
.background-leave-from {
    opacity: 1;
}
</style>

<!-- TODO : Hover : HoverCard avec infos spot ? -->
<!-- TODO : watchPosition() pour un suivi en temps réel -->
