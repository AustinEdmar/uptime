<script setup>
import AuthenticatedEndpointLayout from '@/Layouts/AuthenticatedEndpointLayout.vue';
import Check from '@/Components/Check.vue';
import { Head } from '@inertiajs/vue3';
import {
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    ChartBarIcon,
    BellIcon,
    Cog6ToothIcon,
    SignalIcon,
    DocumentTextIcon,
    GlobeAltIcon,
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

// ── Sparkline do tempo de resposta (últimos 20 checks, cronológico) ──
const sparklinePoints = computed(() => {
    const times = checks.value
        .slice(0, 20)
        .map(c => c.response_time_ms)
        .filter(v => v !== null && v !== undefined)
        .reverse();

    if (times.length < 2) return { path: '', points: [] };

    const max = Math.max(...times);
    const min = Math.min(...times);
    const range = max - min || 1;
    const width = 100;
    const height = 32;
    const step = width / (times.length - 1);

    const points = times.map((t, i) => {
        const x = i * step;
        const y = height - ((t - min) / range) * height;
        return { x, y, value: t };
    });

    const path = points.map((p, i) => `${i === 0 ? 'M' : 'L'} ${p.x} ${p.y}`).join(' ');
    return { path, points };
});
</script>

<template>
    <Head title="Endpoint — Checks" />

    <AuthenticatedEndpointLayout :endpoint="endpoint">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                            :class="isUp ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full animate-pulse"
                                :class="isUp ? 'bg-green-500' : 'bg-red-500'"
                            ></span>
                            {{ isUp ? 'Online' : 'Offline' }}
                        </span>
                        <span class="text-xs text-gray-400 font-mono">{{ lastCheck?.checked_at ?? '' }}</span>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">Histórico de Checks</h1>
                </div>

                <!-- Endereço monitorado -->
                <div class="flex items-center gap-2 bg-gradient-to-r from-[#495AFF]/10 to-[#0ACFFE]/10 text-[#495AFF] rounded-full px-4 py-2 text-sm font-semibold self-start sm:self-auto">
                    <GlobeAltIcon class="w-4 h-4 shrink-0" />
                    <span class="font-mono truncate max-w-[240px]">{{ endpoint?.data?.url }}</span>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- ── Banner de resumo em gradiente ── -->
            <div class="bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] rounded-2xl p-6 text-white shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-white/70 mb-1">Uptime</p>
                    <p class="text-4xl font-bold">{{ uptimePercent }}<span class="text-xl text-white/70 font-medium">%</span></p>
                </div>
                <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-white/70 mb-1">Último status</p>
                    <p class="text-2xl font-bold">{{ lastStatus }}</p>
                </div>
                <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-white/70 mb-1">Tempo médio</p>
                    <p class="text-2xl font-bold">{{ avgResponse }}</p>
                </div>
                <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                <div class="flex-1 min-w-[140px] max-w-[220px]">
                    <p class="text-xs font-semibold uppercase tracking-widest text-white/70 mb-1">Tendência</p>
                    <svg v-if="sparklinePoints.path" viewBox="0 0 100 32" class="w-full h-8" preserveAspectRatio="none">
                        <path :d="sparklinePoints.path" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0.9" />
                        <circle
                            v-if="sparklinePoints.points.length"
                            :cx="sparklinePoints.points[sparklinePoints.points.length - 1].x"
                            :cy="sparklinePoints.points[sparklinePoints.points.length - 1].y"
                            r="2.5"
                            fill="white"
                        />
                    </svg>
                    <p v-else class="text-xs text-white/60">Dados insuficientes</p>
                </div>
            </div>

            <!-- ── KPI Cards ── -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Uptime</span>
                        <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <ChartBarIcon class="w-4 h-4 text-[#495AFF]" />
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ uptimePercent }}<span class="text-lg text-gray-400 font-medium">%</span></p>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] transition-all duration-700"
                            :style="`width: ${uptimePercent}%`"
                        ></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Último Status</span>
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center" :class="isUp ? 'bg-green-50' : 'bg-red-50'">
                            <component :is="isUp ? CheckCircleIcon : XCircleIcon" class="w-4 h-4" :class="isUp ? 'text-green-500' : 'text-red-500'" />
                        </span>
                    </div>
                    <p class="text-3xl font-bold" :class="isUp ? 'text-green-600' : 'text-red-500'">{{ lastStatus }}</p>
                    <p class="text-xs text-gray-400">código de resposta HTTP</p>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tempo Médio</span>
                        <span class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center">
                            <ClockIcon class="w-4 h-4 text-[#0ACFFE]" />
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ avgResponse }}</p>
                    <p class="text-xs text-gray-400">tempo de resposta</p>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Checks</span>
                        <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                            <DocumentTextIcon class="w-4 h-4 text-gray-400" />
                        </span>
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
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <SignalIcon class="w-4 h-4 text-[#495AFF]" />
                        <h2 class="font-semibold text-gray-800 text-sm">Checks Recentes</h2>
                        <span class="ml-1 bg-indigo-50 text-[#495AFF] text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ checks.length }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        <span class="text-xs text-gray-400">Actualizando automaticamente</span>
                    </div>
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
    </AuthenticatedEndpointLayout>
</template>