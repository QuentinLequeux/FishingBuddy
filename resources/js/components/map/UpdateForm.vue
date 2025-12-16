<script setup lang="ts">
import { watch } from 'vue';
import { route } from 'ziggy-js';
import {
    Combobox,
    ComboboxItem,
    ComboboxList,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxAnchor,
} from '@/components/ui/combobox';
import { toast } from 'vue-sonner';
import { computed, ref } from 'vue';
import {
    TagsInput,
    TagsInputItem,
    TagsInputInput,
    TagsInputItemText,
    TagsInputItemDelete,
} from '@/components/ui/tags-input';
import { X } from 'lucide-vue-next';
import { useFilter } from 'reka-ui';
import { ISpot } from '@/types/ISpot';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Form, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Separator } from '@/components/ui/separator';
import HoverCardForm from '@/components/map/HoverCardForm.vue';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';

const props = defineProps<{
    visible: boolean;
    lat: number;
    lng: number;
    species: { id: number; name: string }[];
    spot: ISpot | null;
}>();

const emit = defineEmits(['update:visible', 'saved']);

const close = () => {
    emit('update:visible', false);
};

const open = ref(false);
const searchTerm = ref('');
const selectedSpecies = ref<number[]>([]);
const { contains } = useFilter({ sensitivity: 'base' });

const filteredSpecies = computed(() => {
    const options = (props.species || []).filter(
        (s) => !selectedSpecies.value.includes(s.id),
    );

    if (!searchTerm.value) return options;
    return options.filter((s) => contains(s.name, searchTerm.value));
});

const form = useForm({
    latitude: 0,
    longitude: 0,
    name: '',
    license: '',
    url: '',
    species: [] as number[],
    environement: <string[]>[],
    rules: <string[]>[],
    is_public: '1',
    equipments: <string[]>[],
});

watch(
    () => props.visible,
    (val) => {
        if (val && props.spot) {
            form.latitude = Number((props.lat ?? 0).toFixed(7));
            form.longitude = Number((props.lng ?? 0).toFixed(7));
            form.name = props.spot.name ?? '';
            form.license = props.spot.license ?? '';
            form.url = props.spot.url ?? '';
            form.species = [...selectedSpecies.value];
            selectedSpecies.value = [...form.species];
            selectedSpecies.value = props.spot?.species.map((s) => s.id) ?? [];
            form.environement = props.spot.environement ?? '';
            form.rules = props.spot.rules ?? '';
            form.is_public = props.spot?.is_public ? '1' : '0';
            form.equipments = props.spot.equipments ?? [];
        }
    },
);

const submit = () => {
    form.species = [...selectedSpecies.value];
    if (props.spot) {
        form.post(route('map.update', props.spot.id), {
            onSuccess: () => {
                const updatedSpot = form.data();
                emit('update:visible', false);
                emit('saved', updatedSpot);
                toast.success('Spot mis à jour');
            },
        });
    }
};
</script>

