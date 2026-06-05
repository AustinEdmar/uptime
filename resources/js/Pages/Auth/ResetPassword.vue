<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
<template>
    <GuestLayout>
        <Head title="Redefinir senha" />

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

        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">
                Redefinir senha
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Crie uma nova senha para sua conta
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">

            <!-- EMAIL -->
            <div>
                <label class="mb-1.5 block text-xs font-medium uppercase text-gray-500">
                    E-mail
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>

                    <TextInput
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full pl-10"
                        required
                        autofocus
                        autocomplete="username"
                    />
                </div>

                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="mb-1.5 block text-xs font-medium uppercase text-gray-500">
                    Palavra-passe
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>

                    <TextInput
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="w-full pl-10"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- CONFIRM PASSWORD -->
            <div>
                <label class="mb-1.5 block text-xs font-medium uppercase text-gray-500">
                    Confirmar palavra-passe
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        class="w-full pl-10"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <!-- ACTION -->
            <div class="flex justify-end pt-2">
                <PrimaryButton
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    Redefinir senha
                </PrimaryButton>
            </div>

        </form>
    </GuestLayout>
</template>