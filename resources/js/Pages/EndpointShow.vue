<script setup>
import AuthenticatedEndpointLayout from '@/Layouts/AuthenticatedEndpointLayout.vue';
import Check from '@/Components/Check.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    ChartBarIcon,
    BellIcon,
    Cog6ToothIcon,
    HomeIcon,
    GlobeAltIcon,
    SignalIcon,
    DocumentTextIcon,
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    endpoint: Object,
});

// Derived stats
const checks = computed(() => props.endpoint?.data?.checks ?? []);

const successCount = computed(() =>
    checks.value.filter(c => c.response_code >= 200 && c.response_code < 300).length
);

const failCount = computed(() =>
    checks.value.filter(c => c.response_code >= 400 || c.response_code === null).length
);

const uptimePercent = computed(() => {
    if (!checks.value.length) return 100;
    return Math.round((successCount.value / checks.value.length) * 100);
});

const lastCheck = computed(() => checks.value[0] ?? null);
const lastStatus = computed(() => lastCheck.value?.response_code ?? '—');
const isUp = computed(() => lastStatus.value >= 200 && lastStatus.value < 400);

const avgResponse = computed(() => {
    const times = checks.value.filter(c => c.response_time_ms).map(c => c.response_time_ms);
    if (!times.length) return '—';
    return Math.round(times.reduce((a, b) => a + b, 0) / times.length) + ' ms';
});

const sideLinks = [
    { icon: HomeIcon, label: 'Dashboard', href: '/dashboard' },
    { icon: GlobeAltIcon, label: 'Sites', href: '/dashboard' },
    { icon: SignalIcon, label: 'Endpoints', href: '#', active: true },
    { icon: ChartBarIcon, label: 'Relatórios', href: '#' },
    { icon: BellIcon, label: 'Alertas', href: '#' },
    { icon: Cog6ToothIcon, label: 'Configurações', href: '#' },
];
</script>

