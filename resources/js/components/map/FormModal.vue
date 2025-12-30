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
import { useFilter } from 'reka-ui';
import { computed, ref } from 'vue';
import {
    TagsInput,
    TagsInputItem,
    TagsInputInput,
    TagsInputItemText,
    TagsInputItemDelete,
} from '@/components/ui/tags-input';
import { X, Plus } from 'lucide-vue-next';
import { ISpecie } from '@/types/ISpecie';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Form, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Separator } from '@/components/ui/separator';
import HoverCardForm from '@/components/map/HoverCardForm.vue';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';

const props = defineProps<{
    lat: number;
    lng: number;
    visible: boolean;
    species: ISpecie[];
}>();

const open = ref(false);
const searchTerm = ref('');
const selectedSpecies = ref<number[]>([]);
const { contains } = useFilter({ sensitivity: 'base' });
const emit = defineEmits(['update:visible', 'saved']);

const form = useForm({
    name: '',
    license: '',
    url: '',
    latitude: 0,
    longitude: 0,
    species: [] as number[],
    environement: <string[]>[],
    rules: <string[]>[],
    is_public: '1',
    equipments: <string[]>[],
});

watch(
    () => props.visible,
    (val) => {
        if (val) {
            form.latitude = Number((props.lat ?? 0).toFixed(7));
            form.longitude = Number((props.lng ?? 0).toFixed(7));
            selectedSpecies.value = [...form.species];
        }
    },
);

const filteredSpecies = computed(() => {
    const options = (props.species || []).filter(
        (s) => !selectedSpecies.value.includes(s.id),
    );

    if (!searchTerm.value) return options;
    return options.filter((s) => contains(s.name, searchTerm.value));
});

const submit = () => {
    form.species = [...selectedSpecies.value];
    form.post(route('map.store'), {
        onSuccess: () => {
            emit('update:visible', false);
            emit('saved');
            toast.success('Spot crée');
            form.reset();
        },
    });
};

const close = () => {
    emit('update:visible', false);
};

const specieNameById = computed(() => {
    const map = new Map<number, string>();
    props.species.forEach((s) => {
        map.set(s.id, s.name);
    });
    return map;
});
</script>

<template>
    <transition name="create">
        <div
            v-if="props.visible"
            class="absolute top-5 left-1/2 z-10 flex max-h-full max-w-[500px] min-w-[400px] -translate-x-1/2 flex-col gap-4 self-center overflow-y-auto rounded-xl bg-white p-4 shadow-lg max-sm:min-w-[90%] dark:bg-sidebar"
        >
            <div
                title="Fermer"
                class="absolute top-0 right-0 m-2 hover:cursor-pointer hover:text-main"
            >
                <X :size="24" @click="close" />
            </div>
            <h2 aria-level="2" role="heading" class="text-xl font-bold">
                Ajouter un lieu
            </h2>
            <Separator />
            <p>
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
                        <Label for="name"
                            >Nom<span class="text-[#fc5a5a]">*</span></Label
                        >
                        <HoverCardForm>
                            Minimum 3 caract&egrave;res.<br />
                            Maximum 100 caract&egrave;res.
                        </HoverCardForm>
                    </div>
                    <Input
                        required
                        placeholder="Rivière"
                        id="name"
                        type="text"
                        name="name"
                        v-model="form.name"
                    />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <Label for="license">Permis</Label>
                        <HoverCardForm>
                            Minimum 3 caract&egrave;res.<br />
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
                        <Label for="url"> URL </Label>
                        <HoverCardForm>
                            Format&nbsp;: https://example.com
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
                    <Label for="specie" class="w-fit">Esp&egrave;ce</Label>
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
                                        <span class="p-2">
                                            {{ specieNameById.get(id) }}
                                        </span>
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
                                        placeholder="Espèce"
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
                    <InputError :message="form.errors.species" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="env" class="w-fit">Environement</Label>
                    <TagsInput v-model="form.environement">
                        <TagsInputItem
                            v-for="item in form.environement"
                            :key="item"
                            :value="item"
                        >
                            <TagsInputItemText />
                            <TagsInputItemDelete />
                        </TagsInputItem>
                        <TagsInputInput placeholder="Sable" id="env" />
                    </TagsInput>
                    <InputError :message="form.errors.environement" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="rules" class="w-fit">R&egrave;glementations</Label>
                    <TagsInput v-model="form.rules">
                        <TagsInputItem
                            v-for="item in form.rules"
                            :key="item"
                            :value="item"
                        >
                            <TagsInputItemText />
                            <TagsInputItemDelete />
                        </TagsInputItem>
                        <TagsInputInput placeholder="No kill" id="rules" />
                    </TagsInput>
                    <InputError :message="form.errors.rules" />
                </div>
                <div class="flex flex-col gap-2">
                    <p class="text-sm font-medium">Acc&egrave;s</p>
                    <RadioGroup
                        class="flex items-center gap-2"
                        name="is_public"
                        v-model="form.is_public"
                    >
                        <RadioGroupItem value="1" id="1" />
                        <Label for="1" class="hover:text-main">Public</Label>
                        <RadioGroupItem value="0" id="0" />
                        <Label for="0" class="hover:text-main">Priv&eacute;</Label>
                    </RadioGroup>
                </div>
                <div class="flex flex-col gap-2">
                    <p class="text-sm font-medium">Equipements</p>
                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="2"
                            name="equipments"
                            value="Parking"
                            v-model="form.equipments"
                        />
                        <Label for="2" class="hover:text-main">Parking</Label>
                        <input
                            type="checkbox"
                            id="3"
                            name="equipments"
                            value="Toilette"
                            v-model="form.equipments"
                        />
                        <Label for="3" class="hover:text-main">Toilette</Label>
                        <input
                            type="checkbox"
                            id="4"
                            name="equipments"
                            value="PMR"
                            v-model="form.equipments"
                        />
                        <Label for="4" class="hover:text-main">PMR</Label>
                        <input
                            type="checkbox"
                            id="5"
                            name="equipments"
                            value="Restauration"
                            v-model="form.equipments"
                        />
                        <Label for="5" class="hover:text-main">Restauration</Label>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-2">
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
                        class="main-button"
                        :disabled="processing"
                        title="Ajouter"
                    >
                        <Plus />Ajouter
                    </Button>
                </div>
            </Form>
        </div>
    </transition>
</template>

<style scoped>
.create-enter-active,
.create-leave-active {
    transition: all 0.3s ease-in-out;
}
.create-enter-from,
.create-leave-to {
    opacity: 0;
    transform: scale(0.5);
}
.create-enter-to,
.create-leave-from {
    opacity: 1;
    transform: scale(1);
}
</style>
