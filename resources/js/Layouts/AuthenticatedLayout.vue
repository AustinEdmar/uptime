<script setup>
import { computed, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import SiteSelector from '@/Components/SiteSelector.vue';
import {
    Squares2X2Icon,
    SignalIcon,
    BellIcon,
    Cog6ToothIcon,
    Bars3Icon,
    XMarkIcon,
    UserCircleIcon,
    ArrowRightOnRectangleIcon,
} from '@heroicons/vue/24/outline';


const showingMobileSidebar = ref(false);

const props = defineProps({
    site: {
        type: Object,
        default: null,
    },
    sites: {
        type: Object,
        default: null,
    },
    endpoints: {
        type: Object,
        default: null,
    },
});

// Itens da navegação principal. Os que ainda não têm rota dedicada
// apontam para '#' — troca por `route('...')` quando existirem.
const navItems = [
    { label: 'Dashboard', icon: Squares2X2Icon, href: route('dashboard'), active: route().current('dashboard') },
    { label: 'Endpoints', icon: SignalIcon, href: route('dashboard'), active: false },
    { label: 'Alertas', icon: BellIcon, href: '#', active: false },
    { label: 'Configurações', icon: Cog6ToothIcon, href: '#', active: false },
];

// ── Stats do header, calculados aqui em vez de em cada página ──
const endpointList = computed(() => props.endpoints?.data ?? []);

const totalEndpoints = computed(() => endpointList.value.length);

const onlineCount = computed(
    () => endpointList.value.filter((e) => e.lastest_check?.is_successful === true).length
);

const checkedCount = computed(() => endpointList.value.filter((e) => e.lastest_check).length);

const offlineCount = computed(() => checkedCount.value - onlineCount.value);

const avgUptime = computed(() => {
    const withUptime = endpointList.value.filter(
        (e) => e.uptime_percentage !== null && e.uptime_percentage !== undefined
    );
    if (!withUptime.length) return null;
    const sum = withUptime.reduce((acc, e) => acc + e.uptime_percentage, 0);
    return Math.round(sum / withUptime.length);
});
</script>

<template>
    <div class="flex min-h-screen bg-indigo-50">
        <!-- ───────── SIDEBAR (desktop) ───────── -->
    <aside
    class="hidden lg:flex lg:flex-col w-64 shrink-0
           bg-white/95
           border-r border-gray-100
           shadow-[4px_0_24px_rgba(15,23,42,0.03)]"
>
    <!-- Logo -->
    <div
        class="flex items-center gap-3 px-5 h-20
               border-b border-gray-100"
    >
        <Link
            :href="route('dashboard')"
            class="flex items-center gap-3 min-w-0"
        >
            <!-- Logo -->
            <div
                class="relative flex h-11 w-11 shrink-0
                       items-center justify-center
                       rounded-xl
                       bg-gradient-to-r from-[#495AFF] to-[#0ACFFE]
                       shadow-[0_5px_18px_rgba(73,90,255,0.25)]
                       overflow-hidden"
            >
                <!-- Glow -->
                <div
                    class="absolute -top-4 -right-4
                           h-10 w-10 rounded-full
                           bg-white/30 blur-xl"
                ></div>

                <img
                    src="/storage/sme.png"
                    alt="SYSMI"
                    class="relative z-10 h-9 w-9 object-contain"
                />
            </div>

            <div class="min-w-0">
                <p class="text-sm font-bold tracking-tight text-gray-800">
                    SYSMI
                </p>

                <p class="text-[10px] text-gray-400 truncate">
                    Monitoramento de Incidentes
                </p>
            </div>
        </Link>
    </div>


    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-6">

        <!-- Section -->
        <div class="mb-7">
            <p
                class="px-3 mb-3
                       text-[10px] font-bold
                       uppercase tracking-[0.15em]
                       text-gray-400"
            >
                Monitorização
            </p>

            <div class="space-y-1">

                <Link
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    class="group relative flex items-center gap-3
                           px-3 py-2.5 rounded-xl
                           text-sm font-medium
                           transition-all duration-200"
                    :class="item.active
                        ? 'bg-gradient-to-r from-[#495AFF]/10 to-[#0ACFFE]/10 text-[#495AFF] shadow-sm'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800'"
                >

                    <!-- Active indicator -->
                    <span
                        v-if="item.active"
                        class="absolute left-0 top-1/2
                               -translate-y-1/2
                               h-7 w-1 rounded-r-full
                               bg-gradient-to-b from-[#495AFF] to-[#0ACFFE]
                               shadow-[0_0_10px_rgba(73,90,255,0.5)]"
                    ></span>

                    <!-- Icon container -->
                    <span
                        class="flex h-8 w-8 shrink-0
                               items-center justify-center
                               rounded-lg transition-all"
                        :class="item.active
                            ? 'bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] shadow-[0_4px_12px_rgba(73,90,255,0.25)]'
                            : 'bg-gray-50 group-hover:bg-white group-hover:shadow-sm'"
                    >
                        <component
                            :is="item.icon"
                            class="h-4 w-4"
                            :class="item.active
                                ? 'text-white'
                                : 'text-gray-400 group-hover:text-[#495AFF]'"
                        />
                    </span>

                    <span>
                        {{ item.label }}
                    </span>

                    <!-- Active dot -->
                    <span
                        v-if="item.active"
                        class="ml-auto h-1.5 w-1.5 rounded-full
                               bg-[#495AFF]
                               shadow-[0_0_7px_rgba(73,90,255,0.8)]"
                    ></span>
                </Link>

            </div>
        </div>


       

    </nav>


    <!-- User -->
    <div class="border-t border-gray-100 p-3">

        <Dropdown align="right" width="56" placement="top">

            <template #trigger>

                <button
                    type="button"
                    class="group flex w-full items-center gap-3
                           rounded-xl px-3 py-2.5
                           text-sm font-medium text-gray-600
                           transition-all duration-200
                           hover:bg-gray-50"
                >

                    <!-- Avatar -->
                    <span
                        class="flex h-9 w-9 shrink-0
                               items-center justify-center
                               rounded-xl
                               bg-gradient-to-r
                               from-[#495AFF] to-[#0ACFFE]
                               text-xs font-bold text-white
                               shadow-[0_4px_12px_rgba(73,90,255,0.25)]"
                    >
                        {{ $page.props.auth.user.name?.charAt(0) ?? '?' }}
                    </span>

                    <!-- User -->
                    <span class="flex-1 min-w-0 text-left">
                        <span
                            class="block truncate text-sm font-semibold text-gray-700"
                        >
                            {{ $page.props.auth.user.name }}
                        </span>

                        <span
                            class="block truncate text-[10px] text-gray-400"
                        >
                            Conta de utilizador
                        </span>
                    </span>

                    <svg
                        class="h-4 w-4 shrink-0
                               text-gray-400
                               transition-transform
                               group-hover:text-[#495AFF]"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 010 1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>

                </button>

            </template>


            <template #content>

                <DropdownLink
                    :href="route('profile.edit')"
                    class="flex items-center gap-3 px-4 py-2.5
                           text-sm font-medium text-gray-700
                           transition-colors
                           hover:bg-blue-50
                           hover:text-[#495AFF]"
                >
                    <UserCircleIcon class="h-5 w-5 text-gray-400" />
                    <span>Profile</span>
                </DropdownLink>


                <DropdownLink
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex w-full items-center gap-3
                           px-4 py-2.5
                           text-sm font-medium text-gray-700
                           transition-colors
                           hover:bg-red-50
                           hover:text-red-600"
                >
                    <ArrowRightOnRectangleIcon
                        class="h-5 w-5 text-gray-400"
                    />

                    <span>Sair</span>
                </DropdownLink>

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
                         <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] flex items-center justify-center shrink-0 shadow-sm">
                    <img :src="'/storage/sme.png'" alt="Logo" class="w-7 h-7 object-contain" />
                </div>
                <Link :href="route('dashboard')" class="font-bold text-gray-800 text-sm leading-tight truncate">
                  <p>SYSMI</p>   
                 
                </Link>
                    </div>
                    <button @click="showingMobileSidebar = false" class="text-gray-400 hover:text-gray-600">
                        <XMarkIcon class="w-5 h-5" />
                    </button>
                </div>

                <div class="px-4 py-4 border-b border-gray-100">
                    <SiteSelector :sites="sites" />
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
                     <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] flex items-center justify-center shrink-0 shadow-sm">
                    <img :src="'/storage/sme.png'" alt="Logo" class="w-7 h-7 object-contain" />
                </div>
                <Link :href="route('dashboard')" class="font-bold text-gray-800 text-sm leading-tight truncate">
                  <p>SYSMI</p>   
                 
                </Link>
                </div>
                <button @click="showingMobileSidebar = true" class="text-gray-500 hover:text-gray-700">
                    <Bars3Icon class="w-6 h-6" />
                </button>
            </div>

            <!-- Page Heading -->
           <header
    class="relative rounded-md
           px-4 sm:px-8 py-6
           bg-gradient-to-r from-[#495AFF] to-[#0ACFFE]
           shadow-[0_10px_40px_rgba(73,90,255,0.30)]"
>
    <!-- Camada decorativa: só esta tem overflow-hidden, para não cortar/quebrar o dropdown do Site Selector -->
    <div class="absolute inset-0 rounded-md overflow-hidden pointer-events-none">
        <!-- Glow superior direito -->
        <div
            class="absolute -top-24 -right-16
                   h-72 w-72 rounded-full
                   bg-white/20 blur-3xl"
        ></div>

        <!-- Glow azul inferior -->
        <div
            class="absolute -bottom-32 left-1/3
                   h-64 w-96 rounded-full
                   bg-[#0ACFFE]/30 blur-3xl"
        ></div>

        <!-- Glow azul/roxo à esquerda -->
        <div
            class="absolute top-1/2 -left-32
                   h-64 w-64 -translate-y-1/2
                   rounded-full
                   bg-[#495AFF]/40 blur-3xl"
        ></div>

        <!-- Reflexo de luz -->
        <div
            class="absolute inset-0
                   bg-gradient-to-r
                   from-white/[0.08]
                   via-transparent
                   to-white/[0.05]"
        ></div>

        <!-- Brilho diagonal -->
        <div
            class="absolute -top-1/2 left-1/4
                   h-[200%] w-32
                   rotate-[25deg]
                   bg-white/[0.04]
                   blur-2xl"
        ></div>
    </div>

    <!-- Conteúdo: fora da camada com overflow-hidden, dropdown do Site Selector funciona normalmente -->
    <div class="relative z-10 flex items-center justify-between gap-4">
   <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center
                           rounded-xl
                           bg-white/15
                           border border-white/20
                           shadow-[0_0_20px_rgba(255,255,255,0.15)]
                           backdrop-blur-md"
                >
                    <svg
                        class="h-5 w-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                        />
                    </svg>
                </div>
        <div class="flex-1 min-w-0">
            <h1 class="text-xl font-bold text-white tracking-tight">
                {{ site?.data?.domain ?? 'Dashboard' }}
            </h1>

            <p v-if="site" class="text-sm text-white/80 mt-0.5">
                {{ totalEndpoints }}
                endpoint{{ totalEndpoints === 1 ? '' : 's' }}
                monitorado{{ totalEndpoints === 1 ? '' : 's' }}

                <span v-if="avgUptime !== null">
                    · {{ avgUptime }}% uptime médio
                </span>

                <span v-if="offlineCount">
                    ·
                    <span class="text-white font-semibold">
                        {{ offlineCount }} offline
                    </span>
                </span>
            </p>
        </div>

        <!-- Selector -->
        <div class="relative shrink-0">
        
            <SiteSelector :sites="sites" />
        </div>

    </div>
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