<template>
    <transition name="opacity">
        <div
            class="absolute z-20 flex h-full w-full bg-gray-500/50"
            v-show="visible"
            @click.self="close"
        ></div>
    </transition>
    <transition name="overlay">
        <div
            v-if="props.visible"
            class="absolute top-4 left-1/2 z-20 flex max-h-[96%] max-w-[500px] min-w-[400px] -translate-x-1/2 transform flex-col gap-4 self-center overflow-y-auto rounded-xl bg-white p-4 shadow-lg"
        >
            <X
                :size="24"
                class="absolute top-2 right-0 m-2 text-black hover:cursor-pointer hover:text-main"
                @click="close"
            />
            <h2
                aria-level="2"
                role="heading"
                class="text-xl font-bold text-black"
            >
                Modifier un lieu
            </h2>
            <Separator />
            <p class="text-xs text-black">
                Les champs
                <span class="text-[#fc5a5a]">*</span> sont obligatoires
            </p>
            <Form
                class="flex flex-col gap-4"
                @submit.prevent="submit"
                v-slot="{ processing }"
            >
                <Input
                    type="hidden"
                    name="longitude"
                    :value="props.lng.toFixed(6)"
                    v-model="form.longitude"
                />
                <Input
                    type="hidden"
                    name="latitude"
                    :value="props.lat.toFixed(6)"
                    v-model="form.latitude"
                />
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <Label class="text-black" for="name">
                            Nom
                            <span class="text-[#fc5a5a]">*</span>
                        </Label>
                        <HoverCardForm>
                            Maximum 100 caract&egrave;res.
                        </HoverCardForm>
                    </div>
                    <Input
                        required
                        placeholder="Example"
                        id="name"
                        type="text"
                        name="name"
                        v-model="form.name"
                    />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <Label class="text-black" for="license"> Permis </Label>
                        <HoverCardForm>
                            Maximum 100 caract&egrave;res.
                        </HoverCardForm>
                    </div>
                    <Input
                        placeholder="Les amis de la Mehaigne"
                        id="license"
                        name="license"
                        type="text"
                        v-model="form.license"
                    />
                    <InputError :message="form.errors.license" />
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <Label class="text-black" for="url"> URL </Label>
                        <HoverCardForm>
                            Format : https://example.com
                        </HoverCardForm>
                    </div>
                    <Input
                        placeholder="https://www.example.com"
                        id="url"
                        name="url"
                        type="text"
                        v-model="form.url"
                    />
                    <InputError :message="form.errors.url" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label class="text-black" for="specie">Esp&egrave;ce</Label>
                    <Combobox
                        v-model="selectedSpecies"
                        v-model:open="open"
                        :ignore-filter="true"
                        by="id"
                    >
                        <ComboboxAnchor as-child>
                            <TagsInput v-model="selectedSpecies" class="w-full">
                                <div class="flex flex-wrap items-center gap-2">
                                    <TagsInputItem
                                        v-for="id in selectedSpecies"
                                        :key="id"
                                        :value="id.toString()"
                                    >
                                        <TagsInputItemText />
                                        <TagsInputItemDelete
                                            @click="
                                                () =>
                                                    (selectedSpecies =
                                                        selectedSpecies.filter(
                                                            (s) => s !== id,
                                                        ))
                                            "
                                        />
                                    </TagsInputItem>
                                </div>
                                <ComboboxInput v-model="searchTerm" as-child>
                                    <TagsInputInput
                                        id="specie"
                                        placeholder="Espèce..."
                                        class="h-auto border-none p-0 shadow-none focus-visible:ring-0"
                                    />
                                </ComboboxInput>
                                <ComboboxList>
                                    <ComboboxEmpty />
                                    <ComboboxGroup>
                                        <ComboboxItem
                                            v-for="opt in filteredSpecies"
                                            :key="opt.id"
                                            :value="opt.id"
                                            @select.prevent="
                                                () => {
                                                    if (
                                                        !selectedSpecies.includes(
                                                            opt.id,
                                                        )
                                                    )
                                                        selectedSpecies.push(
                                                            opt.id,
                                                        );
                                                    searchTerm = '';
                                                    open = false;
                                                }
                                            "
                                            class="flex cursor-pointer items-center justify-between px-2 py-1"
                                        >
                                            {{ opt.name }}
                                        </ComboboxItem>
                                    </ComboboxGroup>
                                </ComboboxList>
                            </TagsInput>
                        </ComboboxAnchor>
                    </Combobox>
                </div>
                <div class="flex flex-col gap-2">
                    <Label class="text-black" for="environement"
                        >Environement</Label
                    >
                    <TagsInput v-model="form.environement">
                        <TagsInputItem
                            v-for="item in form.environement"
                            :key="item"
                            :value="item"
                        >
                            <TagsInputItemText />
                            <TagsInputItemDelete />
                        </TagsInputItem>
                        <TagsInputInput id="environement" placeholder="Sable" />
                    </TagsInput>
                </div>
                <div class="flex flex-col gap-2">
                    <Label class="text-black" for="rules"
                        >R&egrave;glementations</Label
                    >
                    <TagsInput v-model="form.rules">
                        <TagsInputItem
                            v-for="item in form.rules"
                            :key="item"
                            :value="item"
                        >
                            <TagsInputItemText />
                            <TagsInputItemDelete />
                        </TagsInputItem>
                        <TagsInputInput id="rules" placeholder="No kill" />
                    </TagsInput>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="text-sm font-medium text-black">
                        Acc&egrave;s
                    </div>
                    <RadioGroup
                        class="flex items-center gap-2"
                        name="is_public"
                        v-model="form.is_public"
                    >
                        <RadioGroupItem
                            class="border-black text-black"
                            value="1"
                            id="1"
                        />
                        <Label class="text-black" for="1">Public</Label>
                        <RadioGroupItem
                            class="border-black text-black"
                            value="0"
                            id="0"
                        />
                        <Label class="text-black" for="0">Priv&eacute;</Label>
                    </RadioGroup>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="mt-2 text-sm font-medium text-black">
                        Equipements
                    </div>
                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="2"
                            name="equipments"
                            value="Parking"
                            v-model="form.equipments"
                        />
                        <Label class="text-black" for="2">Parking</Label>
                        <input
                            type="checkbox"
                            id="3"
                            name="equipments"
                            value="Toilette"
                            v-model="form.equipments"
                        />
                        <Label class="text-black" for="3">Toilette</Label>
                        <input
                            type="checkbox"
                            id="4"
                            name="equipments"
                            value="PMR"
                            v-model="form.equipments"
                        />
                        <Label for="5">PMR</Label>
                        <input
                            type="checkbox"
                            id="5"
                            name="equipments"
                            value="Restauration"
                            v-model="form.equipments"
                        />
                        <Label for="5">Restauration</Label>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        @click="close"
                        title="Fermer"
                    >
                        Fermer
                    </Button>
                    <Button
                        type="submit"
                        class="bg-main text-white hover:bg-main/90"
                        :disabled="processing"
                        title="Modifier"
                    >
                        Modifier
                    </Button>
                </div>
            </Form>
        </div>
    </transition>
</template>

<style scoped>
.overlay-enter-active,
.overlay-leave-active {
    transition: all 0.3s ease-in-out;
}
.overlay-enter-from,
.overlay-leave-to {
    opacity: 0;
    transform: scale(0.5);
}
.overlay-enter-to,
.overlay-leave-from {
    opacity: 1;
    transform: scale(1);
}
.opacity-enter-active,
.opacity-leave-active {
    transition: all 0.3s ease-in-out;
}
.opacity-enter-from,
.opacity-leave-to {
    opacity: 0;
}
.opacity-enter-to,
.opacity-leave-from {
    opacity: 1;
}
</style>

<!-- TODO : dark mode -->
<!-- TODO : Style recherche TagsInput -->
<!-- TODO : name, pas id dans combobox -->
<!-- TODO : Reload page after submission -->
<!-- TODO : Component : Radio, checkbox,... -->
<!-- TODO : Afficher toutes les espèces dans combobox -->
