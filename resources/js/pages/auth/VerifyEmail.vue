<script setup lang="ts">
import EmailVerificationNotificationController from '@/actions/App/Http/Controllers/Auth/EmailVerificationNotificationController';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { logout } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout
        title="Vérifier l'email"
        description="Veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer."
    >
        <Head title="Vérifier l'email" />

        <div
            v-if="status === 'verification-link-sent'"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            Un nouveau lien de v&eacute;rification a &eacute;t&eacute; envoy&eacute; &agrave; l'adresse email que vous avez fournie lors de l'inscription.
        </div>

        <Form
            v-bind="EmailVerificationNotificationController.store.form()"
            class="space-y-6 text-center"
            v-slot="{ processing }"
        >
            <Button :disabled="processing" class="bg-main hover:bg-main/90" title="Renvoyer l'email de vérification">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Renvoyer l'email de v&eacute;rification
            </Button>

            <TextLink
                :href="logout()"
                as="button"
                class="mx-auto block text-sm"
                title="Déconnexion"
            >
                D&eacute;connexion
            </TextLink>
        </Form>
    </AuthLayout>
</template>
