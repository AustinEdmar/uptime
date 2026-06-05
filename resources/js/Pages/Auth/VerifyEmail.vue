<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Verificação de Email" />

        <!-- VOLTAR -->
        <div class="mb-6">
            <Link
                :href="route('login')"
                class="inline-flex items-center gap-2 text-sm font-medium bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] bg-clip-text text-transparent transition hover:from-[#495AFF] hover:to-[#66A6FF]"
            >
                <svg class="h-4 w-4 text-[#495AFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 19l-7-7 7-7" />
                </svg>

                Voltar para login
            </Link>
        </div>

        <!-- TITLE -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">
                Verifique seu email
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Confirmar email é necessário para continuar
            </p>
        </div>

        <!-- MESSAGE -->
        <div class="mb-4 text-sm text-gray-600 leading-relaxed">
            Obrigado por se registrar! Antes de começar, por favor verifique seu email
            clicando no link que enviamos. Caso não tenha recebido, podemos reenviar.
        </div>

        <!-- SUCCESS -->
        <div
            v-if="verificationLinkSent"
            class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700"
        >
            Um novo link de verificação foi enviado para o seu email.
        </div>

        <!-- ACTIONS -->
        <form @submit.prevent="submit" class="space-y-4">

            <div class="flex items-center justify-between">

                <!-- RESEND -->
                <PrimaryButton
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    Reenviar email de verificação
                </PrimaryButton>

                <!-- LOGOUT -->
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-gray-500 hover:text-gray-900 transition underline"
                >
                    Sair
                </Link>

            </div>

        </form>
    </GuestLayout>
</template>