<template>
    <Head title="Endpoint — Checks" />

    <AuthenticatedEndpointLayout :endpoint="endpoint">
        <div class="flex min-h-[calc(100vh-4rem)]" style="font-family: 'DM Sans', 'Segoe UI', sans-serif;">

            <!-- ───────── SIDEBAR ───────── -->
            <aside class="hidden lg:flex flex-col w-56 shrink-0 bg-white border-r border-gray-100 shadow-sm pt-6 pb-4">

                <!-- Site label -->
                <div class="px-4 mb-6">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Endpoint</p>
                    <p class="text-sm font-semibold text-gray-700 truncate">{{ endpoint?.data?.url }}</p>
                </div>

                <!-- Nav items -->
                <nav class="flex-1 px-2 space-y-0.5">
                    <a
                        v-for="link in sideLinks" :key="link.label"
                        :href="link.href"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all group"
                        :class="link.active
                            ? 'bg-gradient-to-r from-[#495AFF]/10 to-[#0ACFFE]/10 text-[#495AFF]'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800'"
                    >
                        <component
                            :is="link.icon"
                            class="w-4 h-4 shrink-0 transition-colors"
                            :class="link.active ? 'text-[#495AFF]' : 'text-gray-400 group-hover:text-gray-600'"
                        />
                        {{ link.label }}
                        <span v-if="link.active" class="ml-auto w-1.5 h-1.5 rounded-full bg-[#495AFF]"></span>
                    </a>
                </nav>

                <!-- Bottom: back link -->
                <div class="px-4 pt-4 border-t border-gray-100 mt-4">
                    <Link
                        :href="`/dashboard/${endpoint?.data?.site?.id}`"
                        class="flex items-center gap-2 text-xs text-gray-400 hover:text-[#495AFF] transition-colors font-medium"
                    >
                        <ArrowLeftIcon class="w-3.5 h-3.5" />
                        Voltar ao site
                    </Link>
                </div>
            </aside>

            <!-- ───────── MAIN CONTENT ───────── -->
            <div class="flex-1 py-8 px-4 sm:px-8 bg-indigo-50/60 overflow-x-hidden">

                <!-- Page header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <!-- Status dot -->
                            <span
                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                                :class="isUp
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-600'"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full animate-pulse"
                                    :class="isUp ? 'bg-green-500' : 'bg-red-500'"
                                ></span>
                                {{ isUp ? 'Online' : 'Offline' }}
                            </span>
                            <span class="text-xs text-gray-400 font-mono">{{ lastCheck?.checked_at ?? '' }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Histórico de Checks</h1>
                        <p class="text-sm text-gray-500 mt-0.5 font-mono">{{ endpoint?.data?.url }}</p>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center gap-2">
                        <button class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-[#495AFF]/40 hover:text-[#495AFF] transition-all">
                            <BellIcon class="w-4 h-4" />
                            Alertas
                        </button>
                        <button class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-[#495AFF]/40 hover:text-[#495AFF] transition-all">
                            <Cog6ToothIcon class="w-4 h-4" />
                            Definições
                        </button>
                    </div>
                </div>

                <!-- ── KPI Cards ── -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                    <!-- Uptime -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Uptime</span>
                            <ChartBarIcon class="w-4 h-4 text-[#495AFF]/50" />
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ uptimePercent }}<span class="text-lg text-gray-400 font-medium">%</span></p>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] transition-all duration-700"
                                :style="`width: ${uptimePercent}%`"
                            ></div>
                        </div>
                    </div>

                    <!-- Last Status -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Último Status</span>
                            <component
                                :is="isUp ? CheckCircleIcon : XCircleIcon"
                                class="w-4 h-4"
                                :class="isUp ? 'text-green-400' : 'text-red-400'"
                            />
                        </div>
                        <p
                            class="text-3xl font-bold"
                            :class="isUp ? 'text-green-600' : 'text-red-500'"
                        >{{ lastStatus }}</p>
                        <p class="text-xs text-gray-400">código de resposta HTTP</p>
                    </div>

                    <!-- Avg Response -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tempo Médio</span>
                            <ClockIcon class="w-4 h-4 text-[#0ACFFE]/60" />
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ avgResponse }}</p>
                        <p class="text-xs text-gray-400">tempo de resposta</p>
                    </div>

                    <!-- Total checks -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Checks</span>
                            <DocumentTextIcon class="w-4 h-4 text-gray-300" />
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ checks.length }}</p>
                        <div class="flex gap-3 text-xs">
                            <span class="text-green-600 font-semibold">✓ {{ successCount }}</span>
                            <span class="text-red-500 font-semibold">✗ {{ failCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- ── Checks Table ── -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    <!-- Table header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <SignalIcon class="w-4 h-4 text-[#495AFF]" />
                            <h2 class="font-semibold text-gray-800 text-sm">Checks Recentes</h2>
                            <span class="ml-1 bg-indigo-50 text-[#495AFF] text-xs font-bold px-2 py-0.5 rounded-full">
                                {{ checks.length }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">Actualizando automaticamente</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-fixed divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gradient-to-r from-[#495AFF] to-[#0ACFFE]">
                                    <th scope="col" class="pl-6 pr-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80 w-64">
                                        Verificado em
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80 w-36">
                                        Código HTTP
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80">
                                        Corpo da Resposta
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                <template v-if="checks.length">
                                    <Check
                                        v-for="check in checks"
                                        :key="check.id"
                                        :check="check"
                                    />
                                </template>
                                <tr v-else>
                                    <td colspan="3" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3 text-gray-400">
                                            <SignalIcon class="w-10 h-10 opacity-30" />
                                            <p class="text-sm font-medium">Nenhum check registado ainda</p>
                                            <p class="text-xs text-gray-300">Os checks aparecerão aqui assim que forem executados.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table footer -->
                    <div v-if="checks.length" class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-xs text-gray-400">
                            Mostrando <span class="font-semibold text-gray-600">{{ checks.length }}</span> registos
                        </p>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            <span class="text-xs text-gray-400">Em tempo real</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedEndpointLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');
</style>