<script setup lang="ts">
import {
    Select,
    SelectItem,
    SelectValue,
    SelectTrigger,
    SelectContent,
} from '@/components/ui/select';
import {
    Dialog,
    DialogTitle,
    DialogFooter,
    DialogHeader,
    DialogContent,
    DialogDescription,
} from '@/components/ui/dialog';
import { route } from 'ziggy-js';
import { toast } from 'vue-sonner';
import { ILure } from '@/types/ILure';
import { ISpecie } from '@/types/ISpecie';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Form, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import HoverCardForm from '@/components/map/HoverCardForm.vue';
import { WeightIcon, RulerDimensionLine } from 'lucide-vue-next';

const open = defineModel<boolean>({ required: true });
const props = defineProps<{ species: ISpecie[]; lures: ILure[] }>();

const form = useForm({
    specie_id: '',
    size: '',
    weight: '',
    lure_id: '',
});

const submit = () => {
    form.post(route('feed.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Publication créée !');
            form.reset();
            open.value = false;
        },
    });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent>
            <DialogHeader>
                <DialogTitle> Publication </DialogTitle>
                <DialogDescription> Publier votre prise </DialogDescription>
            </DialogHeader>
            <Form class="flex flex-col gap-6" @submit.prevent="submit">
                <div class="flex flex-col gap-2">
                    <Label for="specie">
                        Esp&egrave;ce
                        <span class="text-[#fc5a5a]">*</span>
                    </Label>
                    <Select v-model="form.specie_id" name="specie_id" required>
                        <SelectTrigger id="specie">
                            <SelectValue placeholder="Choisir une espèce" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="specie in props.species"
                                :value="specie.id"
                                :key="specie.id"
                                class="w-[250px]"
                            >
                                {{ specie.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.specie_id" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="lure">
                        Leurre
                        <span class="text-[#fc5a5a]">*</span>
                    </Label>
                    <Select v-model="form.lure_id" name="lure_id" required>
                        <SelectTrigger id="lure">
                            <SelectValue placeholder="Choisir un leurre" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="lure in props.lures"
                                :value="lure.id"
                                :key="lure.id"
                                class="w-[250px]"
                            >
                                {{ lure.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.lure_id" />
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex gap-2">
                        <Label for="size">
                            Taille
                            <span class="text-[#fc5a5a]">*</span>
                        </Label>
                        <HoverCardForm>
                            Comprise entre 1 cm et 200 cm.
                        </HoverCardForm>
                    </div>
                    <div class="relative">
                        <RulerDimensionLine
                            class="absolute top-2 left-3 size-5"
                        />
                        <Input
                            type="number"
                            id="size"
                            placeholder="Taille"
                            v-model="form.size"
                            name="size"
                            required
                            class="pl-12"
                        />
                        <span class="absolute top-1.5 right-12">cm</span>
                    </div>
                    <InputError :message="form.errors.size" />
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex gap-2">
                        <Label for="weight">
                            Poids
                            <span class="text-[#fc5a5a]">*</span>
                        </Label>
                        <HoverCardForm>
                            Compris entre 0.1 kg et 50 kg.
                        </HoverCardForm>
                    </div>
                    <div class="relative">
                        <WeightIcon class="absolute top-2 left-3 size-5" />
                        <Input
                            type="number"
                            id="weight"
                            placeholder="Poids"
                            step="0.1"
                            v-model="form.weight"
                            name="weight"
                            required
                            class="pl-12"
                        />
                        <span class="absolute top-1.5 right-12">kg</span>
                    </div>
                    <InputError :message="form.errors.weight" />
                </div>
                <DialogFooter>
                    <Button
                        variant="secondary"
                        title="Annuler"
                        type="button"
                        @click="open = false"
                    >
                        Annuler
                    </Button>
                    <Button class="main-button" title="Publier" type="submit">
                        Publier
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>

<!-- TODO : Composants ? -->
<!-- TODO : Chargement des commentaires ? -->
