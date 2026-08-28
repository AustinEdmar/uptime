<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import {
    Squares2X2Icon,
    SignalIcon,
    BellIcon,
    Cog6ToothIcon,
    Bars3Icon,
    XMarkIcon,
    ArrowLeftIcon,
    ChevronDownIcon,
} from '@heroicons/vue/24/outline';

const showingMobileSidebar = ref(false);

const props = defineProps({
    endpoint: Object,
});

const navItems = [
    { label: 'Dashboard', icon: Squares2X2Icon, href: route('dashboard'), active: false },
    { label: 'Endpoints', icon: SignalIcon, href: '#', active: true },
    { label: 'Alertas', icon: BellIcon, href: '#', active: false },
    { label: 'Configurações', icon: Cog6ToothIcon, href: '#', active: false },
];
</script>

<template>
    <div class="flex min-h-screen bg-indigo-50">
        <!-- ───────── SIDEBAR (desktop) ───────── -->
        <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-white border-r border-gray-100">
            <!-- Logo -->
             <div class="flex items-center gap-3 px-5 h-16 border-b border-gray-100">
                <div class="w-14 h-14 rounded-lg bg-gradient-to-r flex items-center justify-center shrink-0 shadow-sm">
                    <img :src="'/storage/sme.png'" alt="Logo" class="w-24 h-24 object-contain" />
                </div>
                <Link :href="route('dashboard')" class="font-bold text-gray-800 text-sm leading-tight truncate">
                  <p>SYSMI</p>   
                 
                </Link>
            </div>

            <!-- Voltar ao site -->
            <div class="px-4 py-4 border-b border-gray-100">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Endpoint</p>
                <Link
                    :href="`/dashboard/${endpoint?.data?.site?.id}`"
                    class="flex items-center gap-2 text-xs text-gray-500 hover:text-[#495AFF] transition-colors font-medium bg-gray-50 hover:bg-indigo-50 rounded-lg px-3 py-2"
                >
                    <ArrowLeftIcon class="w-3.5 h-3.5 shrink-0" />
                    <span class="truncate">{{ endpoint?.data?.site?.domain ?? 'Voltar ao site' }}</span>
                </Link>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
                <div>
                    <p class="px-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Monitorização
                    </p>
                    <div class="space-y-0.5">
                        <Link
                            v-for="item in navItems"
                            :key="item.label"
                            :href="item.href"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all group"
                            :class="item.active
                                ? 'bg-gradient-to-r from-[#495AFF]/10 to-[#0ACFFE]/10 text-[#495AFF]'
                                : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800'"
                        >
                            <component
                                :is="item.icon"
                                class="w-4 h-4 shrink-0"
                                :class="item.active ? 'text-[#495AFF]' : 'text-gray-400 group-hover:text-gray-600'"
                            />
                            {{ item.label }}
                            <span v-if="item.active" class="ml-auto w-1.5 h-1.5 rounded-full bg-[#495AFF]"></span>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- User (bottom) -->
            <div class="border-t border-gray-100 p-3">
                <Dropdown align="right" width="56">
                    <template #trigger>
                        <button
                            type="button"
                            class="flex w-full items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-gray-50 hover:bg-indigo-50 border border-gray-100 transition"
                        >
                            <span class="w-8 h-8 rounded-full bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] flex items-center justify-center text-white text-xs font-bold shrink-0 ring-2 ring-white shadow-sm">
                                {{ $page.props.auth.user.name?.charAt(0) ?? '?' }}
                            </span>
                            <span class="flex flex-col items-start min-w-0">
                                <span class="truncate w-full text-left">{{ $page.props.auth.user.name }}</span>
                                <span class="text-[11px] text-gray-400 truncate w-full text-left">{{ $page.props.auth.user.email }}</span>
                            </span>
                            <ChevronDownIcon class="ms-auto h-4 w-4 text-gray-400 shrink-0" />
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">Sair</DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </aside>

        <!-- ───────── SIDEBAR (mobile, slide-over) ───────── -->
        <div v-if="showingMobileSidebar" class="lg:hidden fixed inset-0 z-50 flex">
            <div class="fixed inset-0 bg-gray-900/40" @click="showingMobileSidebar = false"></div>
            <aside class="relative z-50 flex flex-col w-64 bg-white h-full shadow-xl">
                <div class="flex items-center justify-between px-5 h-16 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] flex items-center justify-center shrink-0">
                            <img :src="'/storage/sme.png'" alt="Logo" class="w-6 h-6 object-contain" />
                        </div>
                        <span class="font-bold text-gray-800 text-sm">sme.ao</span>
                    </div>
                    <button @click="showingMobileSidebar = false" class="text-gray-400 hover:text-gray-600">
                        <XMarkIcon class="w-5 h-5" />
                    </button>
                </div>

                <div class="px-4 py-4 border-b border-gray-100">
                    <Link
                        :href="`/dashboard/${endpoint?.data?.site?.id}`"
                        class="flex items-center gap-2 text-xs text-gray-500 font-medium bg-gray-50 rounded-lg px-3 py-2"
                    >
                        <ArrowLeftIcon class="w-3.5 h-3.5 shrink-0" />
                        <span class="truncate">{{ endpoint?.data?.site?.domain ?? 'Voltar ao site' }}</span>
                    </Link>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                    <ResponsiveNavLink
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        :active="item.active"
                    >
                        {{ item.label }}
                    </ResponsiveNavLink>
                </nav>

                <div class="border-t border-gray-100 p-4">
                    <div class="text-sm font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                    <div class="text-xs text-gray-500 mb-3">{{ $page.props.auth.user.email }}</div>
                    <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                </div>
            </aside>
        </div>

        <!-- ───────── CONTEÚDO PRINCIPAL ───────── -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar mobile -->
            <div class="lg:hidden flex items-center justify-between h-16 px-4 bg-white border-b border-gray-100 sticky top-0 z-40">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] flex items-center justify-center">
                        <img :src="'/storage/sme.png'" alt="Logo" class="w-5 h-5 object-contain" />
                    </div>
                    <span class="font-bold text-gray-800 text-sm">sme.ao</span>
                </div>
                <button @click="showingMobileSidebar = true" class="text-gray-500 hover:text-gray-700">
                    <Bars3Icon class="w-6 h-6" />
                </button>
            </div>

            <!-- Page Heading -->
            <header class="bg-white border-b border-gray-100 px-4 sm:px-8 py-5">
                <slot name="header" />
            </header>

            <!-- Page Content -->
            <main class="flex-1 px-4 sm:px-8 py-8">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>