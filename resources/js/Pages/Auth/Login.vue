<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({ email: '', password: '', remember: false });

const submit = () => {
    form.post(route('login'), { onFinish: () => form.reset('password') });
};
</script>

<template>
    <GuestLayout>
        <Head title="Entrar" />

        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">Bem-vindo de volta</h2>
            <p class="mt-1 text-sm text-gray-500">Entre com suas credenciais para continuar</p>
        </div>

        <div v-if="status" class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500">
                    E-mail
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <TextInput
                        id="email" type="email" v-model="form.email"
                        class="mt-0 block w-full pl-9"
                        placeholder="seu@email.gov.ao"
                        required autofocus autocomplete="username"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-gray-500">
                    Senha
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <TextInput
                        id="password" type="password" v-model="form.password"
                        class="mt-0 block w-full pl-9"
                        placeholder="••••••••"
                        required autocomplete="current-password"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex cursor-pointer items-center gap-2">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="text-sm text-gray-500">Lembrar-me</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-[#495AFF] hover:text-[#3748ff]"
                >
                    Esqueceu a senha?
                </Link>
            </div>

            <PrimaryButton
                class="w-full justify-center"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                Entrar
            </PrimaryButton>
        </form>

          <div class="flex items-center justify-center mt-3">
    <Link
        v-if="canResetPassword"
        :href="route('register')"
        class="text-sm text-[#495AFF] hover:text-[#3748ff]"
    >
        Registrar-se
    </Link>
</div>

        <p class="mt-8 text-center text-xs text-gray-400">
            Acesso restrito a servidores autorizados
        </p>
    </GuestLayout>
</template>