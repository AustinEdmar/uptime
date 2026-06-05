<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen ">
        <!-- Painel lateral gradiente -->
        <div class="relative hidden w-72 flex-shrink-0 overflow-hidden bg-gradient-to-b from-[#495AFF] to-[#0ACFFE] lg:flex lg:flex-col lg:justify-between lg:p-10">

            <!-- Overlay escuro sutil para dar profundidade -->
            <div class="absolute inset-0 bg-black/10" />

            <!-- Círculos decorativos com branco translúcido -->
            <div class="absolute -left-16 -top-16 h-56 w-56 rounded-full bg-white/10" />
            <div class="absolute -bottom-12 -right-12 h-48 w-48 rounded-full bg-white/10" />
            <div class="absolute left-1/2 top-1/2 h-32 w-32 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/5" />

            <div class="relative z-10">
            
                <Link href="/">
                    <img :src="'/storage/sme.png'" alt="Logo SME" class="mb-6 h-12 w-12 object-contain " />
                </Link>
                <h1 class="text-xl font-semibold text-white">SMI</h1>
                <p class="mt-2 text-sm leading-relaxed text-white/70">
                    Sistema de Monitoramento de Incidentes
                </p>
            </div>

            <p class="relative z-10 text-xs text-white/40">© {{ new Date().getFullYear() }} SME</p>
        </div>

        <!-- Área principal do formulário -->
        <div class="flex flex-1 items-center justify-center bg-indigo-50 px-6 py-12">
            <div class="w-full max-w-sm">
                <!-- Logo mobile -->
                <div class="mb-8 flex justify-center lg:hidden">
                    <Link href="/">
                        <img :src="'/storage/sme.png'" alt="Logo SME" class="h-14 w-14 object-contain" />
                    </Link>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>