import { Ref } from 'vue';
import { route } from 'ziggy-js';
import { ISpot } from '@/types/ISpot';
import { Marker } from '@maptiler/sdk';
import { useForm } from '@inertiajs/vue3';

export function useFishMarkers(
    map: Ref,
    spotModalData: Ref<ISpot | null>,
    spotModalVisible: Ref<boolean>,
) {
    const form = useForm({
        latitude: 0,
        longitude: 0,
    });

    const markers = new Map<number, Marker>();

    function createFishMarkers (spots: ISpot[]) {
        if (!map.value) return;

        const fishSVG = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#fff" stroke="#1D4999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-fish-icon lucide-fish"><path d="M6.5 12c.94-3.46 4.94-6 8.5-6 3.56 0 6.06 2.54 7 6-.94 3.47-3.44 6-7 6s-7.56-2.53-8.5-6Z"/><path d="M18 12v.5"/><path d="M16 17.93a9.77 9.77 0 0 1 0-11.86"/><path d="M7 10.67C7 8 5.58 5.97 2.73 5.5c-1 1.5-1 5 .23 6.5-1.24 1.5-1.24 5-.23 6.5C5.58 18.03 7 16 7 13.33"/><path d="M10.46 7.26C10.2 5.88 9.17 4.24 8 3h5.8a2 2 0 0 1 1.98 1.67l.23 1.4"/><path d="m16.01 17.93-.23 1.4A2 2 0 0 1 13.8 21H9.5a5.96 5.96 0 0 0 1.49-3.98"/></svg>`;

        spots.forEach((spot) => {
            if (markers.has(spot.id)) {
                markers.get(spot.id)!.setLngLat([spot.longitude, spot.latitude]);
                return;
            }

            const el = document.createElement('div');
            el.innerHTML = fishSVG;
            el.style.cursor = 'pointer';

            const marker = new Marker({ element: el, draggable: true })
                .setLngLat([spot.longitude, spot.latitude])
                .addTo(map.value);

            marker.getElement().addEventListener('click', (e: MouseEvent) => {
                e.stopPropagation();
                spotModalData.value = spot;
                spotModalVisible.value = true;
            });

            marker.on('dragend', () => {
                const lngLat = marker.getLngLat();
                spot.latitude = lngLat.lat;
                spot.longitude = lngLat.lng;

                form.latitude = spot.latitude;
                form.longitude = spot.longitude;
                form.patch(route('map.position.update', spot.id))
            });

            markers.set(spot.id, marker);
        });
    }
    return { createFishMarkers }
}
