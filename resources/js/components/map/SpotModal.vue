<script setup lang="ts">
import {
    X,
    Car,
    Lock,
    Share2,
    LockOpenIcon,
    ExternalLinkIcon,
} from 'lucide-vue-next';
import { ISpot } from '@/types/ISpot';
import { Badge } from '@/components/ui/badge';
import { defineEmits, defineProps, ref } from 'vue';
import { Separator } from '@/components/ui/separator';
import UpdateForm from '@/components/map/UpdateForm.vue';
import PopoverSpot from '@/components/map/PopoverSpot.vue';

const props = defineProps<{
    modelValue: boolean;
    spot: ISpot | null;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const close = () => {
    emit('update:modelValue', false);
};

const showForm = ref(false);

const onSpotSaved = (updatedSpot: ISpot) => {
    if (props.spot) {
        Object.assign(props.spot, updatedSpot);
    }
    showForm.value = false;
}
</script>
<template>
    <transition name="fade">
        <div
            v-if="modelValue && spot"
            class="absolute right-14 z-10 my-2 flex h-[96%] w-[400px] flex-col gap-2 rounded-2xl bg-white p-4 shadow-lg max-sm:w-[83%] dark:bg-sidebar overflow-y-scroll"
        >
            <PopoverSpot :spot="spot" @edit="showForm = true" />
            <X
                :size="24"
                class="absolute top-2 right-2 m-1 hover:cursor-pointer hover:text-main dark:text-white"
                @click="close"
            />
            <h2
                aria-level="2"
                role="heading"
                class="my-4 text-xl font-bold dark:text-white"
            >
                {{ spot.name }}
            </h2>
            <div class="flex gap-4">
                <a
                    :href="`https://www.google.com/maps/dir//${spot.latitude},${spot.longitude}`"
                    class="w-fit rounded-full bg-main p-2 hover:bg-main/90"
                    target="_blank"
                    title="Vers la carte Google Maps"
                >
                    <Car color="#fff" />
                </a>
                <a
                    href="#"
                    class="w-fit rounded-full bg-main p-2 hover:bg-main/90"
                    target="_blank"
                    title=""
                >
                    <Share2 color="#fff" />
                </a>
            </div>
            <h3
                aria-level="3"
                role="heading"
                class="text-lg font-semibold dark:text-white"
            >
                Permis
            </h3>
            <div v-if="spot?.url?.length">
                <a
                    :href="spot.url"
                    class="font-base mb-1 flex w-fit items-center gap-2 text-xs hover:underline"
                    target="_blank"
                    :title="`Vers le site web ${spot.url}`"
                >
                    {{ spot.license }}
                    <ExternalLinkIcon :size="16" />
                </a>
            </div>
            <p v-else class="text-black dark:text-white">Aucun permis.</p>
            <Separator />
            <div v-if="spot?.species?.length">
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    Esp&egrave;ces
                </h3>
                <div class="my-2 flex flex-wrap gap-2">
                    <Badge
                        v-for="specie in spot.species"
                        :key="specie.id"
                        class="bg-main text-white"
                    >
                        {{ specie.name }}
                    </Badge>
                </div>
            </div>
            <div v-else>
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    Esp&egrave;ces
                </h3>
                <p class="text-black dark:text-white">Aucune esp&egrave;ce.</p>
            </div>
            <Separator />
            <div v-if="spot?.environement?.length">
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    Environement
                </h3>
                <div class="my-2 flex flex-wrap gap-2">
                    <Badge
                        v-for="(env, index) in spot.environement"
                        :key="index"
                        class="bg-main text-white"
                    >
                        {{ env }}
                    </Badge>
                </div>
            </div>
            <div v-else>
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    Environement
                </h3>
                <p class="text-black dark:text-white">Aucun environement.</p>
            </div>
            <Separator />
            <div v-if="spot?.rules?.length">
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    R&egrave;glementations
                </h3>
                <div class="my-2 flex flex-wrap gap-2">
                    <Badge
                        v-for="(r, index) in spot.rules"
                        :key="index"
                        class="bg-main text-white"
                    >
                        {{ r }}
                    </Badge>
                </div>
            </div>
            <div v-else>
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    R&egrave;glementations
                </h3>
                <p class="text-black dark:text-white">
                    Aucune r&egrave;glementation.
                </p>
            </div>
            <Separator />
            <div v-if="spot?.equipments?.length">
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    Équipements
                </h3>
                <div class="my-2 flex flex-wrap gap-2">
                    <Badge
                        v-for="(e, index) in spot.equipments"
                        :key="index"
                        class="bg-main text-white"
                    >
                        {{ e }}
                    </Badge>
                </div>
            </div>
            <div v-else>
                <h3
                    aria-level="3"
                    role="heading"
                    class="text-lg font-semibold dark:text-white"
                >
                    Équipements
                </h3>
                <p class="text-black dark:text-white">
                    Aucun &eacute;quipements.
                </p>
            </div>
            <Separator />
            <h3
                aria-level="3"
                role="heading"
                class="text-lg font-semibold dark:text-white"
            >
                Acc&egrave;s
            </h3>
            <p
                v-if="spot.is_public"
                class="flex items-center gap-2 text-sm dark:text-white"
            >
                <LockOpenIcon />
                Public
            </p>
            <p v-else class="flex items-center gap-2 text-sm dark:text-white">
                <Lock />
                Priv&eacute;
            </p>
        </div>
    </transition>
    <UpdateForm
        :visible="showForm"
        :spot="spot"
        :lat="Number(spot?.latitude ?? 0)"
        :lng="Number(spot?.longitude ?? 0)"
        :species="spot?.species ?? []"
        @update:visible="showForm = $event"
        @saved="onSpotSaved"
    />
</template>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.5s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateX(500px);
}
</style>

<!-- TODO : Météo ? -->
<!-- TODO : Photo ? -->
<!-- TODO : Hover espèces : Taille minimale de prise, saison -->
<!-- TODO : Localisation en dessous du lieu ? (ex : Seraing, BE) -->
