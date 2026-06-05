<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import SiteSelector from '@/Components/SiteSelector.vue';
import { GlobeAltIcon } from '@heroicons/vue/24/outline';

const showingNavigationDropdown = ref(false);

/* defineProps({
    site: Object,
    sites: Object
}); */
defineProps({
    site: {
        type: Object,
        default: null,
    },
    sites: {
        type: Object,
        default: null,
    },
});

</script>

<template>
    <div>
        <div class="min-h-screen bg-indigo-50">
          <div class="px-7 py-2 mb-12">

            <!-- Wrapper fixo -->
<div class="fixed top-0 left-0 right-0 z-50">

    <!-- Container externo IGUAL ao conteúdo -->
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-4">

        <!-- NAVBAR (igual à tua, sem mexer dentro) -->
        <nav
            class="rounded-xl bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] text-white shadow-sm"
        >
            <!-- TODO O TEU CÓDIGO INTERNO DA NAVBAR CONTINUA IGUAL -->
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">

        <!-- ESQUERDA: Logo + Título -->
        <div class="flex items-center gap-6">
            <!-- Logo -->
            <Link :href="route('dashboard')" class="flex shrink-0 items-center">
               

                 <img
            :src="'/storage/sme.png'"
            alt="Logo"
            class="w-auto h-12 object-contain animate-fade-in"
        />
            </Link>


            <p>|</p>

              <!-- Ícone de mundo -->
           <div class="flex items-center gap-2">
            <GlobeAltIcon class="w-8 h-8 text-white" />

            <!-- Título -->
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ site?.data.domain }}
            </h2>
           </div>
        </div>

        <!-- DIREITA: Site Selector + User -->
        <div class="flex items-center gap-6">

            <!-- Select site -->
            <SiteSelector :sites="sites" />

            <!-- User dropdown (desktop) -->
            <div class="hidden sm:flex sm:items-center">
                <div class="relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-600 transition hover:text-gray-800 focus:outline-none"
                            >
                                {{ $page.props.auth.user.name }}

                                <svg
                                    class="ms-2 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">
                                Profile
                            </DropdownLink>

                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Sair
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <!-- Hamburger (mobile) -->
            <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="sm:hidden inline-flex items-center justify-center rounded-md p-2 text-white hover:bg-white/10 focus:outline-none"
            >
                <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        :class="{ hidden: showingNavigationDropdown }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                        :class="{ hidden: !showingNavigationDropdown }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

        </div>
    </div>
</div>


                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
        </nav>

    </div>
</div>

              
          </div>

            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
