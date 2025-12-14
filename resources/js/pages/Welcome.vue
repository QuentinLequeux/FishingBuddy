<script setup lang="ts">
import {
    Map,
    Eye,
    Fish,
    Plus,
    Gift,
    Heart,
    Share2,
    Trash2,
    Github,
    Linkedin,
    CloudSun,
    Activity,
    SquarePen,
    MapPinIcon,
    WandSparkles,
    UserRoundIcon,
    UserRoundPlus,
    MessagesSquare,
    SlidersHorizontal,
} from 'lucide-vue-next';
import 'vue-sonner/style.css';
import { onMounted } from 'vue';
import { toast, Toaster } from 'vue-sonner';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import ScrollButton from '@/components/global/ScrollButton.vue';
import { dashboard, feed, home, login, register } from '@/routes';
import { map, feed as activity, hero, hero2 } from '../assets/img';

onMounted(() => {
    toast.info('Offre de lancement : accès gratuit !', {
        duration: 20000,
        icon: Gift,
    });
});
</script>

<template>
    <Head title="Accueil" />
    <Toaster
        richColors
        position="bottom-center"
        closeButton
        closeButtonPosition="top-right"
    />
    <header class="fixed z-10 w-screen bg-white shadow-md dark:bg-sidebar">
        <h1 aria-level="1" role="heading" class="sr-only">FishingBuddy</h1>
        <nav
            class="m-auto flex w-[80%] items-center justify-between py-4 max-sm:w-[90%]"
        >
            <h2 aria-level="2" role="heading" class="sr-only">
                Navigation principale
            </h2>
            <Link
                class="flex items-center gap-2"
                :href="home()"
                title="Vers la page d'accueil"
            >
                <Fish :size="32" class="text-main" />
                <span class="text-lg font-semibold"> FishingBuddy </span>
            </Link>
            <div class="flex gap-8 max-sm:hidden">
                <Link
                    href="#map"
                    class="font-medium text-gray-500 hover:text-black"
                    title="Carte"
                >
                    Carte
                </Link>
                <Link
                    href="#feed"
                    class="font-medium text-gray-500 hover:text-black"
                    title="Activité"
                >
                    Activit&eacute;
                </Link>
            </div>
            <Button
                v-if="$page.props.auth.user"
                as-child
                class="main-button"
                title="Dashboard"
            >
                <Link :href="dashboard()"> Dashboard </Link>
            </Button>
            <div v-else class="flex gap-2">
                <Button as-child variant="ghost" title="S'inscrire">
                    <Link :href="register()"> S&rsquo;inscrire </Link>
                </Button>
                <Button as-child class="main-button" title="Se connecter">
                    <Link :href="login()"> Se connecter </Link>
                </Button>
            </div>
        </nav>
    </header>
    <main class="m-auto pt-18">
        <section class="m-auto my-16 flex w-[80%] flex-col items-center gap-16">
            <h2
                aria-level="2"
                role="heading"
                class="text-center font-montserrat text-5xl font-bold max-lg:text-4xl"
            >
                Mappez vos <span class="text-main">spots</span
                ><br class="mb-4" />
                Partagez vos <span class="text-main">prises</span
                ><br class="mb-4" />
                Devenez un <span class="text-main">FishingBuddy&nbsp;!</span>
            </h2>
            <Button
                as-child
                class="main-button"
                size="lg"
                title="S'inscrire gratuitement"
            >
                <Link :href="register()"> S&rsquo;inscrire gratuitement </Link>
            </Button>
            <img
                class="shadow-lg rounded-xl"
                :src="hero"
                alt="Fonctionnalité dashboard"
                width="1200"
                height="628"
                :srcset="`
                ${hero} 1x,
                ${hero2} 2x`"
            />
        </section>
        <section class="scroll-mt-24 bg-main p-16 max-sm:px-0" id="map">
            <div class="m-auto flex items-center w-[80%] gap-16 max-xl:flex-wrap">
                <img
                    class="rounded-xl max-w-[600px] min-w-[300px]"
                    :src="map"
                    alt="Fonctionnalité carte"
                    loading="lazy"
                    width="1081"
                    height="566"
                />
                <div class="flex w-fit max-w-full flex-col gap-8">
                    <h3
                        aria-level="3"
                        role="heading"
                        class="text-3xl font-semibold text-white"
                    >
                        Enregistrez vos lieux de pêche favoris et retrouvez-les
                        en un instant<WandSparkles
                            class="ml-4 inline text-yellow-500"
                        />
                    </h3>
                    <p class="text-white leading-6.5">
                        Avec la carte interactive de FishingBuddy, gardez une
                        trace pr&eacute;cise de chaque spot : localisation,
                        permis, type de poisson, environnement,
                        r&egrave;glementations, &eacute;quipements et
                        acc&egrave;s. Que vous d&eacute;couvriez un nouveau coin
                        ou que vous reveniez sur un lieu mythique, tout est
                        centralis&eacute; et accessible depuis votre compte. Vos
                        spots deviennent votre v&eacute;ritable carnet de pêche
                        num&eacute;rique, organis&eacute; et toujours &agrave;
                        port&eacute;e de main.
                    </p>
                    <div class="flex h-[80px] gap-6 overflow-x-auto">
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full bg-white p-5 shadow-md"
                            >
                                <Map />
                            </Button>
                            <span class="text-xs text-white"> Carte </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full bg-white p-5 shadow-md"
                            >
                                <MapPinIcon />
                            </Button>
                            <span class="text-xs text-white"> Spots </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full bg-white p-5 shadow-md"
                            >
                                <CloudSun />
                            </Button>
                            <span class="text-xs text-white">
                                M&eacute;t&eacute;o
                            </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full bg-white p-5 shadow-md"
                            >
                                <Plus />
                            </Button>
                            <span class="text-xs text-white"> Ajouter </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full bg-white p-5 shadow-md"
                            >
                                <SquarePen />
                            </Button>
                            <span class="text-xs text-white"> Modifier </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full bg-white p-5 shadow-md"
                            >
                                <Trash2 />
                            </Button>
                            <span class="text-xs text-white"> Supprimer </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full bg-white p-5 shadow-md"
                            >
                                <Share2 />
                            </Button>
                            <span class="text-xs text-white"> Partager </span>
                        </div>
                    </div>
                    <Button
                        as-child
                        class="w-fit bg-white text-black hover:bg-gray-300"
                        title="Consulter la carte"
                    >
                        <Link :href="login()"> Consulter la carte </Link>
                    </Button>
                </div>
            </div>
        </section>
        <section class="scroll-mt-24 p-16 max-sm:px-0" id="feed">
            <div class="m-auto flex items-center w-[80%] gap-16 max-xl:flex-wrap">
                <div class="flex w-fit max-w-full flex-col gap-8">
                    <h3
                        aria-level="3"
                        role="heading"
                        class="text-3xl font-semibold"
                    >
                        Partagez vos prises, d&eacute;couvrez celles des autres
                        et inspirez la communaut&eacute;<Fish class="inline ml-4 text-main" />
                    </h3>
                    <p class="leading-6.5">
                        La pêche, c’est aussi des histoires. Sur le feed
                        FishingBuddy, publiez vos prises. Interagissez avec
                        d’autres passionn&eacute;s, suivez leurs activit&eacute;s et d&eacute;couvrez
                        de nouveaux spots grâce aux retours de la communaut&eacute;. Un
                        espace vivant, pens&eacute; pour connecter les pêcheurs entre eux.
                    </p>
                    <div class="flex h-[80px] gap-6 overflow-x-auto">
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <Activity />
                            </Button>
                            <span class="text-xs text-gray-500">
                                Activit&eacute;
                            </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <Heart />
                            </Button>
                            <span class="text-xs text-gray-500"> Like </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <UserRoundIcon />
                            </Button>
                            <span class="text-xs text-gray-500"> Profil </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <UserRoundPlus />
                            </Button>
                            <span class="text-xs text-gray-500"> Follow </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <SlidersHorizontal />
                            </Button>
                            <span class="text-xs text-gray-500"> Filtres </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <Share2 />
                            </Button>
                            <span class="text-xs text-gray-500">
                                Partager
                            </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <Eye />
                            </Button>
                            <span class="text-xs text-gray-500">Vues</span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <Button
                                variant="ghost"
                                class="rounded-full p-5 shadow-md"
                            >
                                <MessagesSquare />
                            </Button>
                            <span class="text-xs text-gray-500">
                                Commentaire
                            </span>
                        </div>
                    </div>
                    <Button
                        as-child
                        class="main-button w-fit"
                        title="Consulter les prises"
                    >
                        <Link :href="feed()"> Consulter les prises </Link>
                    </Button>
                </div>
                <img
                    class="rounded-xl max-w-[600px] min-w-[300px] shadow-lg"
                    :src="activity"
                    alt="Fonctionnalité feed"
                    loading="lazy"
                    width="1081"
                    height="566"
                />
            </div>
        </section>
        <section
            class="banner flex w-full flex-col items-center gap-8 py-30 max-sm:py-16"
        >
            <h3
                aria-level="3"
                role="heading"
                class="text-5xl font-semibold text-white text-shadow-lg"
            >
                Sortez pêcher&nbsp;!
            </h3>
            <Button
                as-child
                class="w-fit bg-white text-black hover:bg-gray-300"
            >
                <Link :href="register()" title="S'inscrire gratuitement">
                    S&rsquo;inscrire gratuitement
                </Link>
            </Button>
        </section>
    </main>
    <footer class="m-auto w-[80%]">
        <div class="my-8 flex justify-between gap-2">
            <Link
                href="#"
                title="Vers la page d'accueil"
                class="flex items-center gap-2 font-medium"
            >
                <Fish class="text-main" />
                FishingBuddy
            </Link>
            <div class="flex gap-2">
                <Button as-child class="main-button rounded-full !p-2.5">
                    <a
                        href="https://github.com/QuentinLequeux"
                        target="_blank"
                        title="Vers le profil GitHub de Quentin Lequeux"
                    >
                        <Github class="text-white" />
                    </a>
                </Button>
                <Button as-child class="main-button rounded-full !p-2.5">
                    <a
                        href="https://www.linkedin.com/in/quentin-lequeux-8a11a3192/"
                        target="_blank"
                        title="Vers le profil LinkedIn de Quentin Lequeux"
                    >
                        <Linkedin class="text-white" />
                    </a>
                </Button>
            </div>
        </div>
        <Separator />
        <div class="my-8 flex justify-between gap-2">
            <p class="text-sm text-gray-500">© 2025 Quentin Lequeux.</p>
            <Link
                class="text-sm text-gray-500 hover:underline"
                href="#"
                title="Vers les conditions d'utilisation"
            >
                Conditions d&rsquo;utilisation
            </Link>
        </div>
    </footer>
    <ScrollButton />
</template>

<!-- TODO : Langues ? -->
<!-- TODO : Dark mode -